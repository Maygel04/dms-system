<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;

class UploadMpdoController extends Controller
{

    /* ================= PAGE ================= */
public function index(Request $request)
{
    $user = Auth::user();

    $app = DB::table('applications')
        ->where('applicant_id', $user->id)
        ->latest()
        ->first();

    if (!$app) {
        return view('applicant.upload_mpdo', [
            'app' => null,
            'uploaded' => false,
            'remark' => null,
            'verified' => false,
            'status' => 'pending',
            'allowReupload' => false
        ]);
    }

    $app_id = $app->id;

    /* CHECK IF DOCUMENTS EXIST */
    $docExists = DB::table('documents')
        ->where('application_id', $app_id)
        ->where('department', 'mpdo')
        ->exists();

    /* GET LATEST REMARK RECORD */
    $latestRemark = DB::table('remarks')
        ->where('application_id', $app_id)
        ->where('department', 'mpdo')
        ->orderBy('created_at', 'desc')
        ->first();

    $remark = $latestRemark->remarks ?? null;
    $hasRemark = !empty($remark);

    /* GET STATUS */
    $status = $app->mpdo_status ?? 'pending';
    $verified = $status === 'verified';

    /* CHECK IF USER ALREADY RE-UPLOADED AFTER REMARK */
    $hasReuploadAfterRemark = false;

    if ($latestRemark) {
        $hasReuploadAfterRemark = DB::table('documents')
            ->where('application_id', $app_id)
            ->where('department', 'mpdo')
            ->where('created_at', '>', $latestRemark->created_at)
            ->exists();
    }

    /*
    LOGIC
    - no documents = first upload
    - has remark and no reupload yet = allow reupload
    - has documents and no active remark = uploaded
    - verified = hide upload
    */

    $allowReupload = false;
    $uploaded = false;

    if ($verified) {
        $uploaded = true;
        $allowReupload = false;
    } elseif ($hasRemark && !$hasReuploadAfterRemark) {
        $uploaded = false;
        $allowReupload = true;
    } elseif ($docExists) {
        $uploaded = true;
        $allowReupload = false;
    }

    return view('applicant.upload_mpdo', [
        'app' => $app,
        'uploaded' => $uploaded,
        'remark' => $remark,
        'verified' => $verified,
        'status' => $status,
        'allowReupload' => $allowReupload
    ]);
}



    /* ================= STORE ================= */
public function store(Request $request)
{

    // CHECK: at least one file must be uploaded
    if (
        !$request->hasFile('site_development_plan') &&
        !$request->hasFile('tax_declaration') &&
        !$request->hasFile('barangay_construction_certificate') 
        
    ){
        return back()->with('error','Please upload at least one document.');
    }

    // validation
    $request->validate([
        'site_development_plan' => 'file|mimes:pdf,dwg,dxf|max:25600',
        'tax_declaration' => 'file|mimes:pdf,dwg,dxf|max:25600',
        'barangay_construction_certificate' => 'file|mimes:pdf,dwg,dxf|max:25600',
        
    ]);

    $user = auth()->user();

    $application = DB::table('applications')
        ->where('applicant_id',$user->id)
        ->first();

    if(!$application){

        $application_id = DB::table('applications')->insertGetId([
            'applicant_id' => $user->id,
            'mpdo_status' => 'pending',
            'created_at' => now(),
            'updated_at' => now()
        ]);

    }else{

        $application_id = $application->id;

    }


    $documents = [
    'site_development_plan',
    'tax_declaration',
    'barangay_construction_certificate',

];


   foreach ($documents as $doc) {

    if ($request->hasFile($doc)) {
$file = $request->file($doc);

$extension = $file->getClientOriginalExtension();

$filename = $doc . '_' . time() . '.' . $extension;

$path = $file->storeAs('mpdo_docs', $filename, 'public');

$fileName = basename($path);


/* ===== EXTRACT TEXT FROM PDF ===== */

$fileText = null;

if($extension == "pdf"){

    $parser = new Parser();

    $pdf = $parser->parseFile(storage_path('app/public/'.$path));

    $fileText = $pdf->getText();
}


/* ===== SAVE DOCUMENT ===== */

DB::table('documents')->insert([

    'application_id' => $application_id,
    'department' => 'mpdo',

    'file_name' => $fileName,
    'file_path' => $path,

    'file_text' => $fileText,
    'search_text' => $fileText,

    'created_at' => now(),
    'updated_at' => now()

]);
    }

}

    /* DELETE OLD REMARK AFTER REUPLOAD */
    DB::table('remarks')
        ->where('application_id',$application_id)
        ->where('department','mpdo')
        ->delete();


    /* RESET STATUS */
    DB::table('applications')
        ->where('id',$application_id)
        ->update([
            'mpdo_status' => 'pending',
            'updated_at' => now()
        ]);


    return redirect()->back()->with('success','MPDO documents submitted successfully.');
}

    /* ================= SAVE ASSESSMENT ================= */
    public function saveAssessment(Request $request)
    {

        $request->validate([
            'application_id' => 'required|exists:applications,id',
            'amount' => 'required|numeric|min:1'
        ]);

        DB::table('assessments')->updateOrInsert(
            [
                'application_id' => $request->application_id,
                'department' => 'mpdo'
            ],
            [
                'amount' => $request->amount,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        return redirect()->back()->with('success', 'Assessment saved successfully');

    }
    

}

