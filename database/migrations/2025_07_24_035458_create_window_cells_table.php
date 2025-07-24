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
        Schema::create('window_cells', function (Blueprint $table) {
            $table->id();
            $table->foreignId('window_id')->constrained()->cascadeOnDelete();
            $table->foreignId('template_cell_id')->constrained()->cascadeOnDelete();
            $table->boolean('open_left')->default(false);
            $table->boolean('open_right')->default(false);
            $table->boolean('open_top')->default(false);
            $table->boolean('open_bottom')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('window_cells');
    }
};
