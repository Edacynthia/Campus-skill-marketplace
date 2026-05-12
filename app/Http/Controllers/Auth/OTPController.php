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

    /**
     * Show OTP request form
     */
    public function showRequestForm()
    {
        return view('auth.request-otp');
    }

    /**
     * Show OTP verification form
     */
    public function showVerificationForm(Request $request)
    {
        $email = $request->get('email');
        
        if (!$email) {
            return redirect()->route('login')->with('error', 'Email required for OTP verification.');
        }

        $user = User::where('email', $email)->first();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'User not found.');
        }

        return view('auth.verify-otp', compact('user', 'email'));
    }

    /**
     * Send OTP to user
     */
    public function sendOTP(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        if ($this->otpService->sendOTP($user)) {
           return redirect()->route('otp.verification.form', ['email' => $user->email])
    ->with('success', 'OTP sent to your email. Please check your inbox.');
        } else {
            return back()->with('error', 'Failed to send OTP. Please try again.');
        }
    }

    /**
     * Verify OTP and login user
     */
    public function verifyOTP(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|digits:6',
        ], [
            'otp.required' => 'Please enter the OTP code.',
            'otp.digits' => 'OTP must be exactly 6 numbers.',
        ]);

        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        if ($this->otpService->verifyOTP($user, $request->otp)) {
            // Log the user in
            Auth::login($user);
            $request->session()->regenerate();
            
            return redirect()->intended(route('dashboard'))->with('success', 'Login successful!');
        } else {
            return back()->with('error', 'Invalid or expired OTP. Please try again.');
        }
    }

    /**
     * Check if user needs OTP verification and redirect accordingly
     */
    public function checkOTPRequirement(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.'
            ])->withInput();
        }

        $user = Auth::user();

        if ($this->otpService->needsOTPVerification($user)) {
            Auth::logout();

            return redirect()->route('otp.verification.form', ['email' => $user->email])
                ->with('info', 'Please enter the OTP sent to your email to continue.');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }
}
