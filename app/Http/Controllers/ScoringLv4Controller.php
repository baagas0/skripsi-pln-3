<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Diklat;
use App\Models\ScoringLv4;
use App\Models\ScoringLv4_tangible;
use App\Models\ScoringLv4TangibleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
                
            // Get tangibles with their details
            $tangibles = $diklat->tangibles()->where('area_id', $areaId)->get();
            
            // Load the details for each tangible benefit
            foreach ($tangibles as $tangible) {
                // Load the relationship
                $tangible->load('details');
                
                // Group details by component name for easier handling in the template
                $tangible->componentGroups = $tangible->details->groupBy('component_name');
                
                // Calculate subtotals for each component
                foreach ($tangible->componentGroups as $componentName => $details) {
                    $mainPrice = 0;
                    $subtotal = 0;
                    $firstDetail = $details->first();
                    
                    if ($firstDetail) {
                        $mainPrice = $firstDetail->price;
                        $subtotal = $mainPrice;
                    }
                    
                    foreach ($details as $index => $detail) {
                        if ($index > 0) { // Skip the first one as it's the base
                            switch ($detail->operator) {
                                case '*':
                                    $subtotal *= $detail->price;
                                    break;
                                case '+':
                                    $subtotal += $detail->price;
                                    break;
                                case '-':
                                    $subtotal -= $detail->price;
                                    break;                            case '/':
                                if ($detail->price != 0) {
                                    $subtotal /= $detail->price;
                                } else {
                                    // Log the division by zero attempt
                                    Log::warning('Division by zero attempted in tangible benefit calculation', [
                                        'component_name' => $componentName,
                                        'detail_id' => $detail->id
                                    ]);
                                    // Keep the current subtotal as is
                                }
                                break;
                            }
                        }
                    }
                    
                    // Store the subtotal with the component group
                    $tangible->componentGroups[$componentName]->subtotal = $subtotal;
                }
            }
            
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
            
            // Get tangibles with their details
            $tangibles = $diklat->tangibles()->where('area_id', $areaId)->get();
            
            // Load the details for each tangible benefit
            foreach ($tangibles as $tangible) {
                // Load the relationship
                $tangible->load('details');
                
                // Group details by component name for easier handling in the template
                $tangible->componentGroups = $tangible->details->groupBy('component_name');
                
                // Calculate subtotals for each component
                foreach ($tangible->componentGroups as $componentName => $details) {
                    $mainPrice = 0;
                    $subtotal = 0;
                    $firstDetail = $details->first();
                    
                    if ($firstDetail) {
                        $mainPrice = $firstDetail->price;
                        $subtotal = $mainPrice;
                    }
                    
                    foreach ($details as $index => $detail) {
                        if ($index > 0) { // Skip the first one as it's the base
                            switch ($detail->operator) {
                                case '*':
                                    $subtotal *= $detail->price;
                                    break;
                                case '+':
                                    $subtotal += $detail->price;
                                    break;
                                case '-':
                                    $subtotal -= $detail->price;
                                    break;
                                case '/':
                                    if ($detail->price != 0) {
                                        $subtotal /= $detail->price;
                                    } else {
                                        // Log the division by zero attempt
                                        Log::warning('Division by zero attempted in tangible benefit calculation', [
                                            'component_name' => $componentName,
                                            'detail_id' => $detail->id
                                        ]);
                                        // Keep the current subtotal as is
                                    }
                                    break;
                            }
                        }
                    }
                    
                    // Store the subtotal with the component group
                    $tangible->componentGroups[$componentName]->subtotal = $subtotal;
                }
            }
        } else {
            $tangibles = collect();
        }

        return view('scoring.form_lv4_head_area', compact('diklat', 'areas', 'hash_slug', 'urlLv4', 'tangibles', 'participants', 'selectedEmployees', 'areaId', 'scoreLv4'));
    }

    public function postStore($id, Request $request) {
        // Log the full request for debugging
        Log::info('ScoringLv4 request data:', [
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
                    // Convert from various currency formats to a clean number
                    $cost = $item['cost'];
                    if (is_string($cost)) {
                        $cost = (float)$cost; // Save as plain decimal
                    }
                    $request->merge([
                        'kt_docs_repeater_basic' => array_replace(
                            $request->kt_docs_repeater_basic,
                            [$key => array_replace($item, ['cost' => $cost])]
                        )
                    ]);
                }
                
                // Also process component prices if they exist
                if (isset($item['components'])) {
                    $components = $item['components'];
                    if (is_string($components)) {
                        $components = json_decode($components, true);
                    }
                    
                    if (is_array($components)) {
                        foreach ($components as $compIndex => $component) {
                            // Clean main component price
                            if (isset($component['price'])) {
                                $price = $component['price'];
                                if (is_string($price)) {
                                    $price = (float)$price; // Save as plain decimal
                                    $components[$compIndex]['price'] = $price;
                                }
                            }
                            
                            // Clean sub-component prices
                            if (isset($component['sub_items']) && is_array($component['sub_items'])) {
                                foreach ($component['sub_items'] as $subIndex => $subItem) {
                                    if (isset($subItem['price'])) {
                                        $price = $subItem['price'];
                                        if (is_string($price)) {
                                            $price = (float)$price; // Save as plain decimal
                                            $components[$compIndex]['sub_items'][$subIndex]['price'] = $price;
                                        }
                                    }
                                }
                            }
                        }
                        
                        // Update the components back to the request
                        $updatedItem = array_replace($item, ['components' => json_encode($components)]);
                        $request->merge([
                            'kt_docs_repeater_basic' => array_replace(
                                $request->kt_docs_repeater_basic,
                                [$key => $updatedItem]
                            )
                        ]);
                    }
                }
            }
        }
          // Debug log the raw request data before validation
        Log::info('ScoringLv4 Raw Request Debug:', [
            'method' => $request->method(),
            'url' => $request->url(),
            'area_id' => $request->input('area_id'),
            'score_positive' => $request->input('score_positive'),
            'impacts' => $request->input('impacts'),
            'employee_ids' => $request->input('employee_ids'),
            'has_score_positive' => $request->has('score_positive'),
            'has_impacts' => $request->has('impacts'),
            'has_employee_ids' => $request->has('employee_ids'),
            'all_keys' => array_keys($request->all())
        ]);
          $validator = Validator::make($request->all(), [
            'area_id' => 'required|exists:areas,id',
            'score_positive' => 'required|numeric|min:0',
            'impacts' => 'required|array',
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'integer|exists:employees,id',

            'kt_docs_repeater_basic.*' => 'required|array',
            'kt_docs_repeater_basic.*.category' => 'required',
            'kt_docs_repeater_basic.*.cost' => 'required|numeric',
            'kt_docs_repeater_basic.*.components' => 'sometimes',  // Can be array or JSON string
        ]);
        
        // Custom validation for components
        if ($validator->passes() && $request->has('kt_docs_repeater_basic')) {
            foreach ($request->kt_docs_repeater_basic as $index => $item) {
                if (isset($item['components'])) {
                    $components = null;
                    
                    // Parse components if they're in JSON string format
                    if (is_string($item['components'])) {
                        try {
                            $components = json_decode($item['components'], true);
                            if (json_last_error() !== JSON_ERROR_NONE) {
                                $validator->errors()->add("kt_docs_repeater_basic.{$index}.components", 'Invalid JSON format for components');
                            }
                        } catch (\Exception $e) {
                            $validator->errors()->add("kt_docs_repeater_basic.{$index}.components", 'Error parsing components data');
                        }
                    } else if (is_array($item['components'])) {
                        $components = $item['components'];
                    }
                    
                    // Validate component structure if we have components
                    if ($components) {
                        foreach ($components as $cIndex => $component) {
                            if (empty($component['name'])) {
                                $validator->errors()->add("kt_docs_repeater_basic.{$index}.components.{$cIndex}.name", 'Component name is required');
                            }
                            
                            if (isset($component['price']) && !is_numeric(str_replace(',', '.', str_replace(['Rp', ' ', '.'], '', $component['price'])))) {
                                $validator->errors()->add("kt_docs_repeater_basic.{$index}.components.{$cIndex}.price", 'Price must be a number');
                            }
                            
                            if (isset($component['operator']) && !in_array($component['operator'], ['*', '+', '-', '/'])) {
                                $validator->errors()->add("kt_docs_repeater_basic.{$index}.components.{$cIndex}.operator", 'Invalid operator');
                            }
                            
                            // Validate sub-components if any
                            if (isset($component['sub_items']) && is_array($component['sub_items'])) {
                                foreach ($component['sub_items'] as $sIndex => $subItem) {
                                    if (isset($subItem['price']) && !is_numeric(str_replace(',', '.', str_replace(['Rp', ' ', '.'], '', $subItem['price'])))) {
                                        $validator->errors()->add("kt_docs_repeater_basic.{$index}.components.{$cIndex}.sub_items.{$sIndex}.price", 'Sub-component price must be a number');
                                    }
                                    
                                    if (isset($subItem['operator']) && !in_array($subItem['operator'], ['*', '+', '-', '/'])) {
                                        $validator->errors()->add("kt_docs_repeater_basic.{$index}.components.{$cIndex}.sub_items.{$sIndex}.operator", 'Invalid sub-component operator');
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        if ($validator->fails()) {
            // Debug log validation failures for score_positive, impacts, and employee_ids
            Log::error('ScoringLv4 Validation Failed:', [
                'errors' => $validator->errors()->toArray(),
                'request_data' => [
                    'area_id' => $request->input('area_id'),
                    'score_positive' => $request->input('score_positive'),
                    'impacts' => $request->input('impacts'),
                    'employee_ids' => $request->input('employee_ids'),
                    'has_score_positive' => $request->has('score_positive'),
                    'has_impacts' => $request->has('impacts'),
                    'has_employee_ids' => $request->has('employee_ids'),
                ]
            ]);
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
        // }        // Debug log the incoming request data for score_positive, impacts, and employee_ids
        Log::info('ScoringLv4 Request Data Debug:', [
            'diklat_id' => $diklat->id,
            'area_id' => $request->area_id,
            'score_positive' => $request->score_positive,
            'score_positive_type' => gettype($request->score_positive),
            'impacts' => $request->impacts,
            'impacts_type' => gettype($request->impacts),
            'employee_ids' => $request->employee_ids,
            'employee_ids_type' => gettype($request->employee_ids),
            'all_request_data' => $request->except(['kt_docs_repeater_basic']) // Exclude large component data for readability
        ]);

        $scoreLv4 = ScoringLv4::updateOrCreate(
            ['diklat_id' => $diklat->id, 'area_id' => $request->area_id],
            [
                'score_positive' => $request->score_positive,
                'impacts' => $request->impacts,
                'employee_ids' => $request->employee_ids ?? [],
                'updated_at' => now(),
            ]
        );

        // Log the data that was actually saved to verify it's being stored correctly
        Log::info('ScoringLv4 Data Saved Successfully:', [
            'id' => $scoreLv4->id,
            'diklat_id' => $scoreLv4->diklat_id,
            'area_id' => $scoreLv4->area_id,
            'score_positive' => $scoreLv4->score_positive,
            'impacts' => $scoreLv4->impacts,
            'employee_ids' => $scoreLv4->employee_ids,
            'created_at' => $scoreLv4->created_at,
            'updated_at' => $scoreLv4->updated_at,
        ]);
        
        $tangible = $request->kt_docs_repeater_basic;
        // dd($tangible);
        // Debug log the tangible data that's coming in
        Log::info('Tangible data for processing:', ['tangible_data' => $tangible]);
        
        if ($tangible) {
            // Delete existing tangible data for this area
            $diklat->tangibles()->where('area_id', $request->area_id)->delete();
            
            // Process each tangible item
            foreach ($tangible as $item) {
                // Handle "Lainnya" category with special formatting
                $category = $item['category'];
                
                // Log each item's category for debugging
                Log::info('Processing tangible item category:', [
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
                
                // Create the tangible record
                $tangibleRecord = $diklat->tangibles()->create([
                    'category' => $category,
                    'cost' => $cost,
                    'area_id' => $request->area_id,
                ]);
                
                // Process component details if they exist
                if (isset($item['components'])) {
                    $componentsData = $item['components'];
                    
                    // Parse JSON string if needed
                    if (is_string($componentsData)) {
                        $componentsData = json_decode($componentsData, true);
                    }
                    
                    // Log component data for debugging
                    Log::info('Component data for tangible', [
                        'raw_components' => $item['components'],
                        'parsed_components' => $componentsData
                    ]);
                    
                    // Process component details if they exist and are valid
                    if ($componentsData && is_array($componentsData)) {
                        foreach ($componentsData as $component) {
                            if (!empty($component['name'])) {
                                // Clean component price
                                $componentPrice = $component['price'] ?? 0;
                                if (is_string($componentPrice)) {
                                    $componentPrice = (float)$componentPrice; // Save as plain decimal
                                }
                                
                                // Create main component detail
                                $tangibleRecord->details()->create([
                                    'component_name' => $component['name'],
                                    'sub_component_name' => $component['sub_name'] ?? null,
                                    'price' => $componentPrice,
                                    'operator' => $component['operator'] ?? '*',
                                ]);
                                
                                // Create sub-component details if they exist
                                if (isset($component['sub_items']) && is_array($component['sub_items'])) {
                                    foreach ($component['sub_items'] as $subItem) {
                                        // Clean sub-component price
                                        $subPrice = $subItem['price'] ?? 0;
                                        if (is_string($subPrice)) {
                                            $subPrice = (float)$subPrice; // Save as plain decimal
                                        }
                                        
                                        $tangibleRecord->details()->create([
                                            'component_name' => $component['name'],
                                            'sub_component_name' => $subItem['sub_name'] ?? null,
                                            'price' => $subPrice,
                                            'operator' => $subItem['operator'] ?? '*',
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return response()->json([
            'status' => 200,
            'success' => 'Scoring data saved successfully'
        ]);
    }

    public function postStoreTangible($id, Request $request) {
        // Log the received tangible data for debugging
        Log::info('ScoringLv4 Tangible request data:', [
            'all' => $request->all(),
            'has_tangible' => $request->has('kt_docs_repeater_basic'),
            'tangible' => $request->kt_docs_repeater_basic
        ]);
        
        // Pre-process cost values to clean currency formatting
        if ($request->has('kt_docs_repeater_basic')) {
            foreach ($request->kt_docs_repeater_basic as $key => $item) {
                if (isset($item['cost'])) {
                    // Convert from various currency formats to a clean number
                    $cost = $item['cost'];
                    if (is_string($cost)) {
                        $cost = (float)$cost; // Save as plain decimal
                    }
                    $request->merge([
                        'kt_docs_repeater_basic' => array_replace(
                            $request->kt_docs_repeater_basic,
                            [$key => array_replace($item, ['cost' => $cost])]
                        )
                    ]);
                }
                
                // Also process component prices if they exist
                if (isset($item['components'])) {
                    $components = $item['components'];
                    if (is_string($components)) {
                        $components = json_decode($components, true);
                    }
                    
                    if (is_array($components)) {
                        foreach ($components as $compIndex => $component) {
                            // Clean main component price
                            if (isset($component['price'])) {
                                $price = $component['price'];
                                if (is_string($price)) {
                                    $price = (float)$price; // Save as plain decimal
                                    $components[$compIndex]['price'] = $price;
                                }
                            }
                            
                            // Clean sub-component prices
                            if (isset($component['sub_items']) && is_array($component['sub_items'])) {
                                foreach ($component['sub_items'] as $subIndex => $subItem) {
                                    if (isset($subItem['price'])) {
                                        $price = $subItem['price'];
                                        if (is_string($price)) {
                                            $price = (float)$price; // Save as plain decimal
                                            $components[$compIndex]['sub_items'][$subIndex]['price'] = $price;
                                        }
                                    }
                                }
                            }
                        }
                        
                        // Update the components back to the request
                        $updatedItem = array_replace($item, ['components' => json_encode($components)]);
                        $request->merge([
                            'kt_docs_repeater_basic' => array_replace(
                                $request->kt_docs_repeater_basic,
                                [$key => $updatedItem]
                            )
                        ]);
                    }
                }
            }
        }
        
        $validator = Validator::make($request->all(), [
            'kt_docs_repeater_basic.*' => 'required|array',
            'kt_docs_repeater_basic.*.category' => 'required',
            'kt_docs_repeater_basic.*.cost' => 'required|numeric|min:0',
            'kt_docs_repeater_basic.*.components' => 'sometimes',  // Can be array or JSON string
            'area_id' => 'required|exists:areas,id',
        ]);
        
        // Custom validation for components
        if ($validator->passes() && $request->has('kt_docs_repeater_basic')) {
            foreach ($request->kt_docs_repeater_basic as $index => $item) {
                if (isset($item['components'])) {
                    $components = null;
                    
                    // Parse components if they're in JSON string format
                    if (is_string($item['components'])) {
                        try {
                            $components = json_decode($item['components'], true);
                            if (json_last_error() !== JSON_ERROR_NONE) {
                                $validator->errors()->add("kt_docs_repeater_basic.{$index}.components", 'Invalid JSON format for components');
                            }
                        } catch (\Exception $e) {
                            $validator->errors()->add("kt_docs_repeater_basic.{$index}.components", 'Error parsing components data');
                        }
                    } else if (is_array($item['components'])) {
                        $components = $item['components'];
                    }
                    
                    // Validate component structure if we have components
                    if ($components) {
                        foreach ($components as $cIndex => $component) {
                            if (empty($component['name'])) {
                                $validator->errors()->add("kt_docs_repeater_basic.{$index}.components.{$cIndex}.name", 'Component name is required');
                            }
                            
                            if (isset($component['price']) && !is_numeric(str_replace(',', '.', str_replace(['Rp', ' ', '.'], '', $component['price'])))) {
                                $validator->errors()->add("kt_docs_repeater_basic.{$index}.components.{$cIndex}.price", 'Price must be a number');
                            }
                            
                            if (isset($component['operator']) && !in_array($component['operator'], ['*', '+', '-', '/'])) {
                                $validator->errors()->add("kt_docs_repeater_basic.{$index}.components.{$cIndex}.operator", 'Invalid operator');
                            }
                            
                            // Validate sub-components if any
                            if (isset($component['sub_items']) && is_array($component['sub_items'])) {
                                foreach ($component['sub_items'] as $sIndex => $subItem) {
                                    if (isset($subItem['price']) && !is_numeric(str_replace(',', '.', str_replace(['Rp', ' ', '.'], '', $subItem['price'])))) {
                                        $validator->errors()->add("kt_docs_repeater_basic.{$index}.components.{$cIndex}.sub_items.{$sIndex}.price", 'Sub-component price must be a number');
                                    }
                                    
                                    if (isset($subItem['operator']) && !in_array($subItem['operator'], ['*', '+', '-', '/'])) {
                                        $validator->errors()->add("kt_docs_repeater_basic.{$index}.components.{$cIndex}.sub_items.{$sIndex}.operator", 'Invalid sub-component operator');
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        if ($validator->fails()) {
            // Debug log validation failures for score_positive, impacts, and employee_ids
            Log::error('ScoringLv4 Validation Failed:', [
                'errors' => $validator->errors()->toArray(),
                'request_data' => [
                    'area_id' => $request->input('area_id'),
                    'score_positive' => $request->input('score_positive'),
                    'impacts' => $request->input('impacts'),
                    'employee_ids' => $request->input('employee_ids'),
                    'has_score_positive' => $request->has('score_positive'),
                    'has_impacts' => $request->has('impacts'),
                    'has_employee_ids' => $request->has('employee_ids'),
                ]
            ]);
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $diklat = Diklat::find($id);
        if (!$diklat) {
            return response()->json(['error' => 'Diklat not found2'], 404);
        }
        
        // Delete all existing tangible data for this diklat and area
        $diklat->tangibles()->where('area_id', $request->area_id)->delete();
        
        // Process each tangible item
        foreach ($request->kt_docs_repeater_basic as $item) {
            // Calculate total from components if available
            $calculatedTotal = $item['cost'];
            $componentsData = null;
            
            if (isset($item['components'])) {
                if (is_string($item['components'])) {
                    // Parse the JSON string if it comes as a string
                    $componentsData = json_decode($item['components'], true);
                } else if (is_array($item['components'])) {
                    $componentsData = $item['components'];
                }
                
                // Log component data for debugging
                Log::info('Component data for tangible', [
                    'raw_components' => $item['components'],
                    'parsed_components' => $componentsData
                ]);
            }
            
            // Create the tangible record
            $tangible = $diklat->tangibles()->create([
                'category' => $item['category'],
                'cost' => $calculatedTotal,
                'area_id' => $request->area_id,
            ]);
            
            // Process component details if they exist
            if ($componentsData && is_array($componentsData)) {
                foreach ($componentsData as $component) {
                    if (!empty($component['name'])) {
                        // Create main component detail
                        $tangible->details()->create([
                            'component_name' => $component['name'],
                            'sub_component_name' => $component['sub_name'] ?? null,
                            'price' => $component['price'] ?? 0,
                            'operator' => $component['operator'] ?? '*',
                        ]);
                        
                        // Create sub-component details if they exist
                        if (isset($component['sub_items']) && is_array($component['sub_items'])) {
                            foreach ($component['sub_items'] as $subItem) {
                                $tangible->details()->create([
                                    'component_name' => $component['name'],
                                    'sub_component_name' => $subItem['sub_name'] ?? null,
                                    'price' => $subItem['price'] ?? 0,
                                    'operator' => $subItem['operator'] ?? '*',
                                ]);
                            }
                        }
                    }
                }
            }
        }
        
        return response()->json([
            'status' => 200,
            'success' => 'Tangible data saved successfully'
        ]);
    }
    
    /**
     * Calculate total cost from tangible benefit components
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculateTangible(Request $request)
    {
        try {
            $components = $request->input('components');
            
            if (empty($components)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No components provided',
                    'total' => 0
                ]);
            }
            
            // Parse the components if they're in string format
            if (is_string($components)) {
                $components = json_decode($components, true);
                
                if (json_last_error() !== JSON_ERROR_NONE) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid JSON format for components',
                        'total' => 0
                    ]);
                }
            }
            
            // Initialize total
            $total = 0;
            
            // Process each component
            foreach ($components as $component) {
                if (empty($component['name']) || !isset($component['price'])) {
                    continue;
                }
                
                // Start with the component's base price
                $componentValue = $this->parseFloatSafe($component['price']);
                
                // Process sub-components if they exist
                if (!empty($component['sub_items'])) {
                    foreach ($component['sub_items'] as $subItem) {
                        if (!isset($subItem['price'])) {
                            continue;
                        }
                        
                        $subValue = $this->parseFloatSafe($subItem['price']);
                        $operator = $subItem['operator'] ?? '*';
                        
                        // Apply the operation based on operator
                        switch ($operator) {
                            case '*':
                                $componentValue *= $subValue;
                                break;
                            case '+':
                                $componentValue += $subValue;
                                break;
                            case '-':
                                $componentValue -= $subValue;
                                break;
                            case '/':
                                if ($subValue !== 0) { // Prevent division by zero
                                    $componentValue /= $subValue;
                                } else {
                                    // Log the division by zero attempt
                                    Log::warning('Division by zero attempted in tangible benefit calculation API', [
                                        'component' => $component['name'] ?? 'Unknown'
                                    ]);
                                    // Keep the current value unchanged
                                }
                                break;
                        }
                    }
                }
                
                // Add the component's calculated value to the total
                $total += $componentValue;
            }
            
            // Return the calculated total
            return response()->json([
                'success' => true,
                'total' => $total,
                'formatted_total' => number_format($total, 2)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error calculating total: ' . $e->getMessage(),
                'total' => 0
            ]);
        }
    }
    
    /**
     * Parse float value safely from string input
     * 
     * @param mixed $value
     * @return float
     */    private function parseFloatSafe($value)
    {
        if (empty($value)) {
            return 0.0;
        }

        if (is_numeric($value)) {
            return (float)$value;
        }

        // Convert string to proper decimal number
        $value = trim($value);
        
        // Handle Indonesian format (1.234,56)
        if (preg_match('/^\d{1,3}(\.\d{3})*,\d+$/', $value)) {
            // Remove thousand separators and convert comma to dot
            $value = str_replace('.', '', $value); // Remove thousand separators
            $value = str_replace(',', '.', $value); // Convert decimal comma to dot
        } 
        // Handle potential regular float format (1234.56)
        else {
            // Remove any non-numeric chars except dot
            $value = preg_replace('/[^\d.]/', '', $value);
        }
        
        return (float)$value;
    }
}
