<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_filter_pages', function (Blueprint $table) {
            $table->id();
            $table->string('path')->unique()->comment('ЧПУ шлях, напр. plivki/brand-3m/kolir-chornyj');
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->json('filter_data')->comment('Комбінація фільтрів: field_name => [values]');
            $table->json('meta_title')->nullable();
            $table->json('meta_description')->nullable();
            $table->json('seo_text')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_filter_pages');
    }
};
