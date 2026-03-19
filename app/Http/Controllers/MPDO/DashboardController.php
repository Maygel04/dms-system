<?php

namespace App\Http\Controllers\MPDO;

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
use Smalot\PdfParser\Parser;
class DashboardController extends Controller
{

    /* ================= DASHBOARD ================= */

    public function index()
    {

        /* TOTAL APPLICATIONS */
        $totalApplications = Application::count();

        /* UNDER REVIEW */
        $underReview = Application::where('mpdo_status','assessed')
            ->orWhere('mpdo_status','pending')
            ->count();

        /* VERIFIED */
        $verified = Application::where('mpdo_status','verified')->count();

        /* REVENUE */
        $revenue = Assessment::where('department','mpdo')
            ->sum('amount');

        return view('mpdo.dashboard',[
            'totalApplications'=>$totalApplications,
            'underReview'=>$underReview,
            'verified'=>$verified,
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

    /* SAVE OR UPDATE ASSESSMENT */
    Assessment::updateOrCreate(
        [
            'application_id' => $request->application_id,
            'department' => 'mpdo'
        ],
        [
            'amount' => $request->amount
        ]
    );

    /* UPDATE APPLICATION STATUS */
  DB::table('applications')
    ->where('id', $request->application_id)
    ->update([
        'mpdo_status' => 'assessed'
    ]);

    return back()->with('success','MPDO Assessment saved successfully.');
}

    /* ================= VERIFY ================= */

public function verify($id)
{
    $application = Application::findOrFail($id);

    $application->mpdo_status = 'verified';
    $application->save();

    Assessment::where('application_id', $id)
        ->where('department', 'mpdo')
        ->update([
            'verified_on' => now()
        ]);

    $user = User::find($application->applicant_id);

    if ($user && !empty($user->email)) {
        try {
            Mail::to($user->email)->send(
                new ApplicationVerifiedMail($application, 'MPDO') // ✅ FIX HERE
            );

            return redirect()->back()->with('verify_success', 'Verification sent successfully and sent to Gmail.');
        } catch (\Exception $e) {
            return redirect()->back()->with('verify_warning', 'Verification sent successfully but not sent to Gmail.');
        }
    }

    return redirect()->back()->with('verify_warning', 'Verification successful but applicant has no Gmail.');
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
        'department' => 'mpdo',
        'remarks' => $request->remark,
        'created_at' => now(),
        'updated_at' => now()
    ]);

    // UPDATE STATUS
    DB::table('applications')
        ->where('id', $request->application_id)
        ->update([
            'mpdo_status' => 'pending'
        ]);

    // GET DATA
    $app = Application::find($request->application_id);
    $user = $app ? User::find($app->applicant_id) : null;

    // CLEAN REMARK
    $cleanRemark = strip_tags($request->remark);

    if ($user && !empty($user->email)) {
        try {

            Mail::to($user->email)->send(
                new RemarkNotification($app, $cleanRemark, 'MPDO') // ✅ FIXED
            );

            return redirect()->back()->with('remark_success', 'Remarks sent successfully and sent to Gmail.');

        } catch (\Exception $e) {

            return redirect()->back()->with('remark_warning', 'Remarks saved but email not sent.');
        }
    }

    return back()->with('warning', 'Remarks saved but applicant has no email.');
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
        'a.mpdo_status'
    )
    ->when($search, function($query) use ($search){

        $query->where(function($q) use ($search){

            $q->where('u.name','LIKE','%'.$search.'%')
              ->orWhere('d.file_name','LIKE','%'.$search.'%');

        });

    })
    ->groupBy('a.id','u.name','a.created_at','a.mpdo_status')
    ->latest()
    ->get();

        $remarks = [];

if($request->app_id){
    $remarks = DB::table('remarks')
        ->where('application_id', $request->app_id)
        ->where('department', 'mpdo')
        ->orderBy('created_at', 'desc')
        ->get();
}

        return view('mpdo.applications',[
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

        return view('mpdo.notifications',compact('notifications'));
    }



    /* ================= REPORTS ================= */

    public function reports()
    {

        $totalApplications = DB::table('applications')->count();

        $verified = DB::table('applications')
            ->where('mpdo_status','verified')
            ->count();

        $pending = DB::table('applications')
            ->where('mpdo_status','pending')
            ->count();

        $total = DB::table('assessments')
            ->where('department','mpdo')
            ->sum('amount');

        return view('mpdo.reports',compact(
            'totalApplications',
            'verified',
            'pending',
            'total'
        ));
    }



    /* ================= PAYMENTS ================= */

    public function payments()
    {

        $payments = DB::table('assessments as a')
            ->join('applications as app','app.id','=','a.application_id')
            ->join('users as u','u.id','=','app.applicant_id')
            ->where('a.department','mpdo')
            ->select(
                'app.id as app_id',
                'u.name',
                'a.amount',
                'a.verified_on'
            )
            ->orderBy('a.created_at','desc')
            ->get();

        $total = $payments->sum('amount');

        return view('mpdo.payments',compact('payments','total'));
    }



    /* ================= RECEIPT ================= */

    public function receipt($id)
    {

        $application = Application::findOrFail($id);

        $applicant = User::find($application->applicant_id);

        $assessment = Assessment::where('application_id',$id)
            ->where('department','mpdo')
            ->first();

        return view('mpdo.receipt',[
            'application'=>$application,
            'applicant'=>$applicant,
            'assessment'=>$assessment
        ]);
    }

    public function store(Request $request)
{

    $request->validate([
        'doc.*' => 'required|file|mimes:pdf,dwg,dxf|max:25600'
    ]);

    $user = auth()->user();

    $application = DB::table('applications')
        ->where('user_id', $user->id)
        ->first();

    if(!$application){
        $application_id = DB::table('applications')->insertGetId([
            'user_id' => $user->id,
            'created_at' => now()
        ]);
    }else{
        $application_id = $application->id;
    }


    if($request->hasFile('doc')){

        foreach($request->file('doc') as $file){

            if($file){

                $path = $file->store('mpdo_docs','public');

                DB::table('documents')->insert([
                    'application_id' => $application_id,
                    'department' => 'mpdo',
                    'file_path' => $path,
                    'created_at' => now()
                ]);

            }

        }

    }
return redirect()->back()->with('success', 'Documents uploaded successfully.');

}

}