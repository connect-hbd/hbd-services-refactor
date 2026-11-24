<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Services\ContactService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{  
    public function checkInRegister(Request $request,ContactService $contactService) {
    $validated = $request->validate([
        'phone' => [
            'nullable',
            'required_without:email',
            'prohibited_with:email',
            'string',
            'max:50',
        ],
        'email' => [
            'nullable',
            'required_without:phone',
            'prohibited_with:phone',
            'email',
            'max:255',
        ],
    ], [
        'phone.required_without' => 'Please enter your phone number or email.',
        'email.required_without' => 'Please enter your phone number or email.',
    ]);

    // Normalize contact
    $phone = !empty($validated['phone'])
        ? $contactService->normalizePhone($validated['phone'])
        : null;

    $email = !empty($validated['email'])
        ? $contactService->normalizeEmail($validated['email'])
        : null;

    // Find existing user
    $user = null;

    if ($phone) {
        $user = User::where('phone', $phone)->first();
    }

    if (!$user && $email) {
        $user = User::where('email', $email)->first();
    }

    // Prevent matching two different accounts
    if ($phone && $email) {
        // Normally unreachable because the validation
        // allows only one contact method.
    }

    // Existing user
    if ($user) {

        if (!$user->isActive() || $user->primary_role_id != 4) {
            throw ValidationException::withMessages([
                'phone' => 'Your account is not available for student check-in.',
            ]);
        }

        // Check student record
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            throw ValidationException::withMessages([
                'phone' => 'Student profile not found. Please contact support.',
            ]);
        }

    } else {

        // New user registration
        $user = DB::transaction(function () use (
            $phone,
            $email,
            $contactService
        ) {

            // Check duplicate primary contacts
            $contactService->validatePrimaryContacts(
                phone: $phone,
                email: $email
            );

            $user = User::create([
                'phone' => $phone,
                'email' => $email,
                'status' => 'active',
                'primary_role_id' => 4,
            ]);

            Student::create([
                'user_id' => $user->id,
                'student_number' => 'STU-' .
                    str_pad($user->id, 6, '0', STR_PAD_LEFT),
            ]);

            return $user;
        });
    }

    // Login without password
    Auth::login($user);

    // Regenerate session
    $request->session()->regenerate();

    return redirect()
        ->route('student.dashboard')
        ->with('message', 'Check-in successful. Welcome!');
}

    public function dashboard()
    {
        $student = auth()->user()->student;
        return view('student.dashboard.index', compact('student'));
    }

}
