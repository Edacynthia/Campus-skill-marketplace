<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OTPService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    protected $otpService;

    public function __construct(OTPService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $email = strtolower($request->email);
        $isUniversityEmail = str_ends_with($email, '@edouniversity.edu.ng'); 

        // If user started registration before but did not verify OTP,
        // allow them to register again with the same email.
        $existingUser = User::where('email', $email)->first();

        if ($existingUser && !$existingUser->otp_verified) {
            $existingUser->delete();
        }

        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->where(function ($query) {
                    return $query->where('approval_status', '!=', 'rejected');
                }),
            ],
            'password'   => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ];

        if (!$isUniversityEmail) {
            $rules['passport_photo'] = 'required|image|mimes:jpg,jpeg,png|max:2048';
        }

        $request->validate($rules);

        // Handle Passport Upload
        $passportPath = null;
        if ($request->hasFile('passport_photo')) {
            $passportPath = $request->file('passport_photo')->store('passports', 'public');
        }

        $rejectedUser = User::where('email', $request->email)
    ->where('approval_status', 'rejected')
    ->first();

if ($rejectedUser) {

    if ($rejectedUser->passport_photo &&
        Storage::disk('public')->exists($rejectedUser->passport_photo)) {

        Storage::disk('public')->delete($rejectedUser->passport_photo);
    }

    $rejectedUser->delete();
}

        $user = User::create([
            'first_name'     => $request->first_name,
            'last_name'      => $request->last_name,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'role'           => 'user',
            'is_approved'    => $isUniversityEmail,
            'approval_status' => $isUniversityEmail ? 'approved' : 'pending',
            'passport_photo' => $passportPath,
            'otp_verified'   => false, // Ensure OTP verification is required
        ]);

        $user->assignRole('user');

        if (!$user->is_university_user) {
    Notification::notifyAdmins(
        'new_pending_user',
        'New Pending User Approval',
        $user->first_name . ' ' . $user->last_name . ' is waiting for admin approval.',
        route('admin.users.pending')
    );
}
        
        // Send OTP to the newly registered user
        if ($this->otpService->sendOTP($user)) {
            return redirect()->route('otp.verification.form', ['email' => $user->email])
                ->with('success', 'Account created successfully! Please check your email for OTP verification.')
                ->with('info', 'We sent a 6-digit code to your email. Enter it below to complete registration.');
        } else {
            // If OTP fails to send, still redirect to OTP verification for security
            return redirect()->route('otp.verification.form', ['email' => $user->email])
                ->with('warning', 'Account created successfully! However, we could not send OTP. Please try again or contact support.')
                ->with('info', 'You must verify your email before accessing the dashboard.');
        }
    }
}