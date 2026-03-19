<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('applicant_id');

            $table->boolean('mpdo_approved')->default(0);
            $table->boolean('meo_issued')->default(0);
            $table->boolean('bfp_issued')->default(0);
            $table->boolean('paid')->default(0);

            $table->timestamps();

            $table->foreign('applicant_id')
                  ->references('id')
                  ->on('users')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};