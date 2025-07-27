<?php

namespace App\Http\Controllers;

use App\Models\Diklat;
use App\Models\ScoringLv1;
use App\Models\ScoringLv1Question;
use App\Models\ScoringLv2;
use App\Models\ScoringLv3;
use App\Models\ScoringLv3Question;
use App\Models\ScoringLv4;
use App\Models\ScoringLv4_tangible;
use App\Models\ScoringLv5;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    public function getIndex()
    {
        $roleId = Auth::user()->role_id;
        $unitId = Auth::user()->unit_id;

        // Filter diklats hanya untuk unit user yang login
        // $diklats = Diklat::where('unit_id', $unitId)->get();
        $d_1 = Diklat::query();
        if ($roleId == 2) {
            $d_1->where('vendor_id', Auth::user()->vendor_id);
        } else if ($roleId !== 7) {
            $d_1->where('unit_id', $unitId);
        }
        $diklats = $d_1->get();

        // Get diklat_ids instead of a single diklat_id
        $diklatIds = request()->get('diklat_ids', []);
        // For backward compatibility, still check for diklat_id
        $oldDiklatId = request()->get('diklat_id');
        if ($oldDiklatId && empty($diklatIds)) {
            $diklatIds = [$oldDiklatId];
        }
        
        $levels = request()->get('levels', ['all']); // Default to 'all' if not specified

        // Validasi akses - hanya boleh melihat diklat dari unit mereka
        if (!empty($diklatIds)) {
            foreach ($diklatIds as $id) {
                $diklat = Diklat::find($id);
                if ($diklat && $diklat->unit_id != $unitId && $roleId !== 7) {
                    abort(403, 'You can only view reports for diklat from your unit');
                }
            }
        }

        // Convert to array if not already
        if (!is_array($levels)) {
            $levels = [$levels];
        }

        // If 'all' is selected, it overrides individual selections
        $showAllLevels = in_array('all', $levels);

        // Convert to simple array without 'all' for use in views
        $selectedLevels = $showAllLevels ? ['1', '2', '3', '4', '5'] : $levels;

        // Prepare data structures for each diklat
        $diklatData = [];
        $diklatsForComparison = [];
        
        // Common data that doesn't change per diklat
        $level1Question = ScoringLv1Question::get();
        $level1Question1 = $level1Question->where('group', 'I. Pelaksanaan Pelatihan');
        $level1Question2 = $level1Question->where('group', 'II. Narasumber Pelatihan');
        $score3Question = ScoringLv3Question::get();
        $scoreName = ['(Kurang)', '(Cukup)', '(Baik)', '(Sangat Baik)', '(Luar Biasa)'];
        
        // Categories for charts
        $scoreList1Category = [];
        $scoreList2Category = [];
        
        foreach ($level1Question1 as $question) {
            $scoreList1Category[] = Str::limit($question->name, 20, '...');
        }
        foreach ($level1Question2 as $question) {
            $scoreList2Category[] = Str::limit($question->name, 20, '...');
        }

        // Process data for each selected diklat
        if (!empty($diklatIds)) {
            foreach ($diklatIds as $diklatId) {
                $diklat = Diklat::find($diklatId);
                if (!$diklat) continue;
                
                $diklatsForComparison[] = $diklat;
                
                // LEVEL 1 data for this diklat
                $scoreList1 = [];
                $scoreList2 = [];
                $tableList1 = [];
                $tableList2 = [];
                
                $scoreLevel1Result = ScoringLv1::whereHas('diklatParticipant', function ($q) use ($diklatId) {
                    $q->where('diklat_id', $diklatId);
                })->get();
                
                foreach ([1, 2, 3, 4, 5] as $key) {
                    $data1 = [];
                    foreach ($level1Question1 as $question) {
                        $score = $scoreLevel1Result->where('scoring_lv1_question_id', $question->id)->where('score', $key)->count();
                        $data1[] = $score;
                    }
                    array_push($scoreList1, [
                        'name' => "Score $key " . $scoreName[$key - 1],
                        'data' => $data1,
                    ]);

                    $data2 = [];
                    foreach ($level1Question2 as $question) {
                        $score = $scoreLevel1Result->where('scoring_lv1_question_id', $question->id)->where('score', $key)->count();
                        $data2[] = $score;
                    }
                    array_push($scoreList2, [
                        'name' => "Score $key " . $scoreName[$key - 1],
                        'data' => $data2,
                    ]);
                }
                
                foreach ($level1Question1 as $question) {
                    $countScore1 = $scoreLevel1Result->where('scoring_lv1_question_id', $question->id)->where('score', 1)->count();
                    $countScore2 = $scoreLevel1Result->where('scoring_lv1_question_id', $question->id)->where('score', 2)->count();
                    $countScore3 = $scoreLevel1Result->where('scoring_lv1_question_id', $question->id)->where('score', 3)->count();
                    $countScore4 = $scoreLevel1Result->where('scoring_lv1_question_id', $question->id)->where('score', 4)->count();
                    $countScore5 = $scoreLevel1Result->where('scoring_lv1_question_id', $question->id)->where('score', 5)->count();

                    $countParticipant = $scoreLevel1Result->where('scoring_lv1_question_id', $question->id)->count();
                    $tableList1[] = [
                        'question' => $question->name,
                        'score1' => $countScore1,
                        'score2' => $countScore2,
                        'score3' => $countScore3,
                        'score4' => $countScore4,
                        'score5' => $countScore5,
                        'total' => $countParticipant,
                    ];
                }
                
                foreach ($level1Question2 as $question) {
                    $countScore1 = $scoreLevel1Result->where('scoring_lv1_question_id', $question->id)->where('score', 1)->count();
                    $countScore2 = $scoreLevel1Result->where('scoring_lv1_question_id', $question->id)->where('score', 2)->count();
                    $countScore3 = $scoreLevel1Result->where('scoring_lv1_question_id', $question->id)->where('score', 3)->count();
                    $countScore4 = $scoreLevel1Result->where('scoring_lv1_question_id', $question->id)->where('score', 4)->count();
                    $countScore5 = $scoreLevel1Result->where('scoring_lv1_question_id', $question->id)->where('score', 5)->count();

                    $countParticipant = $scoreLevel1Result->where('scoring_lv1_question_id', $question->id)->count();
                    $tableList2[] = [
                        'question' => $question->name,
                        'score1' => $countScore1,
                        'score2' => $countScore2,
                        'score3' => $countScore3,
                        'score4' => $countScore4,
                        'score5' => $countScore5,
                        'total' => $countParticipant,
                    ];
                }
                
                // LEVEL 2 data for this diklat
                $score2 = ScoringLv2::whereHas('diklatParticipant', function ($q) use ($diklatId) {
                    $q->where('diklat_id', $diklatId);
                })->get();
                $avgPretest = $score2->avg('pretest_score');
                $avgPosttest = $score2->avg('posttest_score');
                $avgLevel2 = $score2->avg('diff_score');
                
                // LEVEL 3 data for this diklat
                $score3 = ScoringLv3::whereHas('diklatParticipant', function ($q) use ($diklatId) {
                    $q->where('diklat_id', $diklatId);
                })->get();
                $score3Participant = $score3->groupBy('diklat_participant_id');
                $scoreList3 = [];
                
                foreach ($score3Participant as $participantId => $score) {
                    $avg = $score->avg('score');
                    $sum = $score->sum('score');
                    $result = '';
                    if ($sum <= 7) {
                        $result = 'Tidak Efektif';
                    } elseif ($sum <= 14) {
                        $result = 'Kurang Efektif';
                    } elseif ($sum <= 21) {
                        $result = 'Cukup Efektif';
                    } elseif ($sum <= 28) {
                        $result = 'Efektif';
                    } else {
                        $result = 'Sangat Efektif';
                    }
                    $scoreList3[] = [
                        'name' => $score->first()->diklatParticipant->employee->name,
                        'score' => $score,
                        'avg' => number_format($score->avg('score'), 2),
                        'sum' => $score->sum('score'),
                        'result' => $result,
                    ];
                }
                
                $scoreList3 = collect($scoreList3);
                $scoreList3Result = [
                    [
                        'name' => 'Tidak Efektif',
                        'range' => '1 s/d 7',
                        'count' => $scoreList3->where('result', 'Tidak Efektif')->count(),
                    ],
                    [
                        'name' => 'Kurang Efektif',
                        'range' => '8 s/d 14',
                        'count' => $scoreList3->where('result', 'Kurang Efektif')->count(),
                    ],
                    [
                        'name' => 'Cukup Efektif',
                        'range' => '15 s/d 21',
                        'count' => $scoreList3->where('result', 'Cukup Efektif')->count(),
                    ],
                    [
                        'name' => 'Efektif',
                        'range' => '22 s/d 28',
                        'count' => $scoreList3->where('result', 'Efektif')->count(),
                    ],
                    [
                        'name' => 'Sangat Efektif',
                        'range' => '29 s/d 35',
                        'count' => $scoreList3->where('result', 'Sangat Efektif')->count(),
                    ],
                ];
                $scoreList3Result = collect($scoreList3Result);
                
                // LEVEL 4 data for this diklat
                $score4Label = 'Tidak Berdampak';
                $score4 = ScoringLv4::where('diklat_id', $diklatId)->get();

                $score4Positive = $score4->avg('score_positive');
                $score4PositiveRound = (int) round($score4Positive);
                $positifLabel = [
                    1 => 'Tidak Berdampak',
                    2 => 'Kurang Berdampak',
                    3 => 'Cukup Berdampak',
                    4 => 'Berdampak',
                    5 => 'Sangat Berdampak',
                ];
                $score4Label = $positifLabel[$score4PositiveRound] ?? 'Tidak Berdampak';

                $score4Impacts = [];
                foreach ($score4 as $score) {
                    $score4Impacts = array_merge($score4Impacts, $score->impacts);
                }
                $score4Impacts = array_unique($score4Impacts);
                $score4Tangibles = ScoringLv4_tangible::where('diklat_id', $diklatId)->get();
                
                // LEVEL 5 data for this diklat
                $score5 = ScoringLv5::where('diklat_id', $diklatId)->first();
                
                // Store all processed data for this diklat
                $diklatData[$diklatId] = [
                    'name' => $diklat->name,
                    'scoreList1' => $scoreList1,
                    'scoreList2' => $scoreList2,
                    'tableList1' => collect($tableList1),
                    'tableList2' => collect($tableList2),
                    'score2' => $score2,
                    'avgPretest' => $avgPretest,
                    'avgPosttest' => $avgPosttest,
                    'avgLevel2' => $avgLevel2,
                    'scoreList3' => $scoreList3,
                    'scoreList3Result' => $scoreList3Result,
                    'score4Label' => $score4Label,
                    'score4Impacts' => $score4Impacts,
                    'score4Tangibles' => $score4Tangibles,
                    'score5' => $score5,
                ];
            }
        }

        // dd($diklatData);

        $data = [
            'diklats' => $diklats,
            'diklatIds' => $diklatIds,
            'levels' => $levels,
            'selectedLevels' => $selectedLevels,
            'showAllLevels' => $showAllLevels,
            
            // Common data
            'scoreList1Category' => $scoreList1Category,
            'scoreList2Category' => $scoreList2Category,
            'score3Question' => $score3Question,
            
            // Data for each diklat
            'diklatData' => $diklatData,
            'diklatsForComparison' => $diklatsForComparison,
        ];
        
        return view('report.index', $data);
    }

    public function getDownloadPdf($diklatId)
    {
        // For backward compatibility, we still support single diklatId in URL
        // but we also check for multiple diklat_ids in query params
        $diklatIds = request()->get('diklat_ids', [$diklatId]);
        if (!is_array($diklatIds)) {
            $diklatIds = [$diklatIds];
        }
        
        // Only proceed with the first diklat for PDF download (for simplicity)
        // If specific PDF comparison is needed, it could be implemented in a separate method
        $diklatId = $diklatIds[0];
        
        $levels = request()->get('levels', ['all']);

        // Convert to array if not already
        if (!is_array($levels)) {
            $levels = [$levels];
        }

        // If 'all' is selected, it overrides individual selections
        $showAllLevels = in_array('all', $levels);

        // Convert to simple array without 'all' for use in views
        $selectedLevels = $showAllLevels ? ['1', '2', '3', '4', '5'] : $levels;

        // LEVEL 1
        $scoreList1 = [];
        $scoreList2 = [];
        $scoreList1Category = [];
        $scoreList2Category = [];
        $scoreLevel1Result = ScoringLv1::whereHas('diklatParticipant', function ($q) use ($diklatId) {
            $q->where('diklat_id', $diklatId);
        })->get();
        $level1Question = ScoringLv1Question::get();
        $level1Question1 = $level1Question->where('group', 'I. Pelaksanaan Pelatihan');
        $level1Question2 = $level1Question->where('group', 'II. Narasumber Pelatihan');
        $scoreName = ['(Kurang)', '(Cukup)', '(Baik)', '(Sangat Baik)', '(Luar Biasa)'];
        foreach ([1, 2, 3, 4, 5] as $key) {
            $data1 = [];
            foreach ($level1Question1 as $question) {
                $score = $scoreLevel1Result->where('scoring_lv1_question_id', $question->id)->where('score', $key)->count();
                $data1[] = $score;
            }
            array_push($scoreList1, [
                'name' => "Score $key " . $scoreName[$key - 1],
                'data' => $data1,
            ]);

            $data2 = [];
            foreach ($level1Question2 as $question) {
                $score = $scoreLevel1Result->where('scoring_lv1_question_id', $question->id)->where('score', $key)->count();
                $data2[] = $score;
            }
            array_push($scoreList2, [
                'name' => "Score $key " . $scoreName[$key - 1],
                'data' => $data2,
            ]);
        }

        foreach ($level1Question1 as $question) {
            $scoreList1Category[] = \Str::limit($question->name, 20, '...');
        }
        foreach ($level1Question2 as $question) {
            $scoreList2Category[] = \Str::limit($question->name, 20, '...');
        }

        // LEVEL 2
        $score2 = ScoringLv2::whereHas('diklatParticipant', function ($q) use ($diklatId) {
            $q->where('diklat_id', $diklatId);
        })->get();
        $avgPretest = $score2->avg('pretest_score');
        $avgPosttest = $score2->avg('posttest_score');
        $avgLevel2 = $score2->avg('diff_score');

        // Level 3
        $score3 = ScoringLv3::whereHas('diklatParticipant', function ($q) use ($diklatId) {
            $q->where('diklat_id', $diklatId);
        })->get();
        $score3Participant = $score3->groupBy('diklat_participant_id');
        $scoreList3 = [];
        foreach ($score3Participant as $participantId => $score) {
            $avg = $score->avg('score');
            $result = ''; // Tidak Efektif, Kurang Efektif, Cukup Efektif, Efektif, Sangat Efektif
            // 1 -7 = Tidak Efektif
            // 8 - 14 = Kurang Efektif
            // 15 - 21 = Cukup Efektif
            // 22 - 28 = Efektif
            // 29 - 35 = Sangat Efektif
            if ($avg <= 7) {
                $result = 'Tidak Efektif';
            } elseif ($avg <= 14) {
                $result = 'Kurang Efektif';
            } elseif ($avg <= 21) {
                $result = 'Cukup Efektif';
            } elseif ($avg <= 28) {
                $result = 'Efektif';
            } else {
                $result = 'Sangat Efektif';
            }
            $scoreList3[] = [
                'name' => $score->first()->diklatParticipant->employee->name,
                'avg' => $score->avg('score'),
                'result' => $result,
            ];
        }
        $scoreList3 = collect($scoreList3);
        $scoreList3Result = [
            [
                'name' => 'Tidak Efektif',
                'range' => '1 s/d 7',
                'count' => $scoreList3->where('result', 'Tidak Efektif')->count(),
            ],
            [
                'name' => 'Kurang Efektif',
                'range' => '8 s/d 14',
                'count' => $scoreList3->where('result', 'Kurang Efektif')->count(),
            ],
            [
                'name' => 'Cukup Efektif',
                'range' => '15 s/d 21',
                'count' => $scoreList3->where('result', 'Cukup Efektif')->count(),
            ],
            [
                'name' => 'Efektif',
                'range' => '22 s/d 28',
                'count' => $scoreList3->where('result', 'Efektif')->count(),
            ],
            [
                'name' => 'Sangat Efektif',
                'range' => '29 s/d 35',
                'count' => $scoreList3->where('result', 'Sangat Efektif')->count(),
            ],
        ];
        $scoreList3Result = collect($scoreList3Result);

        // LEVEL 4
        $score4Label = 'Cukup Berdampak';
        $score4 = ScoringLv4::where('diklat_id', $diklatId)->get();
        $score4Impacts = [];
        foreach ($score4 as $score) {
            $score4Impacts = array_merge($score4Impacts, $score->impacts);
        }
        $score4Impacts = array_unique($score4Impacts);
        
        // Get tangibles with their details
        $score4Tangibles = ScoringLv4_tangible::where('diklat_id', $diklatId)->get();
        
        // Load the details for each tangible benefit
        foreach ($score4Tangibles as $tangible) {
            $tangible->detailsData = $tangible->details()->get();
            
            // Group details by component for easier display
            $tangible->componentGroups = $tangible->detailsData->groupBy('component_name');
        }

        // LEVEL 5
        $score5 = ScoringLv5::where('diklat_id', $diklatId)->first();

        $data = [
            'diklatId' => $diklatId,
            'levels' => $levels,
            'selectedLevels' => $selectedLevels,
            'showAllLevels' => $showAllLevels,

            // Level 1
            'scoreList1' => $scoreList1,
            'scoreList1Category' => $scoreList1Category,
            'scoreList2' => $scoreList2,
            'scoreList2Category' => $scoreList2Category,

            // Level 3
            'scoreList3Result' => $scoreList3Result,

            // Level 2
            'avgPretest' => $avgPretest,
            'avgPosttest' => $avgPosttest,
            'avgLevel2' => $avgLevel2,

            // Level 4
            'score4Label' => $score4Label,
            'score4Impacts' => $score4Impacts,
            'score4Tangibles' => $score4Tangibles,

            // Level 5
            'score5' => $score5,
        ];

        $pdf = Pdf::loadView('report.filtered_pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'isFontSubsettingEnabled' => true,
            'isPhpEnabled' => true,
            'enable-javascript' => true,
        ]);
        $pdf->setWarnings(false);

        // Generate a descriptive filename based on training name and selected level
        $diklat = Diklat::find($diklatId);
        $diklatName = $diklat ? str_replace(' ', '_', $diklat->name) : 'report';

        $levelText = '';
        if (!$showAllLevels) {
            $levelLabels = [
                '1' => 'L1',
                '2' => 'L2',
                '3' => 'L3',
                '4' => 'L4',
                '5' => 'L5'
            ];

            $levelNames = [];
            foreach ($selectedLevels as $lvl) {
                if (isset($levelLabels[$lvl])) {
                    $levelNames[] = $levelLabels[$lvl];
                }
            }

            if (!empty($levelNames)) {
                $levelText = '_' . implode('_', $levelNames);
            }
        }

        $filename = 'Laporan_' . $diklatName . $levelText . '_' . date('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }
}
