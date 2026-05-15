<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_block_items', function (Blueprint $table) {
            $table->json('kit_description')->nullable()->after('custom_tagline');
        });

        Schema::create('home_block_kit_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('home_block_item_id')->constrained('home_block_items')->cascadeOnDelete();
            $table->json('title')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('home_block_kit_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('home_block_item_id')->constrained('home_block_items')->cascadeOnDelete();
            $table->foreignId('home_block_kit_group_id')->nullable()->constrained('home_block_kit_groups')->nullOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_block_kit_lines');
        Schema::dropIfExists('home_block_kit_groups');

        Schema::table('home_block_items', function (Blueprint $table) {
            $table->dropColumn('kit_description');
        });
    }
};
