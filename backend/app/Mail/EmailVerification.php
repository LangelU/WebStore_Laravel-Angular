<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    public $verificationCode;

    public function __construct(VerificationCode $verificationCode) {
        $this->verificationCode = $verificationCode;
    }

    public function build() {
        return $this->view('email_verification', compact());
    }
}
