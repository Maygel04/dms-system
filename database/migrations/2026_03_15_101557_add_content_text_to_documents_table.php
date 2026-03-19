<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function applications(Request $request)
{
    $search = $request->search;

    $applications = \App\Models\Application::join('users', 'applications.applicant_id', '=', 'users.id')
        ->leftJoin('documents', 'applications.id', '=', 'documents.application_id')
        ->where('documents.department', 'mpdo')
        ->select('applications.*', 'users.name')
        ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                  ->orWhere('documents.file_name', 'like', "%{$search}%")
                  ->orWhere('documents.content_text', 'like', "%{$search}%");
            });
        })
        ->distinct()
        ->orderByDesc('applications.id')
        ->get();

    return view('mpdo.applications', compact('applications'));
}
};
