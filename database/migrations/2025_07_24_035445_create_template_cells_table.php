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
        Schema::create('template_cells', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('window_templates')->cascadeOnDelete();
            $table->unsignedInteger('cell_index');
            $table->decimal('x', 5, 4); // e.g. 0.0000–1.0000
            $table->decimal('y', 5, 4);
            $table->decimal('width_ratio', 5, 4);
            $table->decimal('height_ratio', 5, 4);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_cells');
    }
};
