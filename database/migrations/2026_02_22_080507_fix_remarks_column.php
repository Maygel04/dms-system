<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('remarks')) {

            Schema::table('remarks', function (Blueprint $table) {
                if (!Schema::hasColumn('remarks', 'remarks')) {
                    $table->text('remarks')->nullable();
                }
            });

        } else {

            Schema::create('remarks', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('application_id')->nullable();
                $table->string('department')->nullable();
                $table->text('remarks')->nullable();
                $table->timestamps();
            });

        }
    }

    public function down(): void
    {
        Schema::dropIfExists('remarks');
    }
};