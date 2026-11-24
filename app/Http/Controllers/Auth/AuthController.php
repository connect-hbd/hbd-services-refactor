<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Services\ContactService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLoginForm(): View|RedirectResponse
    {
       if (Auth::check() && Auth::user()->isActive() && Auth::user()->rolePrefix()) {
            return redirect()->route('role.dashboard', ['role' => Auth::user()->rolePrefix()]);
        }

        return view('backend.pages.auth.signin', [
            'title' => 'Login',
        ]);
    }

    // public function login(Request $request): RedirectResponse
    // {
    //     $credentials = $request->validate([
    //         'email' => ['required', 'email'],
    //         'password' => ['required', 'string'],
    //         'remember' => ['nullable', 'boolean'],
    //     ]);

    //     $remember = (bool) ($credentials['remember'] ?? false);
    //     unset($credentials['remember']);

    //     if (! Auth::attempt($credentials, $remember)) {
    //         throw ValidationException::withMessages([
    //             'email' => __('auth.failed'),
    //         ]);
    //     }

    //     $request->session()->regenerate();

    //     if (! $request->user()->isActive() || ! $request->user()->rolePrefix()) {
    //         Auth::logout();

    //         $request->session()->invalidate();
    //         $request->session()->regenerateToken();

    //         throw ValidationException::withMessages([
    //             'email' => 'Your account is not ready for access. Please contact support.',
    //         ]);
    //     }

    //     return redirect()->route('role.dashboard', [
    //         'role' => $request->user()->rolePrefix(),
    //     ]);
    // }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $remember = (bool) ($credentials['remember'] ?? false);
        unset($credentials['remember']);

        if (!Auth::attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        $user = $request->user();

        if (!$user->isActive() || !$user->rolePrefix()) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'Your account is not ready for access.',
            ]);
        }

        if ($user->rolePrefix() === 'student') {
            return redirect()->route('student.dashboard');
        }

        return redirect()->route('role.dashboard', [
            'role' => $user->rolePrefix(),
        ]);
    }


    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function showRegister()
    {      

        return view('backend.pages.auth.signup');
    }

    public function showCheckIn(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('student.dashboard');
        }

        return view('backend.pages.auth.check-in');
    }
    //    public function register(Request $request)
    // {
    //     $validatedData = $request->validate([
    //          'first_name' => 'required|string|max:100',
    //         'last_name'  => 'required|string|max:100',
    //         'email' => 'required|email|unique:users,email',
    //         'phone' => 'required|string|max:255',
    //         'password' => 'required|string|min:8|confirmed',
    //     ]);
    //     $fullName = trim(
    //         $validatedData['first_name'] . ' ' . $validatedData['last_name']
    //     );

    //     $user = User::create([
    //         'name' => $fullName,
    //         'email' => $validatedData['email'],
    //         'phone' => $validatedData['phone'],
    //         'password' => Hash::make($validatedData['password']),
    //         'status' => 'active',
    //         'primary_role_id' => 4, 
    //     ]);

    //     Student::create([
    //         'user_id' => $user->id,
    //         'student_number' => 'STU-' . str_pad($user->id, 6, '0', STR_PAD_LEFT),
    //          'first_name' => $validatedData['first_name'],
    //         'last_name' => $validatedData['last_name'],
    //     ]);

    //     return redirect()
    //         ->route('login')
    //         ->with('message', 'Registration successful.');
    // }
    public function register(
        Request $request,
        ContactService $contactService
    ) {
        $validatedData = $request->validate([
            'first_name' => [
                'required',
                'string',
                'max:100',
            ],

            'last_name' => [
                'required',
                'string',
                'max:100',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
            ],

            'phone' => [
                'required',
                'string',
                'regex:/^01[0-9]{9}$/',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        /*Normalize primary contacts */

        $phone = preg_replace('/\D/', '', $validatedData['phone']);

        $email = $contactService->normalizeEmail(
            $validatedData['email']
        );

        /*Check phone/email uniqueness everywhere */

        $errors = [];
        if (User::where('phone', $phone)->exists() || \App\Models\ContactPoint::where('type', 'phone')->where('normalized_value', $phone)->exists()) {
            $errors['phone'] = 'This phone number is already registered';
        }
        if (User::whereRaw('LOWER(email) = ?', [$email])->exists() || \App\Models\ContactPoint::where('type', 'email')->whereRaw('LOWER(normalized_value) = ?', [$email])->exists()) {
            $errors['email'] = 'This email is already registered';
        }
        if ($errors) {
            throw ValidationException::withMessages($errors);
        }

        /*Create user + student */

        DB::transaction(function () use (
            $validatedData,
            $phone,
            $email,
        ) {
            $fullName = trim(
                $validatedData['first_name'] . ' ' .
                    $validatedData['last_name']
            );

            /*User */

            $user = User::create([
                'name' => $fullName,

                // Primary contacts
                'email' => $email,
                'phone' => $phone,

                'password' => Hash::make(
                    $validatedData['password']
                ),

                'status' => 'active',

                'primary_role_id' => 4,
            ]);

            /*Student */

            Student::create([
                'user_id' => $user->id,

                'student_number' =>
                'STU-' .
                    str_pad(
                        $user->id,
                        6,
                        '0',
                        STR_PAD_LEFT
                    ),

                'first_name' =>
                $validatedData['first_name'],

                'last_name' =>
                $validatedData['last_name'],
            ]);
        });

        return redirect()
            ->to('/login')
            ->with(
                'message',
                'Registration successful.'
            );
    }

    public function checkInRegister(Request $request)
    {
        // 1. Validate input
        $validated = $request->validate([
            'phone' => [
                'nullable',
                'required_without:email',
                'string',
                'regex:/^01[0-9]{9}$/',
            ],
            'email' => [
                'nullable',
                'required_without:phone',
                'email',
                'max:255',
            ],
        ], [
            'phone.required_without' => 'Please enter your phone number or email.',
            'email.required_without' => 'Please enter your phone number or email.',
            'email.email' => 'Please enter a valid email address.',
        ]);

        // 2. Only one contact method allowed
        if (!empty($validated['phone']) && !empty($validated['email'])) {
            throw ValidationException::withMessages([
                'phone' => 'Please enter either phone number or email, not both.',
            ]);
        }

        // 3. Normalize phone
        $phone = null;

        if (!empty($validated['phone'])) {
            $phone = preg_replace('/\D/', '', $validated['phone']);
        }

        // 4. Normalize email
        $email = !empty($validated['email'])
            ? mb_strtolower(trim($validated['email']))
            : null;

        // 5. Find existing user
        $user = null;

        if ($phone !== null) {
            $user = User::where('phone', $phone)->first();
        } elseif ($email !== null) {
            $user = User::where('email', $email)->first();
        }

        // 6. Existing user login
        if ($user) {

            // Check account status
            if (!$user->isActive()) {
                throw ValidationException::withMessages([
                    $phone ? 'phone' : 'email' =>
                    'Your account is inactive. Please contact support.',
                ]);
            }

            // Only student role allowed
            if ((int) $user->primary_role_id !== 4) {
                throw ValidationException::withMessages([
                    $phone ? 'phone' : 'email' =>
                    'You can not login. Please contact admin.',
                ]);
            }

            // Check student profile
            $student = Student::where('user_id', $user->id)->first();

            if (!$student) {
                throw ValidationException::withMessages([
                    $phone ? 'phone' : 'email' =>
                    'Student profile not found. Please contact support.',
                ]);
            }
        } else {

            // 7. Create new user and student
            $user = DB::transaction(function () use ($phone, $email) {

                // Check duplicate phone
                if ($phone && User::where('phone', $phone)->exists()) {
                    throw ValidationException::withMessages([
                        'phone' => 'This phone number is already registered.',
                    ]);
                }

                // Check duplicate email
                if ($email && User::where('email', $email)->exists()) {
                    throw ValidationException::withMessages([
                        'email' => 'This email address is already registered.',
                    ]);
                }

                // Create user
                $user = User::create([
                    'name' => null,
                    'phone' => $phone,
                    'email' => $email,
                    'password' => bcrypt(Str::random(64)),
                    'status' => 'active',
                    'primary_role_id' => 4,
                ]);

                // Create student
                Student::create([
                    'user_id' => $user->id,

                    'student_number' => 'STU-' .
                        str_pad($user->id, 6, '0', STR_PAD_LEFT),
                    'first_name' =>null,

                'last_name' =>null,
                ]);

                return $user;
            });
        }

        // 8. Login without password
        Auth::login($user);

        // 9. Regenerate session
        $request->session()->regenerate();

        // 10. Redirect dashboard
        return redirect()
            ->route('student.dashboard')
            ->with('message', 'Check-in successful. Welcome!');
    }

    public function identifierLogin(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'identifier' => ['required', 'string', 'max:255'],
        ]);
        $identifier = trim($data['identifier']);
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $identifier = mb_strtolower($identifier);
            $user = User::whereRaw('LOWER(email) = ?', [$identifier])->first();
            $field = 'identifier';
        } else {
            $phone = preg_replace('/\D/', '', $identifier);
            if (!preg_match('/^01[0-9]{9}$/', $phone)) {
                throw ValidationException::withMessages(['identifier' => 'Enter a valid email address or Bangladeshi phone number (01XXXXXXXXX).']);
            }
            $user = User::where('phone', $phone)->first();
            $field = 'identifier';
        }
        if (!$user) {
            throw ValidationException::withMessages([$field => 'No account was found with that phone number or email.']);
        }
        if (!$user->isActive()) {
            throw ValidationException::withMessages([$field => 'Your account is inactive. Please contact support.']);
        }
        Auth::login($user, (bool) $request->boolean('remember'));
        $request->session()->regenerate();
        return redirect()->route('student.dashboard');
    }
}
