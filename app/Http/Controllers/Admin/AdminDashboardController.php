<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;


class AdminDashboardController extends Controller
{

    /* ================= ADMIN DASHBOARD ================= */
    public function index(Request $request)
    {

        $selectedAppId = $request->app_id;
        $dept = $request->dept;
        $search = $request->search;

        /* ================= APPLICATION LIST ================= */

        $applications = DB::table('documents as d')
            ->join('applications as a','a.id','=','d.application_id')
            ->join('users as u','u.id','=','a.applicant_id')
            ->select(
                'a.id as app_id',
                'u.name',
                'd.file_name',
                'd.file_path',
                'd.department',
                'd.created_at'
            )
            ->when($search,function($query) use ($search){

                $search = strtolower($search);

                $query->where(function($q) use ($search){

                    $q->whereRaw('LOWER(u.name) LIKE ?', ["%$search%"])
                      ->orWhereRaw('LOWER(d.department) LIKE ?', ["%$search%"])
                      ->orWhereRaw('LOWER(d.file_name) LIKE ?', ["%$search%"])
                      ->orWhereRaw('LOWER(d.file_text) LIKE ?', ["%$search%"])
                      ->orWhereRaw('LOWER(d.search_text) LIKE ?', ["%$search%"]);

                });

            })
            ->orderBy('d.created_at','desc')
            ->get();



        /* ================= FILE VIEW ================= */

        $files = [];
        $applicant = null;

        if ($selectedAppId && in_array($dept,['mpdo','meo','bfp'])) {

            $files = DB::table('documents as d')
                ->join('applications as a','a.id','=','d.application_id')
                ->join('users as u','u.id','=','a.applicant_id')
                ->where('d.application_id',$selectedAppId)
                ->where('d.department',$dept)
                ->select(
                    'd.file_name',
                    'd.created_at',
                    'd.viewed',
                    'u.name',
                    'u.contact_number',
                    'u.address',
                    'u.gender',
                    'u.occupation',
                    'u.photo'
                )
                ->orderBy('d.id')
                ->get();

            if(count($files) > 0){
                $applicant = $files[0];
            }
        }



        /* ================= DASHBOARD STATS ================= */

        $mpdoVerified = DB::table('applications')
            ->where('mpdo_status','verified')
            ->count();

        $meoVerified = DB::table('applications')
    ->where(function($q){
        $q->where('meo_status','verified')
          ->orWhere('meo_endorsed',1);
    })
    ->count();

        $bfpVerified = DB::table('applications')
            ->where('bfp_status','verified')
            ->count();

        $pending = DB::table('applications')
            ->where(function($q){
                $q->where('mpdo_status','!=','verified')
                  ->orWhere('meo_status','!=','verified')
                  ->orWhere('bfp_status','!=','verified');
            })
            ->count();



        /* ================= PAYMENT SUMMARY ================= */

        $mpdoTotal = DB::table('payments')
            ->where('department','mpdo')
            ->sum('amount');

        $meoTotal = DB::table('payments')
            ->where('department','meo')
            ->sum('amount');

        $bfpTotal = DB::table('payments')
            ->where('department','bfp')
            ->sum('amount');

        $totalRevenue = $mpdoTotal + $meoTotal + $bfpTotal;



        return view('admin.dashboard', compact(
            'applications',
            'files',
            'applicant',
            'selectedAppId',
            'dept',
            'mpdoVerified',
            'meoVerified',
            'bfpVerified',
            'pending',
            'mpdoTotal',
            'meoTotal',
            'bfpTotal',
            'totalRevenue'
        ));

    }



    /* ================= ADMIN APPLICATION PAGE ================= */

    public function applications()
    {
        return view('admin.applications');
    }



    /* ================= ADMIN UPLOAD OLD FILES ================= */

public function uploadOld(Request $request)
{
    $request->validate([
        'applicant_name' => 'required|string|max:255',
        'department'     => 'required|in:mpdo,meo,bfp',
        'document_name'  => 'required|string|max:255',
        'file'           => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        'year_uploaded'  => 'nullable|digits:4'
    ]);

    $user = DB::table('users')
        ->where('name', $request->applicant_name)
        ->where('role', '!=', 'admin')
        ->first();

    if (!$user) {
        return back()->with('error', 'Applicant not found.');
    }

    $file = $request->file('file');
    $originalName = time() . '_' . $file->getClientOriginalName();

    $folder = match ($request->department) {
        'mpdo' => 'mpdo_docs',
        'meo'  => 'meo_docs',
        'bfp'  => 'bfp_docs',
    };

    $file->storeAs('public/' . $folder, $originalName);

    $application = DB::table('applications')
        ->where('applicant_id', $user->id)
        ->latest()
        ->first();

    if (!$application) {
        $applicationId = DB::table('applications')->insertGetId([
            'applicant_id' => $user->id,
            'mpdo_status'  => 'pending',
            'meo_status'   => 'pending',
            'bfp_status'   => 'pending',
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);
    } else {
        $applicationId = $application->id;
    }

    DB::table('documents')->insert([
        'application_id'     => $applicationId,
        'department'         => $request->department,
        'file_name'          => $originalName,
        'document_name'      => $request->document_name,
        'year_uploaded'      => $request->year_uploaded,
        'file_path'          => $folder . '/' . $originalName,
        'is_old'             => 1,
        'uploaded_by_admin'  => 1,
        'created_at'         => now(),
        'updated_at'         => now(),
    ]);

    return back()->with('success', 'Old file uploaded successfully.');
}

public function showUploadOldForm()
{
    return view('admin.upload-old');
}

    /* ================= DEPARTMENTS ================= */

    public function departments()
    {

        $applications = DB::table('applications as a')
            ->join('users as u','u.id','=','a.applicant_id')
            ->select(
                'a.id',
                'u.name',
                'a.mpdo_status',
                'a.meo_status',
                'a.bfp_status'
            )
            ->orderBy('a.id','desc')
            ->get();

        return view('admin.department', compact('applications'));

    }



    /* ================= ADMIN VIEW MPDO ================= */

    public function mpdoView()
    {

        $applications = DB::table('applications as a')
            ->join('users as u','u.id','=','a.applicant_id')
            ->select(
                'a.id',
                'u.name',
                'a.mpdo_status',
                'a.meo_status',
                'a.bfp_status',
                'a.created_at'
            )
            ->latest()
            ->get();

        return view('admin.department_mpdo',compact('applications'));

    }



    /* ================= ADMIN VIEW MEO ================= */

    public function meoView()
    {

        $applications = DB::table('applications as a')
            ->join('users as u','u.id','=','a.applicant_id')
            ->select(
                'a.id',
                'u.name',
                'a.mpdo_status',
                'a.meo_status',
                'a.bfp_status',
                'a.created_at'
            )
            ->latest()
            ->get();

        return view('admin.department_meo',compact('applications'));

    }



    /* ================= ADMIN VIEW BFP ================= */

    public function bfpView()
    {

        $applications = DB::table('applications as a')
            ->join('users as u','u.id','=','a.applicant_id')
            ->select(
                'a.id',
                'u.name',
                'a.mpdo_status',
                'a.meo_status',
                'a.bfp_status',
                'a.created_at'
            )
            ->latest()
            ->get();

        return view('admin.department_bfp',compact('applications'));

    }



    /* ================= SYSTEM NOTIFICATIONS ================= */

    public function notifications()
    {

        $notifications = DB::table('remarks')
            ->latest()
            ->limit(30)
            ->get();

        return view('admin.notifications',compact('notifications'));

    }



    /* ================= REPORTS ================= */

    public function reports()
    {

        $totalApplications = DB::table('applications')->count();

        $verified = DB::table('applications')
            ->where('bfp_status','verified')
            ->count();

        $pending = DB::table('applications')
            ->where('bfp_status','!=','verified')
            ->count();

        return view('admin.reports',compact(
            'totalApplications',
            'verified',
            'pending'
        ));

    }



    /* ================= PAYMENTS ================= */


public function payments(Request $request)
{
    $filter = $request->filter ?? 'today';

    if ($filter == 'today') {
        $dateFilter = [Carbon::today(), Carbon::tomorrow()];
    } elseif ($filter == 'week') {
        $dateFilter = [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()];
    } else {
        $dateFilter = [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()];
    }

    $mpdo = DB::table('assessments')
        ->where('department','mpdo')
        ->whereBetween('created_at', $dateFilter)
        ->sum('amount');

    $meo = DB::table('assessments')
        ->where('department','meo')
        ->whereBetween('created_at', $dateFilter)
        ->sum('amount');

    $bfp = DB::table('assessments')
        ->where('department','bfp')
        ->whereBetween('created_at', $dateFilter)
        ->sum('amount');

    $total = $mpdo + $meo + $bfp;

    return view('admin.payments', compact('mpdo','meo','bfp','total','filter'));
}



    /* ================= SYSTEM LOGS ================= */

    public function logs()
    {

        $logs = DB::table('users')
            ->select('name','email','role','created_at')
            ->latest()
            ->limit(50)
            ->get();

        return view('admin.logs',compact('logs'));

    }


public function generateReport()
{
    $today = Carbon::now()->format('F d, Y');

    $mpdo = DB::table('assessments')
        ->where('department','mpdo')
        ->whereDate('created_at', Carbon::today())
        ->sum('amount');

    $meo = DB::table('assessments')
        ->where('department','meo')
        ->whereDate('created_at', Carbon::today())
        ->sum('amount');

    $bfp = DB::table('assessments')
        ->where('department','bfp')
        ->whereDate('created_at', Carbon::today())
        ->sum('amount');

    $total = $mpdo + $meo + $bfp;

    return view('admin.report', compact('mpdo','meo','bfp','total','today'));
}

}