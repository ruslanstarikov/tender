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
        Schema::create('tender_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            
            // Property Information
            $table->string('property_address');
            $table->string('suburb')->nullable();
            $table->string('state', 3)->nullable();
            $table->string('postcode', 4)->nullable();
            
            // Request Details
            $table->integer('number_of_windows');
            $table->dateTime('appointment_datetime');
            
            // Status and Notes
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->text('customer_notes')->nullable();
            $table->text('admin_notes')->nullable();
            
            // Contact Preference
            $table->enum('preferred_contact_method', ['phone', 'email'])->default('phone');
            
            $table->timestamps();
            
            // Indexes
            $table->index(['customer_id', 'status']);
            $table->index('appointment_datetime');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tender_requests');
    }
};