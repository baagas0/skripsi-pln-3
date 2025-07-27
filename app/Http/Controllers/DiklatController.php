<?php

namespace App\Http\Controllers;

use App\Exports\DiklatExport;
use App\Models\Area;
use Str;
use App\Models\Diklat;
use App\Models\DiklatParticipant;
use App\Models\DiklatPlanning;
use App\Models\Employee;
use App\Models\Unit;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class DiklatController extends Controller
{
    public function getIndex()
    {
        $roleId = Auth::user()->role_id;
        $unitId = Auth::user()->unit_id;

        // Filter units berdasarkan role
        $units = Unit::when(in_array($roleId, [1]), function ($query) use ($unitId) {
            // HTD hanya melihat unit mereka sendiri
            return $query->where('id', $unitId);
        })
            ->when($roleId == 2, function ($query) {
                // Vendor melihat semua unit
                return $query;
            })
            ->when(in_array($roleId, [3, 4]), function ($query) {
                // PIC Area dan SRM melihat unit dari area mereka
                $area = Auth::user()->area;
                return $query->where('id', $area->unit_id);
            })
            ->when($roleId == 7, function ($query) {
                // HTD melihat unit yang mereka kelola
                $unitIdsString = Auth::user()->manage_unit_ids;
                $unitIds = is_string($unitIdsString) ? json_decode($unitIdsString) : $unitIdsString;
                return $query->whereIn('id', $unitIds ?? []);
            })
            ->when($roleId == 8, function ($query) {
                // Vice President melihat unit yang mereka kelola
                $unitIdsString = Auth::user()->manage_unit_ids;
                $unitIds = is_string($unitIdsString) ? json_decode($unitIdsString) : $unitIdsString;
                return $query->whereIn('id', $unitIds ?? []);
            })
            ->get();

        // Get areas based on role
        $areas = Area::when(in_array($roleId, [3, 4]), function ($query) {
            // PIC Area dan SRM hanya melihat area mereka
            return $query->where('id', Auth::user()->area_id);
        })
        ->when($roleId == 7, function ($query) {
            // HTD melihat area yang mereka kelola
            $unitIdsString = Auth::user()->manage_unit_ids;
            $unitIds = is_string($unitIdsString) ? json_decode($unitIdsString) : $unitIdsString;
            return $query->whereIn('unit_id', $unitIds ?? []);
            
        })
        ->when($roleId == 8, function ($query) {
            // Vice President melihat area yang mereka kelola
            $unitIdsString = Auth::user()->manage_unit_ids;
            $unitIds = is_string($unitIdsString) ? json_decode($unitIdsString) : $unitIdsString;
            return $query->whereIn('unit_id', $unitIds ?? []);
            
        })
        ->get();

        $vendors = Vendor::all();
        $years = Diklat::select('year')
        ->when($roleId == 7, function ($query) {
            // HTD melihat tahun dari unit yang mereka kelola
            $unitIdsString = Auth::user()->manage_unit_ids;
            $unitIds = is_string($unitIdsString) ? json_decode($unitIdsString) : $unitIdsString;
            return $query->whereIn('unit_id', $unitIds ?? []);
        })
        ->when($roleId == 8, function ($query) {
            // Vice President melihat tahun dari unit yang mereka kelola
            $unitIdsString = Auth::user()->manage_unit_ids;
            $unitIds = is_string($unitIdsString) ? json_decode($unitIdsString) : $unitIdsString;
            return $query->whereIn('unit_id', $unitIds ?? []);
        })
        ->distinct()->get();
        return view('diklat.index', compact('units', 'vendors', 'years', 'areas'));
    }

    public function getData(Request $request)
    {
        $roleId = Auth::user()->role_id;
        $areaId = Auth::user()->area_id;

        $query = Diklat::with(['unit', 'vendor', 'areas']);

        // Filter data berdasarkan role
        $query->when(in_array($roleId, [1]), function ($query) {
            // HTD hanya melihat data unit mereka
            return $query->where('unit_id', Auth::user()->unit_id);
        })
            ->when($roleId == 2, function ($query) {
                // Vendor melihat data mereka sendiri
                return $query->where('vendor_id', Auth::user()->vendor_id);
            })
            ->when(in_array($roleId, [3, 4]), function ($query) use ($areaId) {
                // PIC Area dan SRM melihat data sesuai area mereka
                return $query->whereHas('areas', function ($q) use ($areaId) {
                    $q->where('areas.id', $areaId);
                });
            })
            ->when($roleId == 7, function ($query) {
                // HTD melihat data dari unit yang mereka kelola
                $unitIdsString = Auth::user()->manage_unit_ids;
                $unitIds = is_string($unitIdsString) ? json_decode($unitIdsString) : $unitIdsString;
                return $query->whereIn('unit_id', $unitIds ?? []);
            })
            ->when($roleId == 8, function ($query) {
                // Vice President melihat data dari unit yang mereka kelola
                $unitIdsString = Auth::user()->manage_unit_ids;
                $unitIds = is_string($unitIdsString) ? json_decode($unitIdsString) : $unitIdsString;
                return $query->whereIn('unit_id', $unitIds ?? []);
            });

        if ($request->has('year')) {
            $query->where('year', $request->year);
        }

        return datatables($query)
            ->filter(function ($query) use ($request) {
                if ($request->search['value']) {
                    $query->where(function ($q) use ($request) {
                        $q->where('name', 'like', "%{$request->search['value']}%")
                            ->orWhereHas('vendor', function ($q) use ($request) {
                                $q->where('name', 'like', "%{$request->search['value']}%");
                            })
                            ->orWhereHas('unit', function ($q) use ($request) {
                                $q->where('name', 'like', "%{$request->search['value']}%");
                            });
                    });
                }
            })
            ->toJson();
    }

    public function postStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'diklat_planning_id' => 'required|exists:diklat_plannings,id',
            'area_ids' => 'required|array',
            'area_ids.*' => 'exists:areas,id',
            'diklat_type' => 'required|in:Pelatihan,Pelatihan & Sertifikasi',
            'status_monitoring' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $planning = DiklatPlanning::findOrFail($request->diklat_planning_id);

        $diklat = Diklat::create([
            'diklat_planning_id' => $request->diklat_planning_id,
            'name' => $planning->name,
            'letter_number' => $request->letter_number,
            'year' => $planning->year,
            'estimate_start_date' => $planning->estimate_start_date,
            'estimate_end_date' => $planning->estimate_end_date,
            'vendor_id' => $planning->vendor_id,
            'count_of_participant' => $planning->count_of_participant,
            'unit_id' => $planning->unit_id,
            'total_cost' => $planning->total_cost,
            'diklat_type' => $request->diklat_type,
            'status_monitoring' => $request->status_monitoring,
            'slug' => Diklat::generateSlug($planning->name),
        ]);

        // Attach multiple areas to the diklat
        $diklat->areas()->attach($request->area_ids);

        $employee = Employee::where('unit_id', $request->unit_id)->whereIn('area_id', $request->area_ids)->get();
        $participants = [];
        foreach ($employee as $emp) {
            $participants[] = [
                'diklat_id' => $diklat->id,
                'employee_id' => $emp->id,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        if (count($participants) > 0) {
            $diklat->participants()->insert($participants);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Data berhasil ditambahkan',
            'data' => $diklat
        ]);
    }
    // public function postStore(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required',
    //         'letter_number' => 'required',
    //         'year' => [
    //             'required',
    //             'numeric',
    //             'digits:4',                    // Must be exactly 4 digits
    //             'min:' . (date('Y') - 1),         // Can't be less than last year
    //             'max:' . (date('Y') + 5),         // Can't be more than 5 years in future
    //             function ($attribute, $value, $fail) {
    //                 if ($value < 2000) {       // Additional custom validation
    //                     $fail('The year must be after 2000.');
    //                 }
    //             },
    //         ],
    //         'estimate_start_date' => 'required|date',
    //         'estimate_end_date' => 'required|date|after:estimate_start_date',
    //         'vendor_id' => 'required|exists:vendors,id',
    //         'count_of_participant' => 'required|numeric|min:1',
    //         'unit_id' => 'required|exists:units,id',
    //         'status_monitoring' => 'required',
    //         'total_cost' => 'required|numeric|min:0'
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     $data = $request->all();
    //     $data['slug'] = Diklat::generateSlug($request->name);

    //     $diklat = Diklat::create($data);

    //     // craete bulk diklat_participants by unit
    //     $employee = Employee::where('unit_id', $request->unit_id)->get();
    //     $participants = [];
    //     foreach ($employee as $emp) {
    //         $participants[] = [
    //             'diklat_id' => $diklat->id,
    //             'employee_id' => $emp->id,
    //             'created_at' => now(),
    //             'updated_at' => now()
    //         ];
    //     }
    //     if (count($participants) > 0) {
    //         $diklat->participants()->insert($participants);
    //     }

    //     return response()->json([
    //         'status' => 200,
    //         'message' => 'Data berhasil ditambahkan',
    //         'data' => $diklat
    //     ]);
    // }

    public function getShow($id)
    {
        $diklat = Diklat::with(['unit', 'vendor', 'areas'])->findOrFail($id);
        return response()->json([
            'status' => 200,
            'data' => $diklat
        ]);
    }

    public function postUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'letter_number' => 'required',
            'year' => [
                'required',
                'numeric',
                'digits:4',                    // Must be exactly 4 digits
                'min:' . (date('Y') - 1),         // Can't be less than last year
                'max:' . (date('Y') + 5),         // Can't be more than 5 years in future
                function ($attribute, $value, $fail) {
                    if ($value < 2000) {       // Additional custom validation
                        $fail('The year must be after 2000.');
                    }
                },
            ],
            'estimate_start_date' => 'required|date',
            'estimate_end_date' => 'required|date|after:estimate_start_date',
            'vendor_id' => 'required|exists:vendors,id',
            'count_of_participant' => 'required|numeric|min:1',
            'unit_id' => 'required|exists:units,id',
            'status_monitoring' => 'required',
            'total_cost' => 'required|numeric|min:0',
            'area_ids' => 'required|array',
            'area_ids.*' => 'exists:areas,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $diklat = Diklat::findOrFail($id);

        // Remove area_ids from request data
        $requestData = $request->except('area_ids');
        $diklat->update($requestData);

        // REMOVE PENILAIAN
        DiklatParticipant::where('diklat_id', $id)->delete();

        // INSERT NEW EMPLOYEE
        $employee = Employee::where('unit_id', $request->unit_id)->whereIn('area_id', $request->area_ids)->get();
        $participants = [];
        foreach ($employee as $emp) {
            $participants[] = [
                'diklat_id' => $diklat->id,
                'employee_id' => $emp->id,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        if (count($participants) > 0) {
            $diklat->participants()->insert($participants);
        }

        // Sync the areas
        $diklat->areas()->sync($request->area_ids);

        return response()->json([
            'status' => 200,
            'message' => 'Data berhasil diupdate',
            'data' => $diklat
        ]);
    }

    public function deleteDestroy($id)
    {
        $diklat = Diklat::findOrFail($id);
        $diklat->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Data berhasil dihapus'
        ]);
    }

    public function postBulkDestroy(Request $request)
    {
        Diklat::whereIn('id', $request->id)->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Data berhasil dihapus'
        ]);
    }

    public function getExport(Request $request)
    {
        $year = $request->input('year');
        return Excel::download(new DiklatExport($year), 'Realisasi Diklat ' . ($year && $year !== 'all' ? $year : '') . '.xlsx');
    }
}
