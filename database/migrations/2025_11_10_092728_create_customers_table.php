<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id')->unique(); // UMR0001 (serial)
            $table->string('full_name');
            $table->text('address');
            $table->string('contact_no', 10)->unique(); // ✅ UNIQUE
            $table->string('alternate_no', 10)->nullable();
            $table->string('whatsapp_no', 10);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
};
