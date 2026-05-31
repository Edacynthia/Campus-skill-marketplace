<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OTPService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OTPController extends Controller
{
    protected $otpService;

    public function __construct(OTPService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function showRequestForm()
    {
        return view('auth.request-otp');
    }

    public function showVerificationForm(Request $request)
    {
        $email = $request->get('email');

        if (!$email) {
            return redirect()->route('login')
                ->with('error', 'Email required for OTP verification.');
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'User not found.');
        }

        return view('auth.verify-otp', compact('user', 'email'));
    }

    public function sendOTP(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        if ($this->otpService->sendOTP($user)) {
            return redirect()->route('otp.verification.form', ['email' => $user->email])
                ->with('success', 'OTP sent to your email. Please check your inbox.');
        }

        return back()->with('error', 'Failed to send OTP. Please try again.');
    }

    public function verifyOTP(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp'   => 'required|digits:6',
        ], [
            'otp.required' => 'Please enter the OTP code.',
            'otp.digits'   => 'OTP must be exactly 6 numbers.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        if (!$this->otpService->verifyOTP($user, $request->otp)) {
            return back()->with('error', 'Invalid or expired OTP. Please try again.');
        }

        $user->update([
            'otp_verified' => true,
        ]);

        if (!$user->hasUniversityEmail() && !$user->isApproved()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($user->isRejected()) {
                return redirect()->route('login')
                    ->with('error', 'Your account was rejected. You can register again with corrected details.');
            }

            return redirect()->route('approval.pending')
                ->with('info', 'Your account has been created! Please wait for admin approval before you can access the platform.');
        }

        Auth::login($user);
        $request->session()->regenerate();

        if ($user->hasRole('admin') || $user->role === 'admin') {
            return redirect()->route('admin.dashboard')
                ->with('success', 'Welcome, Admin!');
        }

        return redirect()->intended(route('dashboard'))
            ->with('success', 'Login successful! Welcome back.');
    }

    public function checkOTPRequirement(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->withInput();
        }

        $user = Auth::user();

        if ($user->status === 'banned') {
        Auth::logout();

        return redirect()
            ->route('login')
            ->with('error', 'Your account has been banned. Please contact admin.');
    }

    if ($user->status === 'suspended') {
        Auth::logout();

        return redirect()
            ->route('login')
            ->with('error', 'Your account has been suspended temporarily.');
    }

        if (!$user->hasUniversityEmail() && !$user->isApproved()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($user->isRejected()) {
                return redirect()->route('login')
                    ->with('error', 'Your account was rejected. You can register again with corrected details.');
            }

            return redirect()->route('approval.pending')
                ->with('error', 'Your account is still awaiting admin approval. Please check your email for updates.');
        }

        if ($this->otpService->needsOTPVerification($user)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $this->otpService->sendOTP($user);

            return redirect()->route('otp.verification.form', ['email' => $user->email])
                ->with('info', 'A verification code has been sent to your email. Please enter it to continue.');
        }

        $request->session()->regenerate();

        if ($user->hasRole('admin') || $user->role === 'admin') {
            return redirect()->route('admin.dashboard')
                ->with('success', 'Welcome, Admin!');
        }

        return redirect()->intended(route('dashboard'))
            ->with('success', 'Login successful! Welcome back.');
    }
}