<?php

namespace App\Http\Controllers;

use App\Exports\ScoringLv2Template;
use App\Imports\ScoringLv2Import;
use App\Models\Diklat;
use App\Models\DiklatParticipant;
use App\Models\ScoringLv2;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ScoringLv2Controller extends Controller
{
    public function getIndex($id) {
        $diklat = Diklat::find($id);

        $participants = DiklatParticipant::where('diklat_id', $id)->get();
        $scoreLv2 = null;
        
        if (request()->has('diklat_participant_id')) {
            $scoreLv2 = ScoringLv2::where('diklat_participant_id', request()->get('diklat_participant_id'))->get();
        }
        
        return view('scoring.form_lv2', compact('diklat', 'participants', 'scoreLv2'));
    }

    public function getData(Request $request) 
    {
        // dd($request->diklat_id);
        $query = ScoringLv2::with(['diklatParticipant'])
            ->whereHas('diklatParticipant', function ($query) use ($request) {
                $query->where('diklat_id', $request->diklat_id);
            });
        
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
        $validated = $request->validate([
            'diklat_participant_id' => 'required|exists:diklat_participants,id',
            'pretest_score' => 'required|numeric|min:0|max:100',
            'posttest_score' => 'required|numeric|min:0|max:100',
        ]);

        ScoringLv2::updateOrCreate(
            ['diklat_participant_id' => $validated['diklat_participant_id']],
            [
                'pretest_score' => $validated['pretest_score'],
                'posttest_score' => $validated['posttest_score'],
                'diff_score' => $validated['posttest_score'] - $validated['pretest_score'],
                'average_score' => ($validated['pretest_score'] + $validated['posttest_score']) / 2,
            ]
        );

        ScoringLv2::recalculateRank($validated['diklat_participant_id']);

        return redirect()->back()->with('success', 'Score saved successfully');
    }

    public function getTemplate($id)
    {
        return Excel::download(new ScoringLv2Template($id), 'scoring_lv2_template.xlsx');
    }

    public function postImport(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            // Save file to public path
            $fileName = time().'.'.$request->file->getClientOriginalExtension();
            $request->file->move(public_path('/uploadedfiles'), $fileName);
            Excel::import(new ScoringLv2Import, public_path('/uploadedfiles/'.$fileName));

            // Recalculate ranks for all participants
            $participants = DiklatParticipant::where('diklat_id', $id)->get();
            foreach ($participants as $participant) {
                ScoringLv2::recalculateRank($participant->id);
            }

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
