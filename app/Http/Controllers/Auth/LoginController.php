<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Diklat;
use App\Models\DiklatParticipant;
use App\Models\Employee;
use App\Models\ScoringLv1;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function loginPost(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Try to authenticate as User
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Check if user is an employee (role_id = 5)
            if (Auth::user()->role_id == 5) {
                $employee = Employee::where('email', $credentials['email'])->first();
                
                // Get all diklats where the employee is a participant
                $availableDiklat = DiklatParticipant::where('employee_id', $employee->id)
                    ->with('diklat')
                    ->get();
                
                if ($availableDiklat->count() > 0) {
                    return redirect()->route('form.lv1.employee.login')
                        ->with('success', 'Login successful!');
                }
                
                return redirect()->route('form.lv1.welcome', ['hash_slug' => 'none'])
                    ->with('info', 'You are not assigned to any training sessions.');
            }
            
            return redirect()->intended('dashboard')->with('success', 'Login successful!');
        }

        // If not a user, try to authenticate as Employee
        if ($this->attemptEmployeeLogin($credentials)) {
            $request->session()->put('is_employee', true);
            
            // Find all Diklat the employee is participating in
            $employee = Employee::where('email', $credentials['email'])->first();
            
            // Get all diklats where the employee is a participant
            $availableDiklat = DiklatParticipant::where('employee_id', $employee->id)
                ->with('diklat')
                ->get();
            
            if ($availableDiklat->count() > 0) {
                return redirect()->route('form.lv1.employee.login')
                    ->with('success', 'Login successful!');
            }
            
            return redirect()->route('form.lv1.welcome', ['hash_slug' => 'none'])
                ->with('info', 'You are not assigned to any training sessions.');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Attempt to log in an employee
     */
    protected function attemptEmployeeLogin(array $credentials)
    {
        $employee = Employee::where('email', $credentials['email'])->first();
        
        if ($employee && Hash::check($credentials['password'], $employee->password)) {
            Auth::guard('employee')->login($employee);
            return true;
        }
        
        return false;
    }

    public function logout(Request $request)
    {
        // Check if user is an employee
        if ($request->session()->has('is_employee')) {
            Auth::guard('employee')->logout();
            $request->session()->forget('is_employee');
        } else {
            Auth::logout();
        }
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')->with('success', 'You have been logged out!');
    }

    public function postCheckEmployee(Request $request, $hash_slug) {
        $validator = Validator::make($request->all(), [
            'nip' => 'required',
            'birth_date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $slug = decrypt_custom($hash_slug);
            
            $employee = Employee::where('nip', $request->nip)
                ->where('birth_date', $request->birth_date)
                ->first();
                
            if (!$employee) {
                return response()->json([
                    'status' => false,
                    'message' => 'NIP or Birth Date is incorrect. Please check and try again.'
                ]);
            }
            
            $diklat = Diklat::where('slug', $slug)->first();
            if (!$diklat) {
                return response()->json([
                    'status' => false,
                    'message' => 'Training program not found'
                ]);
            }
            
            $participant = $diklat->participants()->where('employee_id', $employee->id)->first();

            if ($participant) {
                // Check if employee has already submitted the form
                $hasSubmitted = ScoringLv1::where('diklat_participant_id', $participant->id)->exists();
                
                if ($hasSubmitted) {
                    return response()->json([
                        'status' => false,
                        'message' => 'You have already submitted this assessment form'
                    ]);
                }
                
                return response()->json([
                    'status' => true,
                    'message' => 'Verification successful. You can now complete the assessment.',
                    'data' => $employee
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'You are not registered as a participant for this training program'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred during verification: ' . $e->getMessage()
            ], 500);
        }
    }
}