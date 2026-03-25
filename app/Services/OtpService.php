<?php

namespace App\Services;

use App\Mail\OtpMail;
use App\Models\Otp;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    public function send(string $email, string $purpose): Otp
    {
        $existingOtp = Otp::findLatestActiveForPurpose($email, $purpose);

        if ($existingOtp && $existingOtp->wasRecentlyCreated()) {
            return $existingOtp;
        }

        $otp = Otp::createForPurpose($email, $purpose);

        Mail::to($email)->send(new OtpMail($otp));

        return $otp;
    }

    public function sendSignupOtp(string $email): Otp
    {
        return $this->send($email, Otp::PURPOSE_SIGNUP);
    }

    public function sendLoginOtp(string $email): Otp
    {
        return $this->send($email, Otp::PURPOSE_LOGIN);
    }
}
