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
        Schema::create('video_reviews', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->string('video')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('category_id')->constrained('video_categories')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_reviews');
    }
};
