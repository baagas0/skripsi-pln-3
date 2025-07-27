<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Unit;
use App\Models\Area;
use App\Models\Role;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function getIndex() 
    {
        $units = Unit::all();
        $areas = Area::all();
        $vendors = Vendor::all();
        $roles = Role::whereIn('id', [1, 3, 4, 6, 7, 8])->get();
        return view('user.index', compact('units', 'areas', 'roles', 'vendors'));
    }

    public function getData(Request $request) 
    {
        $query = User::with(['role', 'unit', 'area', 'vendor'])->where('role_id', '!=', 999);

        return datatables($query)
            ->filter(function ($query) use ($request) {
                if ($request->search['value']) {
                    $query->where(function($q) use ($request) {
                        $q->where('name', 'like', "%{$request->search['value']}%")
                          ->orWhere('email', 'like', "%{$request->search['value']}%");
                    });
                }
            })
            ->addColumn('role_name', function ($data) {
                return $data->role ? $data->role->name : '-';
            })
            ->addColumn('unit_name', function ($data) {
                return $data->unit ? $data->unit->name : '-';
            })
            ->addColumn('area_name', function ($data) {
                return $data->area ? $data->area->name : '-';
            })
            ->addColumn('vendor_name', function ($data) {
                return $data->vendor ? $data->vendor->name : '-';
            })
            ->addColumn('manage_units', function ($data) {
                if ($data->manage_unit_ids) {
                    $unitIds = is_string($data->manage_unit_ids) ? json_decode($data->manage_unit_ids) : $data->manage_unit_ids;
                    $units = Unit::whereIn('id', $unitIds)->pluck('name');
                    return $units->implode(', ');
                }
                return '-';
            })
            ->addColumn('DT_RowId', function ($data) {
                return 'row_' . $data->id;
            })
            ->addIndexColumn()
            ->toJson();
    }

    public function postStore(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id',
        ];

        // Dynamic validation based on role_id
        switch ($request->role_id) {
            case 1: // HTD
            case 6: // HoE
                $rules['unit_id'] = 'required|exists:units,id';
                break;
            case 2: // Vendor
                $rules['vendor_id'] = 'required|exists:vendors,id';
                break;
            case 3: // PIC Area
            case 4: // SRM
                $rules['area_id'] = 'required|exists:areas,id';
                break;
            case 7: // Super Admin
                $rules['manage_unit_ids'] = 'required|array|min:1';
                $rules['manage_unit_ids.*'] = 'exists:units,id';
                break;
            case 8: 
                $rules['manage_unit_ids'] = 'required|array|min:1';
                $rules['manage_unit_ids.*'] = 'exists:units,id';
                break;
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ];

        // Set specific fields based on role
        switch ($request->role_id) {
            case 1:
            case 6:
                $userData['unit_id'] = $request->unit_id;
                break;
            case 2:
                $userData['vendor_id'] = $request->vendor_id;
                break;
            case 3:
            case 4:
                $userData['area_id'] = $request->area_id;
                break;
            case 7:
                $userData['manage_unit_ids'] = json_encode($request->manage_unit_ids);
                break;
            case 8:
                $userData['manage_unit_ids'] = json_encode($request->manage_unit_ids);
                break;
        }

        $data = User::create($userData);

        return response()->json([
            'status' => 200,
            'message' => 'Data berhasil ditambahkan',
            'data' => $data
        ]);
    }

    public function getShow($id)
    {
        $data = User::with(['role', 'unit', 'area', 'vendor'])->findOrFail($id);
        
        // Decode manage_unit_ids if it exists
        if ($data->manage_unit_ids) {
            $data->manage_unit_ids = is_string($data->manage_unit_ids) ? json_decode($data->manage_unit_ids) : $data->manage_unit_ids;
        }
        
        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    }

    public function postUpdate(Request $request, $id)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role_id' => 'required|exists:roles,id',
        ];

        // Add password validation only if provided
        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:6';
        }

        // Dynamic validation based on role_id
        switch ($request->role_id) {
            case 1:
            case 6:
                $rules['unit_id'] = 'required|exists:units,id';
                break;
            case 2:
                $rules['vendor_id'] = 'required|exists:vendors,id';
                break;
            case 3:
            case 4:
                $rules['area_id'] = 'required|exists:areas,id';
                break;
            case 7:
                $rules['manage_unit_ids'] = 'required|array|min:1';
                $rules['manage_unit_ids.*'] = 'exists:units,id';
                break;
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = User::findOrFail($id);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            // Clear all role-specific fields first
            'unit_id' => null,
            'vendor_id' => null,
            'area_id' => null,
            'manage_unit_ids' => null,
        ];

        // Update password only if provided
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        // Set specific fields based on role
        switch ($request->role_id) {
            case 1:
            case 6:
                $userData['unit_id'] = $request->unit_id;
                break;
            case 2:
                $userData['vendor_id'] = $request->vendor_id;
                break;
            case 3:
            case 4:
                $userData['area_id'] = $request->area_id;
                break;
            case 7:
                $userData['manage_unit_ids'] = json_encode($request->manage_unit_ids);
                break;
        }

        $data->update($userData);

        return response()->json([
            'status' => 200,
            'message' => 'Data berhasil diupdate',
            'data' => $data
        ]);
    }

    public function deleteDestroy($id)
    {
        $data = User::findOrFail($id);
        $data->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Data berhasil dihapus'
        ]);
    }
}