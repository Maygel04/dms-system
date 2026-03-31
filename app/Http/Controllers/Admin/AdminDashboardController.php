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

  public function mpdoView(Request $request)
{
    $query = DB::table('applications as a')
        ->join('users as u','u.id','=','a.applicant_id')
        ->select(
            'a.id',
            'u.name',
            'a.mpdo_status',
            'a.created_at'
        );

    /* ================= SEARCH ================= */
    if ($request->search) {
        $query->where('u.name', 'like', '%' . $request->search . '%');
    }

    /* ================= FILTER ================= */
    $filter = $request->filter ?? 'today';

    /* ================= BASE QUERIES ================= */
    $mpdoQuery = DB::table('assessments')->where('department','mpdo');
    $meoQuery  = DB::table('assessments')->where('department','meo');
    $bfpQuery  = DB::table('assessments')->where('department','bfp');

    /* ================= APPLY FILTER ================= */
    if ($filter == 'today') {

        $mpdoQuery->whereDate('created_at', now());
        $meoQuery->whereDate('created_at', now());
        $bfpQuery->whereDate('created_at', now());

    } elseif ($filter == 'week') {

        $mpdoQuery->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        $meoQuery->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        $bfpQuery->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);

    } elseif ($filter == 'month') {

        $mpdoQuery->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
        $meoQuery->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
        $bfpQuery->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);

    } else {

        $mpdoQuery->whereYear('created_at', now()->year);
        $meoQuery->whereYear('created_at', now()->year);
        $bfpQuery->whereYear('created_at', now()->year);

    }

    /* ================= TOTALS ================= */
    $mpdo = $mpdoQuery->sum('amount');
    $meo  = $meoQuery->sum('amount');
    $bfp  = $bfpQuery->sum('amount');

    /* ================= LIST ================= */
    $mpdoList = DB::table('assessments')
    ->join('applications','applications.id','=','assessments.application_id')
    ->join('users','users.id','=','applications.applicant_id')
    ->where('assessments.department','mpdo')
    ->select(
        'users.name',
        'assessments.amount',
        'assessments.created_at'
    )
    ->latest('assessments.created_at')
    ->get();

    $meoList = DB::table('assessments')
    ->join('applications','applications.id','=','assessments.application_id')
    ->join('users','users.id','=','applications.applicant_id')
    ->where('assessments.department','meo')
    ->select(
        'users.name',
        'assessments.amount',
        'assessments.created_at'
    )
    ->latest('assessments.created_at')
    ->get();

    $bfpList = DB::table('assessments')
    ->join('applications','applications.id','=','assessments.application_id')
    ->join('users','users.id','=','applications.applicant_id')
    ->where('assessments.department','bfp')
    ->select(
        'users.name',
        'assessments.amount',
        'assessments.created_at'
    )
    ->latest('assessments.created_at')
    ->get();

    /* ================= TOTAL ================= */
    $total = $mpdo + $meo + $bfp;

    /* ================= RETURN ================= */
    return view('admin.payments', compact(
        'mpdo','meo','bfp',
        'mpdoList','meoList','bfpList',
        'total','filter'
    ));
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

    // ================= DATE FILTER =================
    if ($filter == 'today') {
        $queryDate = fn($q) =>
            $q->whereDate('assessments.created_at', now());
    }

    elseif ($filter == 'week') {
        $queryDate = fn($q) =>
            $q->whereBetween('assessments.created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ]);
    }

    elseif ($filter == 'month') {
        $queryDate = fn($q) =>
            $q->whereMonth('assessments.created_at', now()->month);
    }

    else { // year
        $queryDate = fn($q) =>
            $q->whereYear('assessments.created_at', now()->year);
    }


    // ================= REVENUE =================
    $mpdo = DB::table('assessments')
        ->where('department', 'mpdo')
        ->where($queryDate)
        ->sum('amount');

    $meo = DB::table('assessments')
        ->where('department', 'meo')
        ->where($queryDate)
        ->sum('amount');

    $bfp = DB::table('assessments')
        ->where('department', 'bfp')
        ->where($queryDate)
        ->sum('amount');


    // ================= MPDO LIST =================
   $mpdoList = DB::table('assessments')
    ->join('applications', 'applications.id', '=', 'assessments.application_id')
    ->join('users', 'users.id', '=', 'applications.applicant_id')
    ->where('assessments.department', 'mpdo')
    ->where('applications.mpdo_status', 'verified') // ✅ ADD THIS
    ->where($queryDate)
    ->select(
        'users.name',
        'assessments.amount',
        'assessments.created_at'
    )
    ->latest('assessments.created_at')
    ->get();


    // ================= MEO LIST =================
     $meoList = DB::table('assessments')
    ->join('applications', 'applications.id', '=', 'assessments.application_id')
    ->join('users', 'users.id', '=', 'applications.applicant_id')
    ->where('assessments.department', 'meo')
    ->where($queryDate)
    ->select(
        'users.name',
        'assessments.amount',
        'assessments.created_at'
    )
    ->latest('assessments.created_at')
    ->get();


    // ================= BFP LIST =================
    $bfpList = DB::table('assessments')
    ->join('applications', 'applications.id', '=', 'assessments.application_id')
    ->join('users', 'users.id', '=', 'applications.applicant_id')
    ->where('assessments.department', 'bfp')
    ->where('applications.bfp_status', 'verified') // ✅ ADD
    ->where($queryDate)
    ->select(
        'users.name',
        'assessments.amount',
        'assessments.created_at'
    )
    ->latest('assessments.created_at')
    ->get();


    // ================= TOTAL =================
    $total = $mpdo + $meo + $bfp;


    // ================= RETURN =================
    return view('admin.payments', compact(
        'mpdo',
        'meo',
        'bfp',
        'mpdoList',
        'meoList',
        'bfpList',
        'total',
        'filter'
    ));
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
    $filter = request('filter', 'today');

    // ================= DATE TITLE =================
    if ($filter == 'today') {
        $today = Carbon::now()->format('F d, Y');

    } elseif ($filter == 'week') {
        $today = "Week of " . Carbon::now()->startOfWeek(Carbon::MONDAY)->format('M d') .
                 " - " . Carbon::now()->endOfWeek(Carbon::SUNDAY)->format('M d, Y');

    } elseif ($filter == 'month') {
        $today = Carbon::now()->format('F Y');

    } else {
        $today = Carbon::now()->format('Y');
    }

    // ================= QUERY =================
    $mpdoQuery = DB::table('assessments')->where('department','mpdo');
    $meoQuery  = DB::table('assessments')->where('department','meo');
    $bfpQuery  = DB::table('assessments')->where('department','bfp');

    // ================= APPLY FILTER (FIXED) =================
    if ($filter == 'today') {

        $mpdoQuery->whereDate('created_at', Carbon::today());
        $meoQuery->whereDate('created_at', Carbon::today());
        $bfpQuery->whereDate('created_at', Carbon::today());

    } elseif ($filter == 'week') {

        $mpdoQuery->whereBetween('created_at', [
            Carbon::now()->startOfWeek(Carbon::MONDAY),
            Carbon::now()->endOfWeek(Carbon::SUNDAY)
        ]);

        $meoQuery->whereBetween('created_at', [
            Carbon::now()->startOfWeek(Carbon::MONDAY),
            Carbon::now()->endOfWeek(Carbon::SUNDAY)
        ]);

        $bfpQuery->whereBetween('created_at', [
            Carbon::now()->startOfWeek(Carbon::MONDAY),
            Carbon::now()->endOfWeek(Carbon::SUNDAY)
        ]);

    } elseif ($filter == 'month') {

        $mpdoQuery->whereMonth('created_at', Carbon::now()->month)
                  ->whereYear('created_at', Carbon::now()->year);

        $meoQuery->whereMonth('created_at', Carbon::now()->month)
                 ->whereYear('created_at', Carbon::now()->year);

        $bfpQuery->whereMonth('created_at', Carbon::now()->month)
                 ->whereYear('created_at', Carbon::now()->year);

    } elseif ($filter == 'year') {

        $mpdoQuery->whereYear('created_at', Carbon::now()->year);
        $meoQuery->whereYear('created_at', Carbon::now()->year);
        $bfpQuery->whereYear('created_at', Carbon::now()->year);
    }

    // ================= TOTAL =================
    $mpdo = $mpdoQuery->sum('amount');
    $meo  = $meoQuery->sum('amount');
    $bfp  = $bfpQuery->sum('amount');

    $total = $mpdo + $meo + $bfp;

    return view('admin.report', compact(
        'today',
        'mpdo',
        'meo',
        'bfp',
        'total'
    ));
}
/* ================= VIEW DOCUMENTS ================= */
public function viewApplicantDocs($id)
{
    $documents = DB::table('documents')
        ->where('application_id', $id)
        ->get();

    return view('admin.applicant_documents', compact('documents', 'id'));
}


