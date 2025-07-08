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
        Schema::create('frame_types', function (Blueprint $table) {
            $table->id();
            $table->string('filename')->unique();
            $table->string('type');
            $table->integer('panels');
            $table->json('config');
            $table->timestamps();

            $table->index(['type']);
            $table->index(['panels']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frame_types');
    }
};
