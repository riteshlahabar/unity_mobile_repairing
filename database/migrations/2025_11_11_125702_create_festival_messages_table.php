<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('festival_messages', function (Blueprint $table) {
            $table->id();
            
            // Campaign identifier
            $table->string('campaign_name')->nullable(); // "Diwali 2025", "New Year"
            
            // Date when campaign was sent
            $table->dateTime('sent_date')->nullable();
            
            // Message content
            $table->longText('message');
            
            // Campaign statistics
            $table->unsignedInteger('total_customers')->default(0);
            $table->unsignedInteger('message_sent')->default(0);
            $table->unsignedInteger('failed_messages')->default(0);
            
            // Overall status
<<<<<<< HEAD
            $table->string('status', 50)->default('pending');
=======
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            
            // Additional response/error details
            $table->json('response_data')->nullable(); // WhatsApp API response
>>>>>>> 0963cebdc0528a837022693382951a181cdac698
            
            // Timestamps
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['sent_date']);
            $table->index(['status']);
            $table->index(['created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('festival_messages');
    }
};
