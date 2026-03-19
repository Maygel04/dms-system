<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FileController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | MARK FILE AS VIEWED
    |--------------------------------------------------------------------------
    | Called when user opens the file in modal
    | Updates viewed = 1 in documents table
    */
    public function markViewed($id)
    {
        DB::table('documents')
            ->where('id', $id)
            ->update([
                'viewed' => 1,
                'updated_at' => now()
            ]);

        return response()->json([
            'success' => true
        ]);
    }

}