<?php

namespace App\Http\Controllers;

use App\Mail\ScoringLv1Reminder;
use App\Models\Diklat;
use App\Models\DiklatParticipant;
use App\Models\Employee;
use App\Models\ScoringLv1;
use App\Models\ScoringLv1Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ScoringLv1Controller extends Controller
{
    public function getIndex($id) {
        $diklat = Diklat::findOrFail($id);

        $participants = DiklatParticipant::where('diklat_id', $id)->get();

        $questions = null;
        $scoreLv1 = null;
        $hashDiklat = encrypt_custom($diklat->slug);
        $urlLv1 = url('form/lv1/' . $hashDiklat);

        if (request()->has('diklat_participant_id')) {
            $questions = ScoringLv1Question::get()->groupBy('group');
            $scoreLv1 = ScoringLv1::where('diklat_participant_id', request()->get('diklat_participant_id'))->get();
        }

        // dd($scoreLv1);
        return view('scoring.form_lv1', compact('diklat', 'participants', 'questions', 'scoreLv1', 'urlLv1'));
    }

    public function getWelcomeForm($hash_slug) {
        // Descripy md5
        $slug = decrypt_custom($hash_slug);
        
        $diklat = Diklat::where('slug', $slug)->first();

        return view('scoring.form_welcome', compact('diklat', 'hash_slug'));
    }

    public function getEmployeeLoginForm() {
        // Get employee's available diklat
        $employee = null;
        
        // Check for user authentication (either standard user or employee)
        if (Auth::check() && Auth::user()->role_id == 5) {
            $employee = Employee::where('email', Auth::user()->email)->first();
        } else if (Auth::guard('employee')->check()) {
            $employee = Auth::guard('employee')->user();
        }
        
        if (!$employee) {
            return redirect()->route('login')->with('error', 'Employee not found');
        }

        $availableDiklat = DiklatParticipant::where('employee_id', $employee->id)
            ->with(['diklat', 'scoreLv1'])
            ->get();
        
        if ($availableDiklat->count() == 0) {
            return redirect()->route('form.lv1.welcome', ['hash_slug' => 'none'])
                ->with('info', 'You are not assigned to any training sessions.');
        }
        
        return view('scoring.form_lv1_employee_login', compact('availableDiklat'));
    }

    public function getForm($hash_slug) {
        // Decrypt slug
        $slug = decrypt_custom($hash_slug);
        
        $diklat = Diklat::where('slug', $slug)->first();
        if (!$diklat) {
            return redirect()->route('login')
                ->with('error', 'Training program not found');
        }
        
        $questions = ScoringLv1Question::get()->groupBy('group');
        $hasSubmitted = false;
        
        // Check if user is logged in
        $employee = null;
        // Check for user authentication (either standard user or employee)
        if (Auth::check() && Auth::user()->role_id == 5) {
            $employee = Employee::where('email', Auth::user()->email)->first();
        } else if (Auth::guard('employee')->check()) {
            $employee = Auth::guard('employee')->user();
        }
        
        if ($employee) {
            // For logged-in users, check if they're a participant
            $participant = DiklatParticipant::where('diklat_id', $diklat->id)
                ->where('employee_id', $employee->id)
                ->first();
                
            if (!$participant) {
                return redirect()->route('form.lv1.employee.login')
                    ->with('error', 'You are not registered for this training program');
            }
            
            // Check if employee has already submitted the form
            $hasSubmitted = ScoringLv1::where('diklat_participant_id', $participant->id)->exists();
            
            return view('scoring.form_lv1_employee', compact('diklat', 'questions', 'hash_slug', 'hasSubmitted', 'employee', 'participant'));
        } else {
            // For direct access (not logged in), show the form with employee verification step
            return view('scoring.form_lv1_employee', compact('diklat', 'questions', 'hash_slug'));
        }
    }

    public function postSubmit(Request $request, $hash_slug) {
        // Base validation for all form submissions
        $validationRules = [
            'question.*' => 'required|array',
            'question.*.scoring_lv1_question_id' => 'required',
            'question.*.score' => 'required|in:1,2,3,4,5',
        ];
        
        // Additional validation for non-authenticated users
        if (!Auth::check() && !Auth::guard('employee')->check()) {
            $validationRules['nip'] = 'required';
            $validationRules['birth_date'] = 'required|date';
        }
        
        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $diklat = Diklat::where('slug', decrypt_custom($hash_slug))->first();
            if (!$diklat) {
                return response()->json(['errors' => ['Diklat not found']], 404);
            }

            // Get employee - either from auth or from submitted NIP/birth_date
            $employee = null;
            
            // Check for user authentication (either standard user or employee)
            if (Auth::check() && Auth::user()->role_id == 5) {
                // From authenticated user
                $employee = Employee::where('email', Auth::user()->email)->first();
            } else if (Auth::guard('employee')->check()) {
                // From employee auth
                $employee = Auth::guard('employee')->user();
            } else if ($request->has('nip') && $request->has('birth_date')) {
                // From form data for direct link access
                \Log::info('Direct link access - NIP: ' . $request->nip . ', Birth Date: ' . $request->birth_date);
                $employee = Employee::where('nip', $request->nip)
                    ->where('birth_date', $request->birth_date)
                    ->first();
                
                if (!$employee) {
                    \Log::warning('Employee not found with NIP: ' . $request->nip . ' and Birth Date: ' . $request->birth_date);
                    // Check if employee exists with just NIP
                    $empWithNip = Employee::where('nip', $request->nip)->first();
                    if ($empWithNip) {
                        \Log::warning('Employee found with NIP but birth date mismatch. DB birth_date: ' . $empWithNip->birth_date);
                    }
                }
            }
            
            if (!$employee) {
                return response()->json(['errors' => ['Employee not found or invalid credentials']], 404);
            }

            $participant = $diklat->participants()->where('employee_id', $employee->id)->first();
            if (!$participant) {
                return response()->json(['errors' => ['You are not registered for this training program']], 404);
            }

            $scorring = ScoringLv1::where('diklat_participant_id', $participant->id)
                ->first();
            if ($scorring) {
                return response()->json(['errors' => ['You have already submitted this form']], 422);
            }

            $questions = $request->question;
            foreach ($questions as &$question) {
                $scoringLv1Question = ScoringLv1Question::find($question['scoring_lv1_question_id']);
                if (!$scoringLv1Question) {
                    return response()->json(['errors' => ['Scoring question not found']], 404);
                }

                $question['diklat_participant_id'] = $participant->id;
                $question['created_at'] = now();
                $question['updated_at'] = now();
            }

            ScoringLv1::insert($questions);

            return response()->json([
                'status' => 200,
                'message' => 'Form submitted successfully',
                'data' => $questions
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'errors' => ['An error occurred while submitting the form: ' . $e->getMessage()]
            ], 500);
        }
    }

    public function postSendReminder($id)
    {
        try {
            $diklat = Diklat::findOrFail($id);
            $hashDiklat = encrypt_custom($diklat->slug);
            $baseUrl = url('form/lv1/' . $hashDiklat);

            // Get participants who haven't submitted scoring
            $participants = DiklatParticipant::where('diklat_id', $id)
                ->whereNotExists(function ($query) {
                    $query->select('id')
                          ->from('scoring_lv1s')
                          ->whereRaw('scoring_lv1s.diklat_participant_id = diklat_participants.id');
                })
                ->get();

            $sentCount = 0;
            foreach ($participants as $participant) {
                if ($participant->employee && $participant->employee->email) {
                    Mail::to($participant->employee->email)
                        ->send(new ScoringLv1Reminder($participant, $diklat, $baseUrl));
                    $sentCount++;
                }
            }

            return response()->json([
                'status' => 200,
                'message' => "Reminder sent to {$sentCount} participants",
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Error sending reminders',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
