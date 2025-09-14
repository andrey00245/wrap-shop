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
        Schema::table('categories', function (Blueprint $table) {
            // SEO поля для категорий
            $table->json('meta_title')->nullable()->after('slug');
            $table->json('meta_description')->nullable()->after('meta_title');
            $table->json('meta_keywords')->nullable()->after('meta_description');
            $table->json('h1')->nullable()->after('meta_keywords');
            $table->json('content')->nullable()->after('h1');
            $table->json('seo_text')->nullable()->after('content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn([
                'meta_title',
                'meta_description', 
                'meta_keywords',
                'h1',
                'content',
                'seo_text'
            ]);
        });
    }
};
