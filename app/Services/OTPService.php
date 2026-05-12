<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\OTPMail;

class OTPService
{
    /**
     * Generate and send OTP to user email
     */
   public function sendOTP($user)
{
    $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

    $expiresAt = now()->addMinutes(10);

    $user->update([
        'otp_code' => $otp,
        'otp_expires_at' => $expiresAt,
        'otp_verified' => false,
    ]);

    try {
        Mail::to($user->email)->send(new OTPMail($otp, $user->first_name));

        return true;
    } catch (\Exception $e) {
        \Log::error('Failed to send OTP: ' . $e->getMessage());

        return false;
    }
}
    
    /**
     * Verify OTP code
     */
    public function verifyOTP($user, $otp)
    {
        // Check if OTP exists and is not expired
        if (!$user->otp_code || !$user->otp_expires_at || now()->gt($user->otp_expires_at)) {
            return false;
        }
        
        // Verify OTP matches
        if ($user->otp_code !== $otp) {
            return false;
        }
        
        // Mark OTP as verified and clear it
        $user->update([
            'otp_verified' => true,
            'otp_code' => null,
            'otp_expires_at' => null
        ]);
        
        return true;
    }
    
    /**
     * Check if user needs OTP verification
     */
    public function needsOTPVerification($user)
    {
        return !$user->otp_verified && $user->email;
    }
}
