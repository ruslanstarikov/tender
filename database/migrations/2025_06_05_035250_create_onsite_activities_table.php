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
        Schema::create('onsite_activities', function (Blueprint $table) {
            $table->id();
            // Each onsite activity belongs to one tender.
            $table->unsignedBigInteger('tender_id');
            // Date and time when work begins.
            $table->dateTime('work_start_datetime');
            // Date and time when work ends.
            $table->dateTime('work_end_datetime');
            // Full, detailed address for the property.
            $table->string('full_address');
            // Owner's contact details.
            $table->string('owner_email');
            $table->string('owner_phone');
            // Any notes about entering the premises (dogs, security systems, locks, etc.)
            $table->text('premises_entry_info')->nullable();
            $table->timestamps();

            $table->foreign('tender_id')->references('id')->on('tenders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('onsite_activities');
    }
};
