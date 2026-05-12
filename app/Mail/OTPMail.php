<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;

class OTPMail extends Mailable
{
    use Queueable;

    public $otp;
    public $firstName;

    public function __construct($otp, $firstName)
    {
        $this->otp = $otp;
        $this->firstName = $firstName;
    }

    public function build()
    {
        return $this->subject('Campus Connect Verification Code')
            ->html("
                <div style='background:#f4f7fb;padding:40px 20px;font-family:Arial,sans-serif;'>

                    <div style='max-width:600px;margin:auto;background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 12px rgba(0,0,0,0.08);'>

                        <!-- Header -->
                        <div style='background:linear-gradient(135deg,#1e3a8a,#2563eb);padding:30px;text-align:center;color:white;'>

                            <div style='font-size:32px;font-weight:bold;letter-spacing:1px;'>
                                Campus Connect
                            </div>

                            <p style='margin-top:10px;font-size:15px;opacity:0.9;'>
                                Secure Email Verification
                            </p>
                        </div>

                        <!-- Body -->
                        <div style='padding:40px 30px;'>

                            <h2 style='margin-top:0;color:#111827;'>
                                Hello {$this->firstName},
                            </h2>

                            <p style='font-size:16px;color:#4b5563;line-height:1.7;'>
                                Use the verification code below to complete your sign in to
                                <strong>Campus Connect</strong>.
                            </p>

                            <!-- OTP BOX -->
                            <div style='margin:35px 0;text-align:center;'>

                                <div style='display:inline-block;
                                            background:#eff6ff;
                                            border:2px dashed #2563eb;
                                            padding:20px 40px;
                                            border-radius:12px;
                                            font-size:38px;
                                            letter-spacing:10px;
                                            font-weight:bold;
                                            color:#1e3a8a;'>

                                    {$this->otp}

                                </div>

                            </div>

                            <p style='font-size:15px;color:#6b7280;line-height:1.6;'>
                                This OTP will expire in
                                <strong>10 minutes</strong>.
                            </p>

                            <p style='font-size:14px;color:#dc2626;margin-top:30px;'>
                                If you did not request this code, please ignore this email.
                            </p>

                        </div>

                        <!-- Footer -->
                        <div style='background:#f9fafb;padding:20px;text-align:center;border-top:1px solid #e5e7eb;'>

                            <p style='margin:0;font-size:13px;color:#6b7280;'>
                                © ".date('Y')." Campus Connect. All rights reserved.
                            </p>

                        </div>

                    </div>

                </div>
            ");
    }
}