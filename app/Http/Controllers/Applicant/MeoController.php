<?php

namespace App\Http\Controllers\MEO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Smalot\PdfParser\Parser;

class MeoController extends Controller
{
    public function show()
    {
        $app = DB::table('applications')
        ->where('applicant_id', auth()->id())
        ->latest()
        ->first();

    $uploaded = DB::table('documents')
        ->where('application_id', $app->id ?? 0)
        ->where('department', 'meo')
        ->count();

    return view('applicant.upload_meo', compact('app','uploaded'));
        $user = auth()->user();

        /* GET APPLICATION */
        $app = DB::table('applications')
    ->where('id', $request->application_id)
    ->update([
        'meo_paid' => 1
    ]);

        if(!$app){
            return redirect('/applicant/dashboard');
        }

        /* CHECK IF ALREADY UPLOADED */
        $uploaded = DB::table('documents')
            ->where('application_id',$app->id)
            ->where('department','meo')
            ->exists();

        return view('applicant.upload_meo',compact('app','uploaded'));
    }


    public function upload(Request $request)
    {
        $user = auth()->user();

        $app = DB::table('applications')
            ->where('applicant_id',$user->id)
            ->first();

        if(!$app){
            return back()->with('error','No application found.');
        }

        /* BLOCK IF ALREADY UPLOADED */
        $exists = DB::table('documents')
            ->where('application_id',$app->id)
            ->where('department','meo')
            ->exists();

        if($exists){
            return back()->with('error','You already uploaded MEO requirements.');
        }

        /* FIND FIRST VALID FILE (same logic as old PHP) */
        $filename = null;

        foreach($request->files as $file){

            if(!$file) continue;

            $request->validate([
                'doc1'=>'nullable|mimes:pdf,dwg,dxf|max:25600',
                'doc2'=>'nullable|mimes:pdf,dwg,dxf|max:25600',
                'doc3'=>'nullable|mimes:pdf,dwg,dxf|max:25600',
                'doc4'=>'nullable|mimes:pdf,dwg,dxf|max:25600',
                'doc5'=>'nullable|mimes:pdf,dwg,dxf|max:25600',
                'doc6'=>'nullable|mimes:pdf,dwg,dxf|max:25600',
                'doc7'=>'nullable|mimes:pdf,dwg,dxf|max:25600',
                'doc8'=>'nullable|mimes:pdf,dwg,dxf|max:25600',
            ]);

            $clean = preg_replace("/[^a-zA-Z0-9._-]/","_",$file->getClientOriginalName());
            $filename = time().'_'.$clean;

            $file->move(public_path('uploads/meo'),$filename);
            break; // SAME FLOW: save only first file
        }

        if(!$filename){
            return back()->with('error','Please upload at least one MEO requirement.');
        }

        /* SAVE DOCUMENT */
        DB::table('documents')->insert([
            'application_id'=>$app->id,
            'department'=>'meo',
            'file_name'=>$filename
        ]);

        /* MOVE TO BFP */
        DB::table('applications')
            ->where('id',$app->id)
            ->update(['bfp_status'=>'pending']);

        return back()->with('success','Successfully uploaded MEO requirements.');
    }



public function markPaid($id)
{
    DB::table('applications')
        ->where('id',$id)
        ->update([
            'meo_paid'=>1,
            'updated_at'=>now()
        ]);

    return back()->with('success','Payment successful');
}

}
