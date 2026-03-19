<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Smalot\PdfParser\Parser;
class MpdoController extends Controller
{

/* ================= SHOW PAGE ================= */

public function show()
{

    $user = auth()->user();

    $app = DB::table('applications')
        ->where('applicant_id',$user->id)
        ->first();

    if(!$app){

        DB::table('applications')->insert([
            'applicant_id'=>$user->id,
            'mpdo_status'=>'pending',
            'meo_status'=>'pending',
            'bfp_status'=>'pending',
            'created_at'=>now(),
            'updated_at'=>now()
        ]);

        $app = DB::table('applications')
            ->where('applicant_id',$user->id)
            ->first();
    }

    $doc = DB::table('documents')
        ->where('application_id',$app->id)
        ->where('department','mpdo')
        ->first();

    $remark = DB::table('remarks')
        ->where('application_id',$app->id)
        ->where('department','mpdo')
        ->latest()
        ->first();

   $uploaded = false;

/* kung naay document pero walay remark = under review */
if ($doc && !$remark) {
    $uploaded = true;
}

/* kung naay remark = pwede reupload */
if ($remark) {
    $uploaded = false;
}

/* kung walay document = pwede upload */
if (!$doc) {
    $uploaded = false;
}

    return view('applicant.upload_mpdo',[
        'app'=>$app,
        'doc'=>$doc,
        'remark'=>$remark,
        'uploaded'=>$uploaded
    ]);
}


/* ================= SINGLE FILE UPLOAD ================= */

public function upload(Request $request)
{

    $request->validate([
        'doc'=>'required|file|mimes:pdf,dwg,dxf|max:25600'
    ]);

    $user = auth()->user();

    $app = DB::table('applications')
        ->where('applicant_id',$user->id)
        ->first();

    if(!$app){
        return back()->with('error','Application not found.');
    }

    $existingDoc = DB::table('documents')
        ->where('application_id',$app->id)
        ->where('department','mpdo')
        ->first();

    $path = public_path('uploads/mpdo');

    if(!file_exists($path)){
        mkdir($path,0777,true);
    }

    $file = $request->file('doc');

    $clean = preg_replace("/[^a-zA-Z0-9._-]/","_",$file->getClientOriginalName());

    $filename = time().'_'.$clean;

    $file->move($path,$filename);


    /* ===== REUPLOAD ===== */

    if($existingDoc){

    $old = $path.'/'.$existingDoc->file_name;

    if(file_exists($old)){
        unlink($old);
    }

    DB::table('documents')
        ->where('id',$existingDoc->id)
        ->update([
            'file_name'=>$filename,
            'updated_at'=>now()
        ]);

    /* DELETE OLD REMARK */
    DB::table('remarks')
        ->where('application_id',$app->id)
        ->where('department','mpdo')
        ->delete();

    DB::table('applications')
        ->where('id',$app->id)
        ->update([
            'mpdo_status'=>'pending',
            'updated_at'=>now()
        ]);

    return redirect()->route('applicant.track')
        ->with('reuploaded','Re-uploaded successfully');
}




    /* ===== FIRST UPLOAD ===== */

    DB::table('documents')->insert([
        'application_id'=>$app->id,
        'department'=>'mpdo',
        'file_name'=>$filename,
        'created_at'=>now(),
        'updated_at'=>now()
    ]);

    DB::table('applications')
        ->where('id',$app->id)
        ->update([
            'mpdo_status'=>'pending',
            'updated_at'=>now()
        ]);

    return redirect()->route('applicant.track')
        ->with('success','Uploaded successfully');

}


/* ================= MULTIPLE FILE UPLOAD ================= */

public function store(Request $request)
{

    $request->validate([
        'doc.*'=>'required|file|mimes:pdf,dwg,dxf|max:25600'
    ]);

    foreach($request->file('doc') as $file){

        $filename = time().'_'.$file->getClientOriginalName();

        $path = $file->storeAs('uploads/mpdo',$filename,'public');

        $searchText = null;

        if($file->getClientOriginalExtension() == 'pdf'){

            try{

                $parser = new Parser();

                $pdf = $parser->parseFile(storage_path('app/public/'.$path));

                $searchText = $pdf->getText();

            }catch(\Exception $e){
                $searchText = null;
            }

        }

        \App\Models\Document::create([
            'application_id'=>$request->application_id,
            'department'=>'mpdo',
            'file_name'=>$filename,
            'search_text'=>$searchText,
            'created_at'=>now()
        ]);

    }

    DB::table('applications')
        ->where('id',$request->application_id)
        ->update([
            'mpdo_status'=>'pending',
            'updated_at'=>now()
        ]);

    return back()->with('success','Files uploaded successfully.');

}


/* ================= RECEIPT ================= */

public function receipt($id)
{

    $application = DB::table('applications')
        ->where('id',$id)
        ->first();

    $applicant = DB::table('users')
        ->where('id',$application->applicant_id)
        ->first();

    return view('mpdo.receipt',[
        'application'=>$application,
        'applicant'=>$applicant
    ]);

}

}