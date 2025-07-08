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
        Schema::create('tender_frames', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tender_id')->constrained()->onDelete('cascade');
            $table->foreignId('frame_type_id')->constrained()->onDelete('cascade');
            $table->decimal('width', 8, 2);
            $table->decimal('height', 8, 2);
            $table->integer('quantity');
            $table->timestamps();
            
            $table->index(['tender_id']);
            $table->index(['frame_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tender_frames');
    }
};