<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('category_homepage_quick_links');

        if (Schema::hasColumn('categories', 'show_on_homepage')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn([
                    'show_on_homepage',
                    'homepage_sort_order',
                    'homepage_tile_size',
                    'homepage_bottom_caption',
                ]);
            });
        }

        Schema::create('home_blocks', function (Blueprint $table) {
            $table->id();
            $table->string('type', 32);
            $table->string('layout', 32)->nullable();
            $table->json('title')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('home_block_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('home_block_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->json('custom_title')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_block_items');
        Schema::dropIfExists('home_blocks');
    }
};
