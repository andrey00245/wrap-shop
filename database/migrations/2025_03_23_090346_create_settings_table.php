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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('phone')->nullable();
            $table->string('phone_view')->nullable();
            $table->string('phone_aditional')->nullable();
            $table->string('phone_aditional_view')->nullable();
            $table->string('telegram')->nullable();
            $table->string('instagram')->nullable();
            $table->string('email')->nullable();
            $table->json('address')->nullable();
            $table->string('google_map_link')->nullable();
            $table->json('video_banner_title')->nullable();
            $table->json('video_banner_desc')->nullable();
            $table->json('slogan_title')->nullable();
            $table->json('slogan_desc')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::dropIfExists('settings');
}
};
