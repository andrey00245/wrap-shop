<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_pages', function (Blueprint $table) {
            $table->id();
            $table->json('content')->nullable();
            $table->json('seo_text')->nullable();
            $table->timestamps();
        });

        DB::table('home_pages')->insert([
            'id' => 1,
            'content' => null,
            'seo_text' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('home_pages');
    }
};
