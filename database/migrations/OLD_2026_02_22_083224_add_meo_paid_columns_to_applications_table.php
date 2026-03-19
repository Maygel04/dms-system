<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {

            if (!Schema::hasColumn('applications', 'meo_endorsed')) {
                $table->boolean('meo_endorsed')->default(0);
            }

            if (!Schema::hasColumn('applications', 'paid')) {
                $table->boolean('paid')->default(0);
            }

        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {

            if (Schema::hasColumn('applications', 'meo_endorsed')) {
                $table->dropColumn('meo_endorsed');
            }

            if (Schema::hasColumn('applications', 'paid')) {
                $table->dropColumn('paid');
            }

        });
    }
};