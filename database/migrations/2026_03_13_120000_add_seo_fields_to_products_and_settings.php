<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->json('meta_title')->nullable()->after('descriptions');
            $table->json('meta_description')->nullable()->after('meta_title');
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->json('product_meta_title_template')->nullable()->after('slogan_desc');
            $table->json('product_meta_description_template')->nullable()->after('product_meta_title_template');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_description']);
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['product_meta_title_template', 'product_meta_description_template']);
        });
    }
};
