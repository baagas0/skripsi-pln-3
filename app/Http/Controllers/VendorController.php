<?php

namespace App\Http\Controllers;

use App\Imports\VendorImport;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;


class VendorController extends Controller
{
    public function getIndex() 
    {
        return view('vendor.index');
    }

    public function getData(Request $request) 
    {
        $query = Vendor::query();

        return datatables($query)
            ->filter(function ($query) use ($request) {
                if ($request->search['value']) {
                    $query->where(function($q) use ($request) {
                        $q->where('name', 'like', "%{$request->search['value']}%");
                    });
                }
    
                // Individual column filtering
                foreach ($request->columns as $column) {
                    if (isset($column['search']['value']) && $column['search']['value'] !== '') {
                        if (in_array($column['data'], ['name'])) {
                            $query->where($column['data'], 'like', "%{$column['search']['value']}%");
                        }
                    }
                }
            })
            ->orderColumn('name', function ($query, $order) {
                $query->orderBy('name', $order);
            })
            ->addColumn('DT_RowId', function ($data) {
                return 'row_' . $data->id;
            })
            ->addIndexColumn()
            ->toJson();
    }

    public function postStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:vendors,name',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = Vendor::create($request->all());

        $users = [
            'name' => 'Vendor ' . $data->name,
            'email' => str_replace(' ', '', strtolower($data->name)) . '@gmail.com',
            'password' => bcrypt('pln#573*'),
            'role_id' => 2, // Assuming 2 is vendor role
            'vendor_id' => $data->id,
        ];

        User::create($users);

        return response()->json([
            'status' => 200,
            'message' => 'Data berhasil ditambahkan',
            'data' => $data
        ]);
    }

    public function getShow($id)
    {
        $data = Vendor::findOrFail($id);
        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    }

    public function postUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:vendors,name,'.$id,
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = Vendor::findOrFail($id);
        $data->update($request->all());

        $users = [
            'name' => 'Vendor ' . $data->name,
            'email' => str_replace(' ', '', strtolower($data->name)) . '@gmail.com',
            'password' => bcrypt('pln#573*'),
            'role_id' => 2, // Assuming 2 is vendor role
            'vendor_id' => $data->id,
        ];

        User::where('vendor_id', $data->id)->update($users);

        return response()->json([
            'status' => 200,
            'message' => 'Data berhasil diupdate',
            'data' => $data
        ]);
    }

    public function deleteDestroy($id)
    {
        $data = Vendor::findOrFail($id);
        $data->delete();

        User::where('vendor_id', $id)->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Data berhasil dihapus'
        ]);
    }

    public function postImport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            // Save file to public path
            $fileName = time().'.'.$request->file->getClientOriginalExtension();
            $request->file->move(public_path('/uploadedfiles'), $fileName);
            Excel::import(new VendorImport, public_path('/uploadedfiles/'.$fileName));

            // Delete the uploaded file after import
            unlink(public_path('/uploadedfiles/'.$fileName));

            return response()->json([
                'status' => 200,
                'message' => 'Data vendor berhasil diimpor'
            ]);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = [];
            
            foreach ($failures as $failure) {
                $errors[] = "Baris {$failure->row()}: {$failure->errors()[0]}";
            }

            return response()->json([
                'status' => 422,
                'errors' => $errors
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'errors' => ["Terjadi kesalahan: " . $e->getMessage()]
            ], 500);
        }
    }
}
