<?php

namespace App\Http\Controllers\MPDO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationVerifiedMail;
use App\Models\User;
use App\Models\Application;

class VerificationController extends Controller
{
    /**
     * VERIFY MPDO DOCUMENTS
     */
    public function verify($id)
    {
        // GET APPLICATION
        $app = Application::find($id);

        if (!$app) {
            return back()->with('error', 'Application not found.');
        }

        // UPDATE STATUS TO VERIFIED
        DB::table('applications')
            ->where('id', $id)
            ->update([
                'mpdo_status' => 'verified',
                'updated_at'  => now()
            ]);

        DB::table('assessments')
            ->where('application_id', $id)
            ->where('department', 'mpdo')
            ->update([
                'verified_on' => now()
            ]);

        // ===== SEND EMAIL TO APPLICANT =====
        $user = User::find($app->applicant_id);

        if ($user && $user->email) {
            try {
                Mail::to($user->email)->send(
                    new ApplicationVerifiedMail($app, 'MPDO')
                );
            } catch (\Exception $e) {
                return back()->with('warning', 'MPDO Verified but Email Failed: ' . $e->getMessage());
            }
        } else {
            return back()->with('warning', 'MPDO Verified but applicant email not found.');
        }

        return back()->with('success', 'MPDO Verified + Email Sent');
    }
}