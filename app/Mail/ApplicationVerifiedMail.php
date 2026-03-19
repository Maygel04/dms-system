<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplicationVerifiedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $department;

    public function __construct($application, $department)
    {
        $this->application = $application;
        $this->department = strtoupper($department); // ✅ ensure uppercase (MPDO/MEO/BFP)
    }

    public function build()
    {
        return $this->subject($this->department . ' Application Verified') // ✅ dynamic subject
                    ->view('emails.application_verified')
                    ->with([
                        'application' => $this->application,
                        'department' => $this->department
                    ]);
    }
}