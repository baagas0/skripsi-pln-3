<?php

namespace App\Http\Controllers;

use App\Models\Diklat;
use App\Models\ScoringLv5;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScoringLv5Controller extends Controller
{
    public function getIndex($id) {
        $diklat = Diklat::findOrFail($id);
        $scoreLv5 = ScoringLv5::where('diklat_id', $id)->first();
        if ($scoreLv5) {
            $tangible = (float) $scoreLv5->total_tangible;
            $cost = (float) $scoreLv5->cost_of_training;
            $roti = (float) $scoreLv5->roti;
        } else {
            $tangible = 0;
            $cost = 0;
            $roti = 0;
        }
        return view('scoring.form_lv5', compact('diklat', 'scoreLv5', 'tangible', 'cost', 'roti'));
    }

    public function postRoti(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'cost' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $diklat = Diklat::find($id);
        if (!$diklat) {
            return response()->json(['error' => 'Diklat not found1'], 404);
        }

        $tangible = (float) $diklat->tangibles()->sum('cost');
        $cost = (float) $request->cost;

        $netBenefit = $tangible - $cost;
        $roti = ($netBenefit / $cost) * 100;

        $roti = round($roti, 2);

        ScoringLv5::updateOrCreate(
            ['diklat_id' => $id],
            [
                'total_tangible' => $tangible,
                'cost_of_training' => $cost,
                'roti' => $roti,
            ]
        );

        return response()->json([
            'status' => 200,
            'message' => 'Scoring data saved successfully',
            'data' => [
                'net_benefit' => $netBenefit,
                'cost' => $cost,
                'tangible' => $tangible,
                'roti' => $roti,
            ]
        ]);
    }
}
