<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('device_photos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_sheet_id');
            $table->foreign('job_sheet_id')->references('id')->on('job_sheets')->onDelete('cascade');
            $table->string('photo_path');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('device_photos');
    }
};
