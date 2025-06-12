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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            
            // Company Information
            $table->string('company_name');
            $table->string('abn', 11)->nullable(); // Australian Business Number
            $table->string('website')->nullable();
            $table->text('company_description')->nullable();
            $table->string('logo_path')->nullable();
            $table->date('established_date')->nullable();
            
            // Contact Person Information
            $table->string('first_name');
            $table->string('last_name');
            $table->string('position')->nullable(); // e.g., "Sales Manager", "Owner"
            $table->string('email')->unique();
            $table->string('phone');
            
            // Authentication
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('remember_token')->nullable();
            $table->timestamp('last_login_at')->nullable();
            
            // Company Address (Australian format)
            $table->string('street_number')->nullable();
            $table->string('street_name')->nullable();
            $table->string('street_type')->nullable();
            $table->string('unit_number')->nullable();
            $table->string('suburb')->nullable();
            $table->string('state', 3)->nullable();
            $table->string('postcode', 4)->nullable();
            
            // Business Details
            $table->json('specialty_areas')->nullable(); // e.g., ["aluminium frames", "timber frames"]
            $table->text('certifications')->nullable();
            $table->decimal('minimum_order_value', 10, 2)->nullable();
            $table->integer('lead_time_days')->nullable(); // typical lead time
            
            // Status and Verification
            $table->boolean('is_active')->default(true);
            $table->boolean('is_verified')->default(false); // admin verification
            $table->enum('onboarding_status', ['pending', 'documents_required', 'under_review', 'approved', 'rejected'])->default('pending');
            
            // Additional Information
            $table->text('admin_notes')->nullable();
            $table->json('contact_preferences')->nullable(); // preferred contact methods/times
            
            $table->timestamps();
            
            // Indexes
            $table->index(['is_active', 'is_verified']);
            $table->index('onboarding_status');
            $table->index(['state', 'suburb']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
