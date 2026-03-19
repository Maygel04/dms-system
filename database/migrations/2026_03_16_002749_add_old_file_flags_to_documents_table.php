<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            if (!Schema::hasColumn('documents', 'is_old')) {
                $table->boolean('is_old')->default(0)->after('file_path');
            }

            if (!Schema::hasColumn('documents', 'uploaded_by_admin')) {
                $table->boolean('uploaded_by_admin')->default(0)->after('is_old');
            }
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            if (Schema::hasColumn('documents', 'uploaded_by_admin')) {
                $table->dropColumn('uploaded_by_admin');
            }

            if (Schema::hasColumn('documents', 'is_old')) {
                $table->dropColumn('is_old');
            }
        });
    }
};