<?php

namespace App\Http\Controllers;

use App\Exports\CertificateTemplate;
use App\Imports\CertificateImport;
use App\Models\Certificate;
use App\Models\Diklat;
use App\Models\Employee;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class CertificateController extends Controller
{

    private function sanitizeFileName($fileName)
    {

        $extension = pathinfo($fileName, PATHINFO_EXTENSION);

        $name = pathinfo($fileName, PATHINFO_FILENAME);

        $name = preg_replace('/[^a-zA-Z0-9]/', '_', $name);

        $name = preg_replace('/_+/', '_', $name);

        $name = trim($name, '_');

        return time() . '_' . $name . '.' . $extension;
    }

    public function getIndex()
    {
        $roleId = Auth::user()->role_id;
        $diklats = Diklat::all();
        $employees = Employee::all();
        $vendors = Vendor::when($roleId == 2, function ($q) {
            $q->where('id', Auth::user()->vendor_id);
        })
            ->get();
        return view('certificate.index', compact('diklats', 'employees', 'vendors'));
    }

    public function getData(Request $request)
    {
        $roleId = Auth::user()->role_id;
        $unitId = Auth::user()->unit_id;

        $query = Certificate::with(['diklat', 'employee', 'vendor'])
            ->select(DB::raw('certificates.*, certificates.id as id'))
            ->join('diklats', 'certificates.diklat_id', '=', 'diklats.id')
            ->when($roleId == 1, function ($q) use ($unitId) {
                // Filter for HTD role - only show certificates from their unit
                $q->where('diklats.unit_id', $unitId);
            })
            ->when($roleId == 2, function ($q) {
                // Filter for Vendor role
                $q->where('certificates.vendor_id', Auth::user()->vendor_id);
            });

        return datatables($query)
            ->addIndexColumn()
            ->toJson();
    }

    public function postStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'diklat_id' => 'required|exists:diklats,id',
            'employee_id' => 'required|exists:employees,id',
            'vendor_id' => 'required|exists:vendors,id',
            'fungsi' => 'required',
            'certificate_number' => 'required|unique:certificates,certificate_number',
            'certificate_date' => 'required|date',
            // 'certificate_expire' => 'required|date|after:certificate_date',
            'certificate_path' => 'nullable|file|mimes:pdf|max:5120', // 5MB max
            'certificate_type' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $data = new Certificate($request->except('certificate_path'));

            if ($request->hasFile('certificate_path')) {
                $file = $request->file('certificate_path');
                $fileName = $this->sanitizeFileName($file->getClientOriginalName());
                $file->move(public_path('certificates'), $fileName);

                $data->certificate_path = '/certificates/' . $fileName;
            }

            $data->save();

            return response()->json([
                'status' => 200,
                'message' => 'Data berhasil ditambahkan',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            // Delete uploaded file if save fails
            if (isset($fileName) && file_exists(public_path('certificates/' . $fileName))) {
                unlink(public_path('certificates/' . $fileName));
            }

            return response()->json([
                'status' => 500,
                'message' => 'Error saving certificate',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getShow($id)
    {
        $data = Certificate::findOrFail($id);
        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    }

    public function postUpdate(Request $request, $id)
    {
        $data = Certificate::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'diklat_id' => 'required|exists:diklats,id',
            'employee_id' => 'required|exists:employees,id',
            'vendor_id' => 'required|exists:vendors,id',
            'fungsi' => 'required',
            'certificate_number' => 'required|unique:certificates,certificate_number,' . $id,
            'certificate_date' => 'required|date',
            // 'certificate_expire' => 'required|date|after:certificate_date',
            'certificate_path' => 'nullable|file|mimes:pdf|max:5120', // 5MB max
            'certificate_type' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $payload = $request->all();
            // Handle file upload if new file is provided
            if ($request->hasFile('certificate_path')) {
                // Delete old file if exists
                if ($data->certificate_path && file_exists(public_path($data->certificate_path))) {
                    unlink(public_path($data->certificate_path));
                }

                // Upload new file
                $file = $request->file('certificate_path');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('certificates'), $fileName);

                // Update path in request data
                $path = '/certificates/' . $fileName;
                $payload['certificate_path'] = $path;
            }

            $data->update($payload);

            return response()->json([
                'status' => 200,
                'message' => 'Data berhasil diupdate',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            // Delete newly uploaded file if save fails
            if (isset($fileName) && file_exists(public_path('certificates/' . $fileName))) {
                unlink(public_path('certificates/' . $fileName));
            }

            return response()->json([
                'status' => 500,
                'message' => 'Error updating certificate',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function postUploadFile(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'certificate_path' => 'required|file|mimes:pdf|max:5120', // 5MB max
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $certificate = Certificate::findOrFail($id);

            // Delete old file if exists
            if ($certificate->certificate_path && file_exists(public_path($certificate->certificate_path))) {
                unlink(public_path($certificate->certificate_path));
            }

            // Upload new file
            $file = $request->file('certificate_path');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('certificates'), $fileName);

            // Update database
            $certificate->certificate_path = 'certificates/' . $fileName;
            $certificate->save();

            return response()->json([
                'status' => 200,
                'message' => 'File uploaded successfully',
                'data' => $certificate
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Error uploading file',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteDestroy($id)
    {
        $data = Certificate::findOrFail($id);
        $data->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Data berhasil dihapus'
        ]);
    }

    public function getTemplate($diklat_id)
    {
        try {
            $diklat = Diklat::findOrFail($diklat_id);
            return Excel::download(
                new CertificateTemplate($diklat_id),
                'certificate_template_' . $diklat->slug . '.xlsx'
            );
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Error generating template',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function postImport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            // Save file to public path
            $fileName = time() . '.' . $request->file->getClientOriginalExtension();
            $request->file->move(public_path('/uploadedfiles'), $fileName);
            Excel::import(new CertificateImport, public_path('/uploadedfiles/' . $fileName));

            // Delete the uploaded file after import
            unlink(public_path('/uploadedfiles/' . $fileName));

            return response()->json([
                'status' => 200,
                'message' => 'Data imported successfully'
            ]);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = [];

            foreach ($failures as $failure) {
                $errors[] = "Row {$failure->row()}: {$failure->errors()[0]}";
            }

            return response()->json([
                'status' => 422,
                'errors' => $errors
            ], 422);
        }
    }

    public function getDownload($id)
    {
        $certificate = Certificate::findOrFail($id);
        $filePath = public_path($certificate->certificate_path);

        if (file_exists($filePath)) {
            return response()->download($filePath);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'File not found'
            ], 404);
        }
    }
}
