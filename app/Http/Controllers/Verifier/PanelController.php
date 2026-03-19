<?php

namespace App\Http\Controllers\Verifier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PanelController extends Controller
{

    /* ================= APPLICATIONS ================= */
    public function applications($dept)
    {
        $apps = DB::table('applications as a')
            ->join('users as u','u.id','=','a.applicant_id')
            ->select('a.id','u.name','a.created_at')
            ->orderByDesc('a.id')
            ->get();

        return view("verifier.applications", compact('apps','dept'));
    }


    /* ================= NOTIFICATIONS ================= */
    public function notifications($dept)
    {
        $notifications = DB::table('documents')
            ->where('department',$dept)
            ->latest()
            ->limit(30)
            ->get();

        return view("verifier.notifications", compact('notifications','dept'));
    }


    /* ================= REPORTS ================= */
    public function reports($dept)
    {
        $total = DB::table('documents')
            ->where('department',$dept)
            ->count();

        $verified = DB::table('applications')
            ->where($dept.'_status','verified')
            ->count();

        $pending = DB::table('applications')
            ->where($dept.'_status','pending')
            ->count();

        return view("verifier.reports", compact('total','verified','pending','dept'));
    }


    /* ================= PAYMENTS ================= */
    public function payments($dept)
    {
        $payments = DB::table('payments')
            ->where('department',$dept)
            ->latest()
            ->get();

        $total = DB::table('payments')
            ->where('department',$dept)
            ->sum('amount');

        return view("verifier.payments", compact('payments','total','dept'));
    }

}