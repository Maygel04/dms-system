<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;

class UploadMeoController extends Controller
{

    /* ================= PAGE ================= */
    public function index(Request $request)
{
    $user = Auth::user();

    $app = DB::table('applications')
        ->where('applicant_id', $user->id)
        ->latest()
        ->first();

    if(!$app){
        return view('applicant.upload_meo',[
            'app'=>null,
            'uploaded'=>false,
            'remark'=>null,
            'verified'=>false,
            'status'=>'pending'
        ]);
    }

    $app_id = $app->id;

    /* CHECK IF DOCUMENTS EXIST */
    $docExists = DB::table('documents')
        ->where('application_id', $app_id)
        ->where('department', 'meo')
        ->exists();

    /* GET LATEST REMARK */
    $remark = DB::table('remarks')
        ->where('application_id', $app_id)
        ->where('department', 'meo')
        ->latest()
        ->value('remark');

    $hasRemark = !empty($remark);

    /* GET STATUS */
    $status = $app->meo_status ?? 'pending';

    $verified = $status === 'verified';

    /*
    LOGIC
    - no documents → allow upload
    - has remark → allow reupload
    - verified → hide upload
    */

   $uploaded = false;

if($docExists && !$hasRemark){
    $uploaded = true;
}

    return view('applicant.upload_meo', [
        'app' => $app,
        'uploaded' => $uploaded,
        'remark' => $remark,
        'verified' => $verified,
        'status' => $status
    ]);


}



    /* ================= STORE ================= */
public function store(Request $request)
{

    // CHECK: at least one file must be uploaded
    if (
        !$request->hasFile('architectural_plan') &&
        !$request->hasFile('structural_plan') &&
        !$request->hasFile('electrical_plan') &&
        !$request->hasFile('plumbing_plan') &&
        !$request->hasFile('mechanical_plan') &&
        !$request->hasFile('bill_materials') &&
        !$request->hasFile('engineer_plan') &&
        !$request->hasFile('construction_schedule') &&
        !$request->hasFile('electronics_plan')
    ){
        return back()->with('error','Please upload at least one document.');
    }

    // validation
    $request->validate([
      
        'architectural_plan' => 'file|mimes:pdf,dwg,dxf|max:25600',
        'structural_plan' => 'file|mimes:pdf,dwg,dxf|max:25600',
        'electrical_plan' => 'file|mimes:pdf,dwg,dxf|max:25600',
        'plumbing_plan' => 'file|mimes:pdf,dwg,dxf|max:25600',
        'mechanical_plan' => 'file|mimes:pdf,dwg,dxf|max:25600',
        'bill_materials' => 'file|mimes:pdf,dwg,dxf|max:25600',
        'engineer_plan' => 'file|mimes:pdf,dwg,dxf|max:25600',
        'construction_schedule' => 'file|mimes:pdf,dwg,dxf|max:25600',
        'electronics_plan' => 'file|mimes:pdf,dwg,dxf|max:25600'
    ]);

    $user = auth()->user();

    $application = DB::table('applications')
        ->where('applicant_id',$user->id)
        ->first();

    if(!$application){

        $application_id = DB::table('applications')->insertGetId([
            'applicant_id' => $user->id,
            'meo_status' => 'pending',
            'created_at' => now(),
            'updated_at' => now()
        ]);

    }else{

        $application_id = $application->id;

    }


    $documents = [
    
        'architectural_plan',
        'structural_plan',
        'electrical_plan' ,
        'plumbing_plan',
        'mechanical_plan',
        'bill_materials',
        'engineer_plan',
        'construction_schedule',
        'electronics_plan'
];


   foreach ($documents as $doc) {

    if ($request->hasFile($doc)) {
$file = $request->file($doc);

$extension = $file->getClientOriginalExtension();

$filename = $doc . '_' . time() . '.' . $extension;

$path = $file->storeAs('meo_docs', $filename, 'public');

$fileName = basename($path);


/* ===== EXTRACT TEXT FROM PDF ===== */

$fileText = null;

if(strtolower($extension) == "pdf"){

    $parser = new Parser();

    $pdf = $parser->parseFile(storage_path('app/public/'.$path));

    $fileText = $pdf->getText();
    


    if(empty($fileText)){
        $fileText = $fileName;
    }


}


/* ===== SAVE DOCUMENT ===== */

DB::table('documents')->insert([

    'application_id' => $application_id,
    'department' => 'meo',

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
        ->where('department','meo')
        ->delete();


    /* RESET STATUS */
    DB::table('applications')
        ->where('id',$application_id)
        ->update([
            'meo_status' => 'pending',
            'updated_at' => now()
        ]);


    return redirect()->back()->with('success','MEO documents submitted successfully.');
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
                'department' => 'meo'
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

