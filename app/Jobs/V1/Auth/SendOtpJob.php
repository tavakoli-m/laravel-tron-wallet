<?php

namespace App\Jobs\V1\Auth;

use App\Mail\V1\Auth\SendOtpMail;
use App\Models\Otp;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendOtpJob implements ShouldQueue,ShouldBeEncrypted
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private Otp $otp, private string $email)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->email)->send(new SendOtpMail($this->otp));
    }
}
