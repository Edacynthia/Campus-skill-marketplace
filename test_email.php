<?php

require __DIR__.'/vendor/autoload.php';

use Illuminate\Support\Facades\Mail;
use App\Mail\OTPMail;

// Test email sending
try {
    $otp = '123456';
    $firstName = 'Test User';
    
    $mail = new OTPMail($otp, $firstName);
    
    // Test with Laravel's mail system
    Mail::raw('This is a test email from Campus Connect OTP system', function($message) {
        $message->to('your-email@gmail.com') // Replace with your email
                ->subject('Test Email from Campus Connect')
                ->from('your-email@gmail.com', 'Campus Connect');
    });
    
    echo "Test email sent successfully!\n";
    
} catch (Exception $e) {
    echo "Error sending test email: " . $e->getMessage() . "\n";
    echo "Error details: " . $e->getTraceAsString() . "\n";
}
