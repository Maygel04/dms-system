<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('documents', function ($table) {
        if (!Schema::hasColumn('documents', 'document_name')) {
            $table->string('document_name')->nullable()->after('file_name');
        }

        if (!Schema::hasColumn('documents', 'year_uploaded')) {
            $table->year('year_uploaded')->nullable()->after('document_name');
        }

        if (!Schema::hasColumn('documents', 'file_path')) {
            $table->string('file_path')->nullable()->after('year_uploaded');
        }
    });
}

public function down()
{
    Schema::table('documents', function ($table) {
        $table->dropColumn(['document_name', 'year_uploaded', 'file_path']);
    });
}
};
