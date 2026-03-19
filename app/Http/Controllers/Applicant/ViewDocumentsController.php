<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ViewDocumentsController extends Controller
{
   public function index()
{

    $app = DB::table('applications')
        ->where('applicant_id', auth()->id())
        ->latest()
        ->first();

    $mpdoDocs = [];
    $meoDocs = [];
    $bfpDocs = [];

    if($app){

        $mpdoDocs = DB::table('documents')
            ->where('application_id', $app->id)
            ->where('department','mpdo')
            ->get();

        $meoDocs = DB::table('documents')
            ->where('application_id', $app->id)
            ->where('department','meo')
            ->get();

        $bfpDocs = DB::table('documents')
            ->where('application_id', $app->id)
            ->where('department','bfp')
            ->get();

    }

    return view('applicant.view_documents', compact(
        'app',
        'mpdoDocs',
        'meoDocs',
        'bfpDocs'
    ));

}

    /* ================= MARK FILE AS VIEWED ================= */
    public function markViewed($id)
    {
        DB::table('documents')
            ->where('id', $id)
            ->update([
                'viewed' => 1,
                'updated_at' => now()
            ]);

        return response()->json(['success' => true]);
    }
}