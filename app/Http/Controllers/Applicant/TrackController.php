<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TrackController extends Controller
{

    public function index()
    {

        $user = Auth::user();

        /* ===== GET LATEST APPLICATION ===== */

        $app = DB::table('applications')
            ->where('applicant_id',$user->id)
            ->latest()
            ->first();

        if(!$app){
    return view('applicant.track',[
        'app'=>null,
        'remarks'=>[],
        'mpdo'=>['status'=>'pending','label'=>'⏳ Pending'],
        'meo'=>['status'=>'pending','label'=>'⏳ Pending'],
        'bfp'=>['status'=>'pending','label'=>'⏳ Pending'],
        'mpdoAmt'=>0,
        'meoAmt'=>0,
        'bfpAmt'=>0,
        'total'=>0,
        'isIssuedMEO'=>0,
        'mpdoPaid'=>0,
        'meoPaid'=>0,
        'bfpPaid'=>0,
        'bfpHasDocs'=>false,
        'mpdoRemark'=>null,
        'meoRemark'=>null,
        'bfpRemark'=>null,
        'mpdoReuploaded'=>false,
        'meoReuploaded'=>false,
        'bfpReuploaded'=>false,
        'mpdoCanReupload'=>false,
        'meoCanReupload'=>false,
        'bfpCanReupload'=>false
    ]);
}

        $app_id = $app->id;

        /* ===== REMOVE OLD REMARKS IF REUPLOADED ===== */

        $departments = ['mpdo','meo','bfp'];

        foreach($departments as $dept){

            $uploadCount = DB::table('documents')
                ->where('application_id',$app_id)
                ->whereRaw('LOWER(department) = ?', [$dept])
                ->count();

            if($uploadCount > 1){

                DB::table('remarks')
                    ->where('application_id',$app_id)
                    ->where('department',$dept)
                    ->delete();
            }
        }

        /* ===== GET REMARKS ===== */

        $remarks = [];

        $rawRemarks = DB::table('remarks')
            ->where('application_id',$app_id)
            ->get();

        foreach($rawRemarks as $r){

            $dept = strtolower($r->department);

            $text = $r->remarks ?? $r->remark ?? null;

            if(!$text){
                continue;
            }

            $uploadCount = DB::table('documents')
                ->where('application_id',$app_id)
                ->whereRaw('LOWER(department) = ?',[$dept])
                ->count();

            if($uploadCount <= 1){
                $remarks[$dept] = $text;
            }
        }

        /* ===== DIRECT REMARK VARIABLES ===== */

        $mpdoRemark = $remarks['mpdo'] ?? null;
        $meoRemark  = $remarks['meo'] ?? null;
        $bfpRemark  = $remarks['bfp'] ?? null;

        /* ===== CHECK REUPLOAD ===== */

        $mpdoUploadCount = DB::table('documents')
            ->where('application_id',$app_id)
            ->where('department','mpdo')
            ->count();

        $meoUploadCount = DB::table('documents')
            ->where('application_id',$app_id)
            ->where('department','meo')
            ->count();

        $bfpUploadCount = DB::table('documents')
            ->where('application_id',$app_id)
            ->where('department','bfp')
            ->count();

        $mpdoReuploaded = $mpdoUploadCount > 1;
        $meoReuploaded  = $meoUploadCount > 1;
        $bfpReuploaded  = $bfpUploadCount > 1;

        if($mpdoReuploaded){
            $mpdoRemark = null;
        }

        if($meoReuploaded){
            $meoRemark = null;
        }

        if($bfpReuploaded){
            $bfpRemark = null;
        }

        /* ===== SHOW REUPLOAD BUTTON ===== */

        $mpdoCanReupload = !empty($mpdoRemark);
        $meoCanReupload  = !empty($meoRemark);
        $bfpCanReupload  = !empty($bfpRemark);

        /* ===== STATUS ===== */

        $mpdo = $this->getStatus($app_id,'mpdo');
        $meo  = $this->getStatus($app_id,'meo');
        $bfp  = $this->getStatus($app_id,'bfp');

        /* ===== ASSESSMENTS ===== */

        $assessments = DB::table('assessments')
            ->where('application_id',$app_id)
            ->pluck('amount','department')
            ->toArray();

        $mpdoAmt = $assessments['mpdo'] ?? 0;
        $meoAmt  = $assessments['meo'] ?? 0;
        $bfpAmt  = $assessments['bfp'] ?? 0;

        $totalFee = ($mpdoAmt ?? 0) + ($meoAmt ?? 0) + ($bfpAmt ?? 0);
$total = $totalFee;

        /* ===== FLAGS ===== */

        $isIssuedMEO = $app->meo_endorsed ?? 0;

        $mpdoPaid = $app->mpdo_paid ?? 0;
        $meoPaid  = $app->meo_paid ?? 0;
        $bfpPaid  = $app->bfp_paid ?? 0;

        $bfpHasDocs = DB::table('documents')
            ->where('application_id',$app_id)
            ->where('department','bfp')
            ->exists();

       $totalFee = ($mpdoAmt ?? 0) + ($meoAmt ?? 0) + ($bfpAmt ?? 0);
$total = $totalFee;

return view('applicant.track',compact(
    'app',
    'remarks',
    'mpdo',
    'meo',
    'bfp',
    'mpdoAmt',
    'meoAmt',
    'bfpAmt',
    'total',
    'totalFee', // ✅ MAO NI IMPORTANTE
    'isIssuedMEO',
    'mpdoPaid',
    'meoPaid',
    'bfpPaid',
    'bfpHasDocs',
    'mpdoRemark',
    'meoRemark',
    'bfpRemark',
    'mpdoReuploaded',
    'meoReuploaded',
    'bfpReuploaded',
    'mpdoCanReupload',
    'meoCanReupload',
    'bfpCanReupload'
));
    }

    /* ===== STATUS LOGIC ===== */

    private function getStatus($app_id,$dept)
    {

        $status = DB::table('applications')
            ->where('id',$app_id)
            ->value($dept.'_status');

        if($status === 'verified' || $status === 'endorsed'){
            return ['status'=>'done','label'=>'✅ Verified'];
        }

        $hasDoc = DB::table('documents')
            ->where('application_id',$app_id)
            ->where('department',$dept)
            ->exists();

        if(!$hasDoc){
            return ['status'=>'pending','label'=>'⏳ Pending'];
        }

        return ['status'=>'review','label'=>'🔍 Under Review'];
    }



    public function receipt($id)
{
    $application = DB::table('applications')
        ->join('users','users.id','=','applications.applicant_id')
        ->where('applications.id',$id)
        ->select('applications.*','users.name')
        ->first();

    $payments = DB::table('assessments')
        ->where('application_id',$id)
        ->select('department','amount','created_at')
        ->get();

    $total = $payments->sum('amount');

    return view('applicant.receipt', compact(
        'application',
        'payments',
        'total'
    ));
}
}