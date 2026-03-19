<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {

            if (!Schema::hasColumn('applications', 'mpdo_status')) {
                $table->string('mpdo_status')->default('pending');
            }

            if (!Schema::hasColumn('applications', 'meo_status')) {
                $table->string('meo_status')->default('pending');
            }

            if (!Schema::hasColumn('applications', 'bfp_status')) {
                $table->string('bfp_status')->default('pending');
            }

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {

            if (Schema::hasColumn('applications', 'mpdo_status')) {
                $table->dropColumn('mpdo_status');
            }

            if (Schema::hasColumn('applications', 'meo_status')) {
                $table->dropColumn('meo_status');
            }

            if (Schema::hasColumn('applications', 'bfp_status')) {
                $table->dropColumn('bfp_status');
            }

        });
    }
};