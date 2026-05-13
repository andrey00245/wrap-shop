<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seo_filter_pages', function (Blueprint $table) {
            $table->dropUnique(['path']);
        });

        Schema::table('seo_filter_pages', function (Blueprint $table) {
            $table->dropColumn(['path', 'filter_data']);
        });

        Schema::table('seo_filter_pages', function (Blueprint $table) {
            $table->foreignId('attribute_id')->nullable()->after('category_id')->constrained('attributes')->cascadeOnDelete();
            $table->string('filter_value')->nullable()->after('attribute_id')->comment('Значення фільтра як у products_attributes');
            $table->string('slug')->nullable()->after('filter_value')->comment('ЧПУ напр. kolir-chornyj, brand-3m');
        });

        Schema::table('seo_filter_pages', function (Blueprint $table) {
            $table->unique(['category_id', 'slug'], 'seo_filter_pages_category_slug_unique');
        });
    }

    public function down(): void
    {
        Schema::table('seo_filter_pages', function (Blueprint $table) {
            $table->dropUnique('seo_filter_pages_category_slug_unique');
        });

        Schema::table('seo_filter_pages', function (Blueprint $table) {
            $table->dropForeign(['attribute_id']);
            $table->dropColumn(['attribute_id', 'filter_value', 'slug']);
        });

        Schema::table('seo_filter_pages', function (Blueprint $table) {
            $table->string('path')->nullable()->after('id');
            $table->json('filter_data')->nullable()->after('category_id');
        });

        Schema::table('seo_filter_pages', function (Blueprint $table) {
            $table->unique('path');
        });
    }
};
