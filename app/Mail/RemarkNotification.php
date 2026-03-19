<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RemarkNotification extends Mailable
{
    use SerializesModels;

    public $application;
    public $department;
    public $remark;

    public function __construct($application, $remark, $department)
    {
        $this->application = $application;
        $this->remark = $remark;
        $this->department = $department;
    }

    public function build()
    {
        return $this->subject('Building Permit Update') // ✅ CLEAN SUBJECT
                    ->view('emails.remark')
                    ->with([
                        'application' => $this->application,
                        'remark' => $this->remark,
                        'department' => $this->department
                    ]);
    }
}