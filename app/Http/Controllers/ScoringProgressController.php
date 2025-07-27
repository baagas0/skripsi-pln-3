<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Diklat;
use App\Models\DiklatParticipant;
use App\Models\ScoringLv1;
use App\Models\ScoringLv2;
use App\Models\ScoringLv3;
use App\Models\ScoringLv4;
use App\Models\ScoringLv4_tangible;
use App\Models\ScoringLv5;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScoringProgressController extends Controller
{
    public function getIndex()
    {
        $roleId = Auth::user()->role_id;
        $unitId = Auth::user()->unit_id;

        // Filter diklats hanya untuk unit user yang login
        $d_1 = Diklat::query();
        if ($roleId == 2) {
            $d_1->where('vendor_id', Auth::user()->vendor_id);
        } else if ($roleId == 8) {

        } else if ($roleId !== 7) {
            $d_1->where('unit_id', $unitId);
        }
        else if ($roleId == 7) {
            // HTD melihat data dari unit yang mereka kelola
            $unitIdsString = Auth::user()->manage_unit_ids;
            $unitIds = is_string($unitIdsString) ? json_decode($unitIdsString) : $unitIdsString;
            $d_1->whereIn('unit_id', $unitIds ?? []);
        }
        else if ($roleId == 8) {
            // Vice President melihat data dari unit yang mereka kelola
            $unitIdsString = Auth::user()->manage_unit_ids;
            $unitIds = is_string($unitIdsString) ? json_decode($unitIdsString) : $unitIdsString;
            $d_1->whereIn('unit_id', $unitIds ?? []);
        }
        $diklats = $d_1->get();

        $diklat_id = request('diklat_id');
        $diklat = null;
        $percentageProgressLv1 = 0;
        $percentageProgressLv2 = 0;
        $percentageProgressLv3 = 0;
        $percentageProgressLv4 = 0;
        $percentageProgressLv5 = 0;

        $list1 = [
            'already' => [],
            'not_yet' => [],
        ];
        $list2 = [
            'already' => [],
            'not_yet' => [],
        ];
        $list3 = [
            'already' => [],
            'not_yet' => [],
        ];
        $list4 = [
            'already' => [],
            'not_yet' => [],
        ];

        if ($diklat_id) {
            $diklat = Diklat::findOrFail($diklat_id);
            $diklatParticipant = DiklatParticipant::where('diklat_id', $diklat_id)->get();
            $totalParticipants = $diklat->count_of_participant;

            // Calculate progress for Level 1
            $countCompletedLv1 = ScoringLv1::whereHas('diklatParticipant', function ($query) use ($diklat_id) {
                $query->where('diklat_id', $diklat_id);
            })
                ->get()
                ->groupBy('diklat_participant_id');
            $list1['already'] = $diklatParticipant->whereIn('id', $countCompletedLv1->keys());
            $list1['not_yet'] = $diklatParticipant->whereNotIn('id', $countCompletedLv1->keys());
            $percentageProgressLv1 = $totalParticipants > 0 ? ($countCompletedLv1->count() / $totalParticipants) * 100 : 0;

            // Calculate progress for Level 2
            $countCompletedLv2 = ScoringLv2::whereHas('diklatParticipant', function ($query) use ($diklat_id) {
                $query->where('diklat_id', $diklat_id);
            })
                ->get();
            $list2['already'] = $diklatParticipant->whereIn('id', $countCompletedLv2->pluck('diklat_participant_id'));
            $list2['not_yet'] = $diklatParticipant->whereNotIn('id', $countCompletedLv2->pluck('diklat_participant_id'));
            $percentageProgressLv2 = $totalParticipants > 0 ? ($countCompletedLv2->count() / $totalParticipants) * 100 : 0;

            // Calculate progress for Level 3
            $countCompletedLv3 = ScoringLv3::whereHas('diklatParticipant', function ($query) use ($diklat_id) {
                $query->where('diklat_id', $diklat_id);
            })
                ->get()
                ->groupBy('diklat_participant_id');
            $list3['already'] = $diklatParticipant->whereIn('id', $countCompletedLv3->keys());
            $list3['not_yet'] = $diklatParticipant->whereNotIn('id', $countCompletedLv3->keys());
            $percentageProgressLv3 = $totalParticipants > 0 ? ($countCompletedLv3->count() / $totalParticipants) * 100 : 0;

            // Calculate progress for Level 4
            $lv4 = ScoringLv4::where('diklat_id', $diklat_id)->get();
            $areas = Area::where('unit_id', $diklat->unit_id)->whereIn('id', $diklat->areas->pluck('id')->toArray())->get();

            $lv4AreaId = $lv4->pluck('area_id')->toArray();

            $list4['already'] = $areas->whereIn('id', $lv4AreaId);
            $list4['not_yet'] = $areas->whereNotIn('id', $lv4AreaId);
            $percentageProgressLv4 = $areas->count() > 0 ? round(($lv4->count() / $areas->count()) * 100, 2) : 0;

            // Collect employee data for Level 4
            $list4['employee_data'] = [];
            foreach ($lv4 as $score) {
                if (!empty($score->employee_ids)) {
                    $employeeIds = $score->employee_ids;
                    $employees = DiklatParticipant::where('diklat_id', $diklat_id)
                        ->whereIn('employee_id', $employeeIds)
                        ->with('employee')
                        ->get();

                    $list4['employee_data'][$score->area_id] = $employees;
                }
            }

            // Calculate progress for Level 5
            $percentageProgressLv5 = ScoringLv5::where('diklat_id', $diklat_id)->count() > 0 ? 100 : 0;
        }

        // dd($list1, $list2, $list3, $list4);
        return view('scoring.progress', compact(
            'diklats',
            'diklat',
            'percentageProgressLv1',
            'percentageProgressLv2',
            'percentageProgressLv3',
            'percentageProgressLv4',
            'percentageProgressLv5',
            'list1',
            'list2',
            'list3',
            'list4',
        ));
    }
}
