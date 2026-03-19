<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Application;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        /* ================= GET APPLICATION ================= */
        $application = Application::where('applicant_id', $user->id)->first();

        /* =====================================================
                IF NO APPLICATION YET
        ====================================================== */
        if (!$application) {
            return view('applicant.dashboard', [
                'user' => $user,
                'application' => null,

                'stepTitle' => 'Step 1',
                'btnLabel' => '📤 Upload MPDO Requirements',
                'btnLink' => '/applicant/upload_mpdo',
                'btnDisabled' => false,

                'mpdoRemark' => null,
                'mpdoDocs' => 0,
                'mpdoStatus' => 'pending',

                'meoRemark' => null,
                'meoDocs' => 0,
                'meoStatus' => 'pending',
                'meoEndorsed' => 0,
                'meoPaid' => 0,

                'bfpRemark' => null,
                'bfpDocs' => 0,
                'bfpStatus' => 'pending',
                'bfpIssued' => 0,
                'bfpPaid' => 0,
            ]);
        }

        /* ================= REMARKS ================= */
        $mpdoRemark = DB::table('remarks')
            ->where('application_id', $application->id)
            ->where('department', 'mpdo')
            ->latest()
            ->value('remark');

        $meoRemark = DB::table('remarks')
            ->where('application_id', $application->id)
            ->where('department', 'meo')
            ->latest()
            ->value('remark');

        $bfpRemark = DB::table('remarks')
            ->where('application_id', $application->id)
            ->where('department', 'bfp')
            ->latest()
            ->value('remark');

        /* ================= DOCUMENT COUNTS ================= */
        $mpdoDocs = DB::table('documents')
            ->where('application_id', $application->id)
            ->where('department', 'mpdo')
            ->count();

        $meoDocs = DB::table('documents')
            ->where('application_id', $application->id)
            ->where('department', 'meo')
            ->count();

        $bfpDocs = DB::table('documents')
            ->where('application_id', $application->id)
            ->where('department', 'bfp')
            ->count();

        /* ================= STATUS ================= */
        $mpdoStatus = $application->mpdo_status ?? 'pending';
        $meoStatus  = $application->meo_status ?? 'pending';
        $bfpStatus  = $application->bfp_status ?? 'pending';

        $meoEndorsed = $application->meo_endorsed ?? 0;
        $meoPaid     = $application->meo_paid ?? 0;

        $bfpIssued   = $application->bfp_issued ?? 0;
        $bfpPaid     = $application->bfp_paid ?? 0;

        /* ================= DEFAULT ================= */
        $stepTitle   = "Waiting";
        $btnLabel    = "⏳ Waiting for verification";
        $btnLink     = "#";
        $btnDisabled = true;

        /* =====================================================
                        FINAL COMPLETED FIRST
        ====================================================== */
        if ($bfpPaid == 1) {
            $stepTitle   = "🎉 Application Completed";
            $btnLabel    = "🎉 All of your requirements have been successfully completed.";
            $btnLink     = "#";
            $btnDisabled = true;
        }

        /* =====================================================
                        BFP ISSUED
        ====================================================== */
        elseif ($bfpIssued == 1 && $bfpPaid == 0) {
            $stepTitle   = "BFP Issued";
            $btnLabel    = "📄 Fire Safety Clearance Issued";
            $btnLink     = "#";
            $btnDisabled = true;
        }

        /* =====================================================
                        BFP VERIFIED
        ====================================================== */
        elseif ($bfpStatus == 'verified' && $bfpIssued == 0) {
            $stepTitle   = "BFP Verified";
            $btnLabel    = "✅ BFP Verified";
            $btnLink     = "#";
            $btnDisabled = true;
        }

        /* =====================================================
                        BFP ASSESSED
        ====================================================== */
        elseif ($bfpStatus == 'assessed') {
            $stepTitle   = "BFP Assessed";
            $btnLabel    = "📝 BFP Assessed";
            $btnLink     = "#";
            $btnDisabled = true;
        }

        /* =====================================================
                        BFP UNDER REVIEW
        ====================================================== */
        elseif ($bfpDocs > 0 && $bfpStatus == 'pending') {
            $stepTitle   = "BFP Under Review";
            $btnLabel    = "⏳ BFP Under Review";
            $btnLink     = "#";
            $btnDisabled = true;
        }

        /* =====================================================
                        STEP 3 - BFP REUPLOAD
        ====================================================== */
        elseif ($meoPaid == 1 && $bfpRemark) {
            $stepTitle   = "BFP Correction";
            $btnLabel    = "⚠ Re-upload BFP Requirements";
            $btnLink     = "/applicant/upload_bfp";
            $btnDisabled = false;
        }

        /* =====================================================
                        STEP 3 - UPLOAD BFP
        ====================================================== */
        elseif ($meoPaid == 1 && $bfpDocs == 0) {
            $stepTitle   = "Step 3";
            $btnLabel    = "📤 Upload BFP Requirements";
            $btnLink     = "/applicant/upload_bfp";
            $btnDisabled = false;
        }

        /* =====================================================
                        MEO PAID / WAITING FOR BFP
        ====================================================== */
        elseif ($meoEndorsed == 1 && $meoPaid == 0) {
            $stepTitle   = "MEO Payment";
            $btnLabel    = "💳 Waiting for MEO Payment";
            $btnLink     = "#";
            $btnDisabled = true;
        }

        /* =====================================================
                        MEO VERIFIED
        ====================================================== */
        elseif ($meoStatus == 'verified' && $meoEndorsed == 0) {
            $stepTitle   = "MEO Verified";
            $btnLabel    = "✅ MEO Verified";
            $btnLink     = "#";
            $btnDisabled = true;
        }

        /* =====================================================
                        MEO ASSESSED
        ====================================================== */
        elseif ($meoStatus == 'assessed') {
            $stepTitle   = "MEO Assessed";
            $btnLabel    = "📝 MEO Assessed";
            $btnLink     = "#";
            $btnDisabled = true;
        }

        /* =====================================================
                        MEO UNDER REVIEW
        ====================================================== */
        elseif ($meoDocs > 0 && $meoStatus == 'pending') {
            $stepTitle   = "MEO Under Review";
            $btnLabel    = "⏳ MEO Under Review";
            $btnLink     = "#";
            $btnDisabled = true;
        }

        /* =====================================================
                        STEP 2 - MEO REUPLOAD
        ====================================================== */
        elseif ($mpdoStatus == 'verified' && $meoRemark) {
            $stepTitle   = "MEO Correction";
            $btnLabel    = "⚠ Re-upload MEO Requirements";
            $btnLink     = "/applicant/upload_meo";
            $btnDisabled = false;
        }

        /* =====================================================
                        STEP 2 - UPLOAD MEO
        ====================================================== */
        elseif ($mpdoStatus == 'verified' && $meoDocs == 0) {
            $stepTitle   = "Step 2";
            $btnLabel    = "📤 Upload MEO Requirements";
            $btnLink     = "/applicant/upload_meo";
            $btnDisabled = false;
        }

        /* =====================================================
                        MPDO VERIFIED
        ====================================================== */
        elseif ($mpdoStatus == 'verified') {
            $stepTitle   = "MPDO Verified";
            $btnLabel    = "✅ MPDO Verified";
            $btnLink     = "#";
            $btnDisabled = true;
        }

        /* =====================================================
                        MPDO ASSESSED
        ====================================================== */
        elseif ($mpdoStatus == 'assessed') {
            $stepTitle   = "MPDO Assessed";
            $btnLabel    = "📝 MPDO Assessed";
            $btnLink     = "#";
            $btnDisabled = true;
        }

        /* =====================================================
                        MPDO UNDER REVIEW
        ====================================================== */
        elseif ($mpdoDocs > 0 && $mpdoStatus == 'pending') {
            $stepTitle   = "MPDO Under Review";
            $btnLabel    = "⏳ Waiting for MPDO Assessment";
            $btnLink     = "#";
            $btnDisabled = true;
        }

        /* =====================================================
                        STEP 1 - MPDO REUPLOAD
        ====================================================== */
        elseif ($mpdoRemark) {
            $stepTitle   = "MPDO Correction";
            $btnLabel    = "⚠ Re-upload MPDO Requirements";
            $btnLink     = "/applicant/upload_mpdo";
            $btnDisabled = false;
        }

        /* =====================================================
                        STEP 1 - UPLOAD MPDO
        ====================================================== */
        elseif ($mpdoDocs == 0) {
            $stepTitle   = "Step 1";
            $btnLabel    = "📤 Upload MPDO Requirements";
            $btnLink     = "/applicant/upload_mpdo";
            $btnDisabled = false;
        }

        /* ================= VIEW ================= */
        return view('applicant.dashboard', compact(
            'user',
            'application',
            'stepTitle',
            'btnLabel',
            'btnLink',
            'btnDisabled',
            'mpdoRemark',
            'mpdoDocs',
            'mpdoStatus',
            'meoRemark',
            'meoDocs',
            'meoStatus',
            'meoEndorsed',
            'meoPaid',
            'bfpRemark',
            'bfpDocs',
            'bfpStatus',
            'bfpIssued',
            'bfpPaid'
        ));
    }
}