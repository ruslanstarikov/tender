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
        Schema::create('tenders', function (Blueprint $table) {
            $table->id();
            // Reference to the customer who created the tender.
            $table->unsignedBigInteger('customer_id');
            // Basic project information.
            $table->string('project_title');
            // Tender status (e.g., pending, onsite_completed, in_progress, completed).
            $table->string('tender_status')->default('pending');
            // Basic property address as provided by the customer.
            $table->string('property_address');
            // Onsite details collected during the visit.
            // Full, detailed address (if different from the basic address).
            $table->string('full_address');
            // Date and time for beginning the work.
            $table->dateTime('work_start_datetime');
            // Date and time for completion of the work.
            $table->dateTime('work_end_datetime');
            // Owner's contact information.
            $table->string('owner_email');
            $table->string('owner_phone');
            // Additional information about entering the premises (e.g., dogs, security systems, locks).
            $table->text('premises_entry_info')->nullable();
            // Details on current system, frame color, number of glass panels, etc.
            $table->text('frame_details')->nullable();
            $table->timestamps();

            // Foreign key constraint: assuming customers are stored in the "users" table.
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenders');
    }
};
