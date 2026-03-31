<?php

namespace App\Http\Controllers\MEO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\Application;
use App\Mail\ApplicationVerifiedMail;
use App\Mail\RemarkNotification;
use App\Models\Document;
use App\Models\User;
use App\Models\Assessment;
use Carbon\Carbon;

class DashboardController extends Controller
{

    /* ================= DASHBOARD ================= */

    public function index()
    {

        $totalApplications = Application::count();

        $underReview = Application::where('meo_status','assessed')
            ->orWhere('meo_status','pending')
            ->count();

        $verified = Application::where('meo_status','verified')->count();

        $endorsed = Application::where('meo_endorsed',1)->count();

        $revenue = Assessment::where('department','meo')->sum('amount');

        return view('meo.dashboard',[
            'totalApplications'=>$totalApplications,
            'underReview'=>$underReview,
            'verified'=>$verified,
            'endorsed'=>$endorsed,
            'revenue'=>$revenue
        ]);
    }


    /* ================= SAVE ASSESSMENT ================= */

public function saveAssessment(Request $request)
{
    $request->validate([
        'application_id' => 'required|exists:applications,id',
        'amount' => 'required|numeric|min:1'
    ]);

    Assessment::updateOrCreate(
        [
            'application_id' => $request->application_id,
            'department' => 'meo'
        ],
        [
            'amount' => $request->amount,
            'updated_at' => now()
        ]
    );

    DB::table('applications')
        ->where('id', $request->application_id)
        ->update([
            'meo_status' => 'assessed',
            'updated_at' => now()
        ]);

    return back()->with('success', 'MEO Assessment saved successfully.');
}


    /* ================= VERIFY ================= */

public function verify($id)
{
    $application = Application::findOrFail($id);

    $application->meo_status = 'verified';
    $application->updated_at = now();
    $application->save();

    Assessment::where('application_id', $id)
        ->where('department', 'meo')
        ->update([
            'verified_on' => now(),
            'updated_at' => now()
        ]);

    $user = User::find($application->applicant_id);

    if ($user && !empty($user->email)) {
        try {
            Mail::to($user->email)
                ->send(new \App\Mail\ApplicationVerifiedMail($application, 'MEO'));

            return back()->with('verify_success', 'MEO verified successfully and sent to Gmail.');
        } catch (\Exception $e) {
            return back()->with('verify_warning', 'MEO verified successfully but not sent to Gmail.');
        }
    }

    return back()->with('verify_warning', 'MEO verified successfully but not sent to Gmail.');
}

    /* ================= SAVE REMARK ================= */

public function saveRemark(Request $request)
{
    $request->validate([
        'application_id' => 'required',
        'remark' => 'required|string'
    ]);

    // SAVE REMARK
    DB::table('remarks')->insert([
        'application_id' => $request->application_id,
        'department' => 'meo',
        'remarks' => $request->remark, // ✅ correct column name
        'created_at' => now(),
        'updated_at' => now()
    ]);

    // UPDATE APPLICATION STATUS
    DB::table('applications')
        ->where('id', $request->application_id)
        ->update([
            'meo_status' => 'pending',
            'updated_at' => now()
        ]);

    // GET APPLICATION + USER
    $app = Application::find($request->application_id);
    $user = $app ? User::find($app->applicant_id) : null;

    // CLEAN REMARK (avoid HTML/garbage)
    $cleanRemark = strip_tags($request->remark);

    if ($user && !empty($user->email)) {
        try {
            Mail::to($user->email)->send(
                new \App\Mail\RemarkNotification($app, $cleanRemark, 'MEO') // ✅ FIXED (3 parameters, correct order)
            );

            return back()->with('remark_success', 'Remark sent to applicant successfully.');

        } catch (\Exception $e) {

            return back()->with('remark_warning', 'Remark saved but email was not sent.');
        }
    }

    return back()->with('remark_warning', 'Remark saved but applicant has no email address.');
}


    /* ================= APPLICATIONS PAGE ================= */

