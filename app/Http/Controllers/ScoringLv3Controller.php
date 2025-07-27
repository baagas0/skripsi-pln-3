<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Diklat;
use App\Models\DiklatParticipant;
use App\Models\Employee;
use App\Models\ScoringLv3;
use App\Models\ScoringLv3Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ScoringLv3Controller extends Controller
{
    public function getIndex($id) {
        $diklat = Diklat::findOrFail($id);
        $userRole = auth()->user()->role_id;

        $participants = DiklatParticipant::where('diklat_id', $id)->get();
        $urlLv3 = route('form.lv3.form', encrypt_custom($diklat->slug));
        $questions = null;
        $scoreLv3 = null;

        if (request()->has('diklat_participant_id')) {
            $questions = ScoringLv3Question::get();
            $scoreLv3 = ScoringLv3::where('diklat_participant_id', request()->get('diklat_participant_id'))->get();
        }
        return view('scoring.form_lv3', compact('diklat', 'participants', 'questions', 'scoreLv3', 'urlLv3', 'userRole'));
    }

    public function getForm($hash_slug) {
        $slug = decrypt_custom($hash_slug);
        $diklat = Diklat::where('slug', $slug)->first();
        $userRole = isset(auth()->user()->role_id) ? auth()->user()->role_id : 0;
        
        if (!$diklat) {
            return redirect()->route('home')->with('error', 'Diklat not found');
        }
        $areas = Area::where('unit_id', $diklat->unit_id)->get();
        $participants = DiklatParticipant::where('diklat_id', $diklat->id)
            // ->whereHas('employee', function ($query) {
            //     $query->where('area_id', request()->get('area_id'));
            // })
            ->with('employee')
            ->get();
        $selectedParticipants = $participants->where('employee.area_id', request()->get('area_id'));
        
        $questions = null;
        $scoreLv3 = null;
        if (request()->has('diklat_participant_id')) {
            $questions = ScoringLv3Question::get();
            $scoreLv3 = ScoringLv3::where('diklat_participant_id', request()->get('diklat_participant_id'))->get();
        }

        $urlLv3 = route('form.lv3.form', $hash_slug);
        return view('scoring.form_lv3_head_area', compact('diklat', 'areas', 'participants', 'selectedParticipants', 'hash_slug', 'urlLv3', 'questions', 'scoreLv3', 'userRole'));
    }

    public function getData(Request $request) 
    {
        $query = ScoringLv3::with(['diklatParticipant']);
        
        if ($request->has('year')) {
            $query->where('year', $request->year);
        }

        return datatables($query)
            ->addColumn('name', function ($row) {
                return $row->diklatParticipant->employee->name;
            })
            ->toJson();
    }

    public function postStore(Request $request, $id) 
    {
        $validator = Validator::make($request->all(), [
            'diklat_participant_id' => 'required|exists:diklat_participants,id',
            'question.*' => 'required|array',
            'question.*.scoring_lv3_question_id' => 'required',
            'question.*.diklat_participant_id' => 'required',
            'question.*.score' => 'required|in:1,2,3,4,5',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $participant = DiklatParticipant::findOrFail($request->diklat_participant_id);
        if (!$participant) {
            return response()->json(['errors' => ['Participant not found']], 404);
        }

        $employee = $participant->employee;
        if (!$employee) {
            return response()->json(['errors' => ['Employee not found']], 404);
        }

        $diklat = $participant->diklat;
        if (!$diklat) {
            return response()->json(['errors' => ['Diklat not found4']], 404);
        }

        // $scorring = ScoringLv3::where('diklat_participant_id', $participant->id)
        //     ->first();
        // if ($scorring) {
        //     return response()->json(['errors' => ['You have already submitted this form']], 422);
        // }

        $questions = $request->question;
        foreach ($questions as &$question) {
            $scoringLv1Question = ScoringLv3Question::find($question['scoring_lv3_question_id']);
            if (!$scoringLv1Question) {
                return response()->json(['errors' => ['Scoring question not found']], 404);
            }

            $question['diklat_participant_id'] = $participant->id;
            $question['created_at'] = now();
            $question['updated_at'] = now();
        }

        DB::beginTransaction();
        try {
            $questions = $request->question;
            foreach ($questions as $question) {
                $scoringLv3Question = ScoringLv3Question::find($question['scoring_lv3_question_id']);
                if (!$scoringLv3Question) {
                    throw new \Exception('Scoring question not found');
                }

                ScoringLv3::updateOrCreate(
                    [
                        'diklat_participant_id' => $participant->id,
                        'scoring_lv3_question_id' => $question['scoring_lv3_question_id']
                    ],
                    [
                        'score' => $question['score'],
                        'updated_at' => now()
                    ]
                );
            }

            DB::commit();
            // return redirect()->back()->with('success', 'Score saved successfully');
            return response()->json([
                'status' => 200,
                'success' => 'Scoring data saved successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'errors' => [$e->getMessage()]
            ], 422);
        }
    }
}
