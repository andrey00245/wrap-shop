<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_blocks', function (Blueprint $table) {
            $table->json('data')->nullable();
        });

        Schema::create('home_block_item_quick_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('home_block_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->json('custom_title')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_block_item_quick_links');

        Schema::table('home_blocks', function (Blueprint $table) {
            $table->dropColumn('data');
        });
    }
};
