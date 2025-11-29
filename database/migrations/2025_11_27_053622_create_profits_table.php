<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfitsTable extends Migration
{
    public function up()
    {
        Schema::create('profits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jobsheet_id')->unique();
            $table->decimal('service_charge', 10, 2)->default(0);
            $table->decimal('spare_parts_charge', 10, 2)->default(0);
            $table->decimal('other_charge', 10, 2)->default(0);
            $table->decimal('estimated_cost', 10, 2);
            $table->decimal('profit', 10, 2);
            $table->timestamps();

            $table->foreign('jobsheet_id')->references('id')->on('job_sheets')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('profits');
    }
}
