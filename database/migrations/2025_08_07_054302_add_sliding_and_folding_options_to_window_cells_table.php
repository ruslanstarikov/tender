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
        Schema::table('window_cells', function (Blueprint $table) {
            // Add sliding options
            $table->boolean('slide_left')->default(false)->after('open_bottom');
            $table->boolean('slide_right')->default(false)->after('slide_left');
            $table->boolean('slide_top')->default(false)->after('slide_right');
            $table->boolean('slide_bottom')->default(false)->after('slide_top');

            // Add folding options
            $table->boolean('folding_left')->default(false)->after('slide_bottom');
            $table->boolean('folding_right')->default(false)->after('folding_left');
            $table->boolean('folding_top')->default(false)->after('folding_right');
            $table->boolean('folding_bottom')->default(false)->after('folding_top');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('window_cells', function (Blueprint $table) {
            // Remove folding options
            $table->dropColumn([
                'folding_bottom',
                'folding_top',
                'folding_right',
                'folding_left'
            ]);

            // Remove sliding options
            $table->dropColumn([
                'slide_bottom',
                'slide_top',
                'slide_right',
                'slide_left'
            ]);
        });
    }
};