    public function applications(Request $request)
    {

        $search = $request->search;

        $applications = DB::table('applications as a')
            ->join('users as u','u.id','=','a.applicant_id')
            ->leftJoin('documents as d','d.application_id','=','a.id')
            ->select(
                'a.id',
                'u.name',
                'a.created_at',
                'a.meo_status',
                'a.meo_endorsed'
            )
            ->when($search,function($query) use ($search){

                $query->where(function($q) use ($search){

                    $q->where('u.name','LIKE','%'.$search.'%')
                      ->orWhere('d.file_name','LIKE','%'.$search.'%');

                });

            })
            ->groupBy('a.id','u.name','a.created_at','a.meo_status','a.meo_endorsed')
            ->latest()
            ->get();

        $remarks = [];

        if($request->app_id){

            $remarks = DB::table('remarks')
                ->where('application_id',$request->app_id)
                ->where('department','meo')
                ->orderBy('created_at','desc')
                ->get();
        }

        return view('meo.applications',[
            'applications'=>$applications,
            'remarks'=>$remarks
        ]);
    }



    /* ================= NOTIFICATIONS ================= */

    public function notifications()
    {

        $notifications = DB::table('notifications as n')
            ->leftJoin('users as u','u.id','=','n.user_id')
            ->select(
                'n.id',
                'n.type',
                'n.message',
                'n.created_at',
                'u.name'
            )
            ->latest()
            ->limit(50)
            ->get();

        return view('meo.notifications',compact('notifications'));
    }



    /* ================= REPORTS ================= */

    public function report()
{
    $filter = request('filter', 'today');

    if ($filter == 'today') {
        $title = Carbon::now()->format('F d, Y');
    } elseif ($filter == 'week') {
        $title = "Week of " . Carbon::now()->startOfWeek()->format('M d') .
                 " - " . Carbon::now()->endOfWeek()->format('M d, Y');
    } elseif ($filter == 'month') {
        $title = Carbon::now()->format('F Y');
    } else {
        $title = Carbon::now()->format('Y');
    }

    $query = DB::table('assessments')
        ->join('applications','applications.id','=','assessments.application_id')
        ->join('users','users.id','=','applications.applicant_id')
        ->where('assessments.department','meo'); // IMPORTANT

    if ($filter == 'today') {
        $query->whereDate('assessments.created_at', Carbon::today());
    } elseif ($filter == 'week') {
        $query->whereBetween('assessments.created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ]);
    } elseif ($filter == 'month') {
        $query->whereMonth('assessments.created_at', Carbon::now()->month)
              ->whereYear('assessments.created_at', Carbon::now()->year);
    } else {
        $query->whereYear('assessments.created_at', Carbon::now()->year);
    }

    $records = $query->select(
        'users.name',
        'assessments.amount',
        'assessments.created_at'
    )->get();

    $total = $records->sum('amount');

    return view('meo.report', compact('records','total','title'));
}




    /* ================= PAYMENTS ================= */

    public function payments()
    {

        $payments = DB::table('assessments as a')
            ->join('applications as app','app.id','=','a.application_id')
            ->join('users as u','u.id','=','app.applicant_id')
            ->where('a.department','meo')
            ->select(
                'app.id as app_id',
                'u.name',
                'a.amount',
                'a.verified_on'
            )
            ->orderBy('a.created_at','desc')
            ->get();

        $total = $payments->sum('amount');

        return view('meo.payments',compact('payments','total'));
    }



   /* ================= RECEIPT ================= */

public function receipt($id)
{
    // Get application
    $application = Application::findOrFail($id);

    // Get applicant
    $applicant = User::find($application->applicant_id);

    // Get assessment (MEO)
    $assessment = Assessment::where('application_id', $id)
        ->where('department', 'meo')
        ->first();

    // Safe values
    $assessmentAmount = $assessment->amount ?? 0;
    $verifiedOn = $assessment->verified_on ?? null;

    $title = "Official Receipt - MEO"; // ✅ ADD

    return view('meo.receipt', [
        'application' => $application,
        'applicant' => $applicant,
        'assessment' => $assessment,
        'assessmentAmount' => $assessmentAmount,
        'verifiedOn' => $verifiedOn,
        'title' => $title // ✅ PASS
    ]);
}
    


    /* ================= ISSUE ENDORSEMENT ================= */

    public function issueEndorsement(Request $request)
    {

        $id = $request->application_id;

        DB::table('applications')
            ->where('id',$id)
            ->update([
                'meo_status' => 'endorsed',
                'meo_endorsed' => 1,
                'updated_at' => now()
            ]);

        return back()->with('success','Application endorsed to BFP successfully.');
    }

public function markAsPaid(Request $request)
{
    DB::table('applications')
        ->where('id',$request->application_id)
        ->update([
            'meo_paid'=>1,
            'updated_at'=>now()
        ]);

    return back()->with('success','Payment marked as PAID successfully.');
}
}

