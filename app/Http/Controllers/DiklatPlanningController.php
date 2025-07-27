<?php

namespace App\Http\Controllers;

use App\Exports\DiklatPlanningExport;
use App\Models\Area;
use App\Models\DiklatPlanning;
use App\Models\Unit;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class DiklatPlanningController extends Controller
{
    public function getIndex()
    {
        $roleId = Auth::user()->role_id;
        $unitId = Auth::user()->unit_id;
        $areaId = Auth::user()->area_id;

        // Filter units yang ditampilkan berdasarkan role
        $units = Unit::when(in_array($roleId, [1]), function ($query) use ($unitId) {
            // HTD hanya melihat unit mereka sendiri
            return $query->where('id', $unitId);
        })
            ->when($roleId == 2, function ($query) {
                // Vendor melihat semua unit
                return $query;
            })->get();

        // Filter years berdasarkan role
        $years = DiklatPlanning::select('year')
            ->when(in_array($roleId, [1]), function ($query) use ($unitId) {
                // HTD hanya melihat data unit mereka
                return $query->where('unit_id', $unitId);
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
            ->distinct()->get();

        $picUnitId = Auth::user()->area->unit_id ?? null;
        $areas = Area::where('unit_id', $picUnitId)->get();

        $vendors = Vendor::get();

        return view('diklat_planning.index', compact('units', 'vendors', 'years', 'areas'));
    }

    public function getData(Request $request)
    {
        $roleId = Auth::user()->role_id;
        $unitId = Auth::user()->unit_id;
        $areaId = Auth::user()->area_id;

        $query = DiklatPlanning::with(['unit', 'vendor', 'areas']);

        if ($request->has('year')) {
            $query->where('year', $request->year);
        }

        // Filter data berdasarkan role
        $query->when(in_array($roleId, [1]), function ($query) use ($unitId) {
            // HTD hanya melihat data unit mereka
            return $query->where('unit_id', $unitId);
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
            });

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
            'name' => 'required',
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
            'total_cost' => 'required|numeric|min:0',
            'diklat_type' => 'required|in:Pelatihan,Pelatihan & Sertifikasi',
            'area_ids' => 'required|array',
            'area_ids.*' => 'exists:areas,id',
            'tangible_benefit_categories' => 'nullable|array',
            'tangible_benefit_categories.*' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();
        if ($request->has('tangible_benefit_categories')) {
            $data['tangible_benefit_categories'] = json_encode($request->tangible_benefit_categories);
        }
        $diklatPlanning = DiklatPlanning::create($data);
        $diklatPlanning->areas()->attach($request->area_ids);

        return response()->json([
            'status' => 200,
            'message' => 'Data berhasil ditambahkan',
            'data' => $diklatPlanning
        ]);
    }

    public function getShow($id)
    {
        $diklatPlanning = DiklatPlanning::with(['unit', 'vendor', 'areas'])->findOrFail($id);
        // Decode tangible_benefit_categories jika ada
        if (!empty($diklatPlanning->tangible_benefit_categories)) {
            $diklatPlanning->tangible_benefit_categories = json_decode($diklatPlanning->tangible_benefit_categories);
        } else {
            $diklatPlanning->tangible_benefit_categories = [];
        }
        return response()->json([
            'status' => 200,
            'data' => $diklatPlanning
        ]);
    }

    public function postUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
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
            'total_cost' => 'required|numeric|min:0',
            'diklat_type' => 'required|in:Pelatihan,Pelatihan & Sertifikasi',
            'area_ids' => 'required|array',
            'area_ids.*' => 'exists:areas,id',
            'tangible_benefit_categories' => 'nullable|array',
            'tangible_benefit_categories.*' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $diklatPlanning = DiklatPlanning::findOrFail($id);
        $data = $request->all();
        if ($request->has('tangible_benefit_categories')) {
            $data['tangible_benefit_categories'] = json_encode($request->tangible_benefit_categories);
        }
        $diklatPlanning->update($data);
        $diklatPlanning->areas()->sync($request->area_ids);

        return response()->json([
            'status' => 200,
            'message' => 'Data berhasil diupdate',
            'data' => $diklatPlanning
        ]);
    }

    public function deleteDestroy($id)
    {
        $diklatPlanning = DiklatPlanning::findOrFail($id);
        $diklatPlanning->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Data berhasil dihapus'
        ]);
    }

    public function getApproved()
    {
        $approvedPlans = DiklatPlanning::where('approve_by_htd', 2)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(value: 1))
                    ->from('diklats')
                    ->whereRaw('diklats.diklat_planning_id = diklat_plannings.id');
            })
            ->with(['unit', 'vendor'])
            ->get();

        return response()->json([
            'status' => 200,
            'data' => $approvedPlans
        ]);
    }

    public function postBulkDestroy(Request $request)
    {
        DiklatPlanning::whereIn('id', $request->id)->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Data berhasil dihapus'
        ]);
    }

    public function postApprove(Request $request, $id)
    {
        $diklatPlanning = DiklatPlanning::findOrFail($id);
        $diklatPlanning->approve_by_htd = 1;
        $diklatPlanning->save();

        return response()->json([
            'status' => 200,
            'message' => 'Data berhasil disetujui'
        ]);
    }


    public function postApproveHtd(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'notes' => 'required|string|min:10'
        ], [
            'notes.required' => 'Approval notes are required',
            'notes.min' => 'Notes must be at least 10 characters'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $diklatPlanning = DiklatPlanning::findOrFail($id);
            $diklatPlanning->approve_by_htd = 2;
            $diklatPlanning->locked_at = now();
            $diklatPlanning->notes = $request->notes;
            $diklatPlanning->save();

            return response()->json([
                'status' => 200,
                'message' => 'Diklat planning approved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while approving the diklat planning'
            ], 500);
        }
    }

    public function postLock(Request $request, $id)
    {
        $diklatPlanning = DiklatPlanning::findOrFail($id);
        $diklatPlanning->locked_at = now();
        $diklatPlanning->save();

        return response()->json([
            'status' => 200,
            'message' => 'Data berhasil dikunci'
        ]);
    }

    public function postUnlock(Request $request, $id)
    {
        $diklatPlanning = DiklatPlanning::findOrFail($id);
        $diklatPlanning->locked_at = null;
        $diklatPlanning->save();

        return response()->json([
            'status' => 200,
            'message' => 'Data berhasil dibuka'
        ]);
    }

    public function getExport(Request $request)
    {
        $year = $request->input('year');
        return Excel::download(new DiklatPlanningExport($year), 'Perencanaan Diklat ' . ($year && $year !== 'all' ? $year : '') . '.xlsx');
    }
}
