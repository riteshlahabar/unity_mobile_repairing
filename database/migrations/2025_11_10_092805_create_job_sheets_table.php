<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('job_sheets', function (Blueprint $table) {
            $table->id();
            $table->string('jobsheet_id')->unique(); // JS0001
            $table->string('customer_id');
            $table->foreign('customer_id')->references('customer_id')->on('customers')->onDelete('cascade');
            
            // Mobile Details
            $table->string('company');
            $table->string('model');
            $table->string('color');
            $table->string('series');
            $table->string('imei', 15)->nullable();
            
            // Problem Details
            $table->text('problem_description');
            $table->boolean('status_dead')->default(false);
            $table->boolean('status_damage')->default(false);
            $table->boolean('status_on')->default(false);
            
            // Accessories
            $table->boolean('accessory_sim_tray')->default(false);
            $table->boolean('accessory_sim_card')->default(false);
            $table->boolean('accessory_memory_card')->default(false);
            $table->boolean('accessory_mobile_cover')->default(false);
            $table->string('other_accessories')->nullable();
            
            // Security
            $table->string('device_password')->nullable();
            $table->text('pattern_image')->nullable(); // Base64 image
            
            // Condition
            $table->enum('device_condition', ['fresh', 'shop_return', 'other']);
            $table->enum('water_damage', ['none', 'lite', 'full']);
            $table->enum('physical_damage', ['none', 'lite', 'full']);
            $table->string('technician')->nullable();
            $table->string('location')->nullable();
            $table->date('delivered_date')->nullable();
            $table->time('delivered_time')->nullable();
            
            // Estimate
            $table->decimal('estimated_cost', 10, 2);
            $table->decimal('advance', 10, 2)->default(0);
            $table->decimal('balance', 10, 2)->default(0);
            $table->text('notes')->nullable();            
            
            // Status
            $table->enum('status', ['in_progress', 'completed', 'delivered'])->default('in_progress');
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('job_sheets');
    }
};
