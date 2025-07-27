<?php

namespace App\Http\Controllers;

use App\Exports\EmployeeTemplateExport;
use App\Imports\EmployeesImport;
use App\Models\Employee;
use App\Models\User;
use App\Models\Unit;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{
    // Default values for user creation
    private const DEFAULT_ROLE_ID = 5; // employee role
    private const DEFAULT_PASSWORD = 'pln#573*';

    public function getIndex() 
    {
        $auth = Auth::user();
        $units = Unit::when($auth->role_id == 1, function ($q) use ($auth) {
            $q->where('id', $auth->unit_id);
        })->when($auth->role_id !== 1 && $auth->role_id !== 7, function ($q) use ($auth) {
            // $q->where('area_id', $auth->area_id);
        })->get();
        $areas = Area::all();
        return view('employee.index', compact('units', 'areas'));
    }

    public function getData(Request $request) 
    {
        $auth = Auth::user();
        $search = $request->search;
        $searchValue = isset($search['value']) ? $search['value'] : null;

        $data = Employee::with(['unit', 'area'])
            ->where('unit_id', $auth->unit_id)
            ->when($searchValue, function ($q) use ($searchValue) {
                $q->where(function ($query) use ($searchValue) {
                    $query->where('nip', 'like', '%' . $searchValue . '%')
                        ->orWhere('name', 'like', '%' . $searchValue . '%')
                        ->orWhere('position', 'like', '%' . $searchValue . '%')
                        ->orWhere('email', 'like', '%' . $searchValue . '%')
                        ->orWhereHas('unit', function ($query) use ($searchValue) {
                            $query->where('name', 'like', '%' . $searchValue . '%');
                        })
                        ->orWhereHas('area', function ($query) use ($searchValue) {
                            $query->where('name', 'like', '%' . $searchValue . '%');
                        });
                });
            })->get();
        return datatables($data)->toJson();
    }

    public function postStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nip' => 'required|unique:employees,nip',
            'name' => 'required',
            'position' => 'required',
            'unit_id' => 'required|exists:units,id',
            'area_id' => 'required|exists:areas,id',
            'email' => 'required|email|unique:employees,email|unique:users,email',
            'birth_date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            // Create employee
            $employee = Employee::create([
                ...$request->all(),
                'password' => Hash::make(self::DEFAULT_PASSWORD),
            ]);

            // Create corresponding user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make(self::DEFAULT_PASSWORD),
                'role_id' => self::DEFAULT_ROLE_ID,
                'unit_id' => $request->unit_id,
                'area_id' => $request->area_id,
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Data berhasil ditambahkan',
                'data' => $employee
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Error adding data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getShow($id)
    {
        $employee = Employee::with(['unit', 'area'])->findOrFail($id);
        return response()->json([
            'status' => 200,
            'data' => $employee
        ]);
    }

    public function postUpdate(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nip' => 'required|unique:employees,nip,' . $id,
            'name' => 'required',
            'position' => 'required',
            'unit_id' => 'required|exists:units,id',
            'area_id' => 'required|exists:areas,id',
            'email' => 'required|email|unique:employees,email,' . $id . '|unique:users,email,' . User::where('email', $employee->email)->first()?->id,
            'birth_date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            // Update employee with default password if not set
            $updateData = $request->all();
            if (!isset($updateData['password'])) {
                $updateData['password'] = Hash::make(self::DEFAULT_PASSWORD);
            }
            $employee->update($updateData);

            // Update or create corresponding user
            $user = User::where('email', $employee->getOriginal('email'))->first();
            
            if ($user) {
                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'unit_id' => $request->unit_id,
                    'area_id' => $request->area_id,
                ]);
            } else {
                User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make(self::DEFAULT_PASSWORD),
                    'role_id' => self::DEFAULT_ROLE_ID,
                    'unit_id' => $request->unit_id,
                    'area_id' => $request->area_id,
                ]);
            }

            return response()->json([
                'status' => 200,
                'message' => 'Data berhasil diupdate',
                'data' => $employee
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Error updating data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteDestroy($id)
    {
        try {
            $employee = Employee::findOrFail($id);
            
            // Delete associated user if exists
            User::where('email', $employee->email)->delete();
            
            $employee->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Error deleting data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function postBulkDestroy(Request $request)
    {
        try {
            $employees = Employee::whereIn('id', $request->id)->get();
            
            // Delete associated users
            User::whereIn('email', $employees->pluck('email'))->delete();
            
            Employee::whereIn('id', $request->id)->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Error deleting data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function postSetPassword(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $employee = Employee::findOrFail($id);
        $employee->password = bcrypt($request->password);
        $employee->save();
        
        // Also update the corresponding user's password
        $user = User::where('email', $employee->email)->first();
        if ($user) {
            $user->password = bcrypt($request->password);
            $user->save();
        }

        return response()->json([
            'status' => 200,
            'message' => 'Password berhasil diatur'
        ]);
    }

    public function getTemplate()
    {
        return Excel::download(new EmployeeTemplateExport, 'employee_import_template.xlsx');
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
            Excel::import(new EmployeesImport, public_path('/uploadedfiles/'.$fileName));

            // Delete the uploaded file after import
            // unlink(public_path($filePath));
            unlink(public_path('/uploadedfiles/'.$fileName));

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
}