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
    Schema::create('tracking', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('application_id');
        $table->string('department');
        $table->text('status');
        $table->timestamps();
    });
}
};
