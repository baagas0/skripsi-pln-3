<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Diklat;
use App\Models\ScoringLv4;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScoringLv4Controller extends Controller
{
    public function getIndex($id) {
        $diklat = Diklat::findOrFail($id);
        $areas = Area::where('unit_id', $diklat->unit_id)->whereIn('id', $diklat->areas->pluck('id')->toArray())->get();

        $scoreLv4 = null;
        $hash_slug = encrypt_custom($diklat->slug);
        $urlLv4 = route('form.lv4.form', $hash_slug);
        $areaId = request('area_id');
        $participants = collect();
        $selectedEmployees = [];
        
        if ($areaId) {
            $scoreLv4 = ScoringLv4::where('diklat_id', $id)
                ->where('area_id', $areaId)
                ->first();
            $tangibles = $diklat->tangibles()->where('area_id', $areaId)->get();
            
            // Get participants from this training in this area
            $participants = $diklat->participants()
                ->whereHas('employee', function($query) use ($areaId) {
                    $query->where('area_id', $areaId);
                })
                ->get();
            
            // Get selected employees if any
            if ($scoreLv4 && !empty($scoreLv4->employee_ids)) {
                $selectedEmployees = $scoreLv4->employee_ids;
            }
        } else {
            $tangibles = collect();
        }

        return view('scoring.form_lv4', compact('diklat', 'tangibles', 'areas', 'scoreLv4', 'urlLv4', 'hash_slug', 'areaId', 'participants', 'selectedEmployees'));
    }

    public function getForm($hash_slug) {
        $slug = decrypt_custom($hash_slug);
        $diklat = Diklat::where('slug', $slug)->first();
        $areas = Area::where('unit_id', $diklat->unit_id)->whereIn('id', $diklat->areas->pluck('id')->toArray())->get();
        $urlLv4 = route('form.lv4.form', $hash_slug);

        $areaId = request('area_id');
        $participants = collect();
        $scoreLv4 = null;
        $selectedEmployees = [];
        
        if ($areaId) {
            $scoreLv4 = ScoringLv4::where('diklat_id', $diklat->id)
                ->where('area_id', $areaId)
                ->first();
                
            // Get participants from this training in this area
            $participants = $diklat->participants()
                ->whereHas('employee', function($query) use ($areaId) {
                    $query->where('area_id', $areaId);
                })
                ->get();
                
            // Get selected employees if any
            if ($scoreLv4 && !empty($scoreLv4->employee_ids)) {
                $selectedEmployees = $scoreLv4->employee_ids;
            }
            
            $tangibles = $diklat->tangibles()->where('area_id', $areaId)->get();
        } else {
            $tangibles = collect();
        }

        return view('scoring.form_lv4_head_area', compact('diklat', 'areas', 'hash_slug', 'urlLv4', 'tangibles', 'participants', 'selectedEmployees', 'areaId', 'scoreLv4'));
    }

    public function postStore($id, Request $request) {
        // Log the full request for debugging
        \Log::info('ScoringLv4 request data:', [
            'all' => $request->all(),
            'has_tangible' => $request->has('kt_docs_repeater_basic'),
            'tangible' => $request->kt_docs_repeater_basic,
            'impacts' => $request->impacts,
            'request_content' => file_get_contents('php://input')
        ]);
        
        // Pre-process cost values to clean currency formatting
        if ($request->has('kt_docs_repeater_basic')) {
            foreach ($request->kt_docs_repeater_basic as $key => $item) {
                if (isset($item['cost'])) {
                    // Convert from "Rp. 999.999.999,99" format to a clean number
                    $cost = $item['cost'];
                    $cost = preg_replace('/[^\d,]/', '', $cost); // Remove all except digits and comma
                    $cost = str_replace(',', '.', $cost); // Replace comma with dot for decimal
                    $request->merge([
                        'kt_docs_repeater_basic' => array_replace(
                            $request->kt_docs_repeater_basic,
                            [$key => array_replace($item, ['cost' => $cost])]
                        )
                    ]);
                }
            }
        }
        
        $validator = Validator::make($request->all(), [
            'area_id' => 'required|exists:areas,id',
            'score_positive' => 'required|numeric|min:0',
            'impacts' => 'required|array',
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,id',

            'kt_docs_repeater_basic.*' => 'required|array',
            'kt_docs_repeater_basic.*.category' => 'required',
            'kt_docs_repeater_basic.*.cost' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $diklat = Diklat::find($id);
        if (!$diklat) {
            return response()->json(['error' => 'Diklat not found'], 404);
        }
        
        // Check if this assessment has already been submitted
        $existingScore = ScoringLv4::where('diklat_id', $diklat->id)
            ->where('area_id', $request->area_id)
            ->first();
            
        // if ($existingScore) {
        //     return response()->json([
        //         'status' => 403,
        //         'error' => 'Penilaian untuk bidang ini sudah disubmit sebelumnya'
        //     ], 403);
        // }

        ScoringLv4::updateOrCreate(
            ['diklat_id' => $diklat->id, 'area_id' => $request->area_id],
            [
                'score_positive' => $request->score_positive,
                'impacts' => $request->impacts,
                'employee_ids' => $request->employee_ids ?? [],
                'updated_at' => now(),
            ]
        );

        // Log the employee IDs that were saved
        \Log::info('Employee IDs saved for Diklat: ' . $diklat->id . ', Area: ' . $request->area_id, [
            'employee_ids' => $request->employee_ids ?? []
        ]);

        $tangible = $request->kt_docs_repeater_basic;
        
        // Debug log the tangible data that's coming in
        \Log::info('Tangible data for processing:', ['tangible_data' => $tangible]);
        
        if ($tangible) {
            $tangible = array_map(function($item) use ($diklat, $request) {
                // Handle "Lainnya" category with special formatting
                $category = $item['category'];
                
                // Log each item's category for debugging
                \Log::info('Processing tangible item category:', [
                    'category' => $category,
                    'is_lainnya' => strpos($category, 'Lainnya:') === 0,
                    'raw_item' => $item
                ]);
                
                // Clean up and format the cost value
                $cost = $item['cost'];
                if (is_string($cost)) {
                    // Remove any currency formatting (Rp., thousand separators, etc.)
                    $cost = preg_replace('/[^\d,.]/', '', $cost);
                    // Replace comma with dot for decimal
                    $cost = str_replace(',', '.', $cost);
                }
                
                return [
                    'category' => $category,
                    'cost' => $cost,
                    'diklat_id' => $diklat->id,
                    'area_id' => $request->area_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $tangible);
            
            // Log the processed tangible data
            \Log::info('Processed tangible data:', ['processed_data' => $tangible]);
            
            $diklat->tangibles()->where('area_id', $request->area_id)->delete();
            $diklat->tangibles()->insert($tangible);
        }

        return response()->json([
            'status' => 200,
            'success' => 'Scoring data saved successfully'
        ]);
    }

    public function postStoreTangible($id, Request $request) {
        // Log the received tangible data for debugging
        \Log::info('ScoringLv4 Tangible request data:', [
            'all' => $request->all(),
            'has_tangible' => $request->has('kt_docs_repeater_basic'),
            'tangible' => $request->kt_docs_repeater_basic
        ]);
        
        // Pre-process cost values to clean currency formatting
        if ($request->has('kt_docs_repeater_basic')) {
            foreach ($request->kt_docs_repeater_basic as $key => $item) {
                if (isset($item['cost'])) {
                    // Convert from "Rp. 999.999.999,99" format to a clean number
                    $cost = $item['cost'];
                    $cost = preg_replace('/[^\d,]/', '', $cost); // Remove all except digits and comma
                    $cost = str_replace(',', '.', $cost); // Replace comma with dot for decimal
                    $request->merge([
                        'kt_docs_repeater_basic' => array_replace(
                            $request->kt_docs_repeater_basic,
                            [$key => array_replace($item, ['cost' => $cost])]
                        )
                    ]);
                }
            }
        }
        
        $validator = Validator::make($request->all(), [
            'kt_docs_repeater_basic.*' => 'required|array',
            'kt_docs_repeater_basic.*.category' => 'required',
            'kt_docs_repeater_basic.*.cost' => 'required|numeric|min:0',
            'area_id' => 'required|exists:areas,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $diklat = Diklat::find($id);
        if (!$diklat) {
            return response()->json(['error' => 'Diklat not found2'], 404);
        }
        $tangible = $request->kt_docs_repeater_basic;
        $tangible = array_map(function($item) use ($diklat, $request) {
            return [
                'category' => $item['category'],
                'cost' => $item['cost'],
                'diklat_id' => $diklat->id,
                'area_id' => $request->area_id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $tangible);
        $diklat->tangibles()->where('area_id', $request->area_id)->delete();
        $diklat->tangibles()->insert($tangible);
        return response()->json([
            'status' => 200,
            'success' => 'Tangible data saved successfully'
        ]);
    }
}