/* ================= MPDO ================= */
public function mpdoApplications(Request $request)
{
    $query = DB::table('applications as a')
        ->join('users as u','u.id','=','a.applicant_id')
        ->select(
            'a.id',
            'u.name',
            'a.mpdo_status',
            'a.created_at'
        );

    if ($request->search) {
        $query->where('u.name','like','%'.$request->search.'%');
    }

    if ($request->status) {
        $query->where('a.mpdo_status',$request->status);
    }

    $applications = $query->latest('a.created_at')->get();

    return view('admin.department_mpdo', compact('applications'));
}


/* ================= MEO ================= */
public function meoApplications(Request $request)
{
    $query = DB::table('applications as a')
        ->join('users as u','u.id','=','a.applicant_id')
        ->select(
            'a.id',
            'u.name',
            'a.meo_status',
            'a.created_at'
        );

    if ($request->search) {
        $query->where('u.name','like','%'.$request->search.'%');
    }

    if ($request->status) {
        $query->where('a.meo_status',$request->status);
    }

    $applications = $query->latest('a.created_at')->get();

    return view('admin.department_meo', compact('applications'));
}


/* ================= BFP ================= */
public function bfpApplications(Request $request)
{
    $query = DB::table('applications as a')
        ->join('users as u','u.id','=','a.applicant_id')
        ->select(
    'a.id',
    'u.name',
    'a.bfp_status',
    'a.bfp_issued', // ✅ kinahanglan ni
    'a.created_at'
);

    if ($request->search) {
        $query->where('u.name','like','%'.$request->search.'%');
    }

   if ($request->status) {

    if ($request->status == 'issued') {
        $query->where('a.bfp_issued', 1);
    } else {
        $query->where('a.bfp_status', $request->status);
    }

}

    $applications = $query->latest('a.created_at')->get();

    return view('admin.department_bfp', compact('applications'));
}




}