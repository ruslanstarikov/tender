<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tender_media', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tender_id');
            // Media type can be a photo, a video, or a 3D polycam model.
            $table->enum('media_type', ['photo', 'video', 'polycam']);
            // Category indicates where or how the media was taken.
            // For polycam models, this would be 'polycam'.
            // For photos and videos, possible categories are: inside, parking, outside, or storage.
            $table->enum('category', ['inside', 'parking', 'outside', 'storage', 'polycam']);
            // File path or URL where the media is stored.
            $table->string('file_path');
            $table->timestamps();

            $table->foreign('tender_id')->references('id')->on('tenders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tender_media');
    }
};
