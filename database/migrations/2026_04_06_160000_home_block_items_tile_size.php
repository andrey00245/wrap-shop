<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_block_items', function (Blueprint $table) {
            $table->string('tile_size', 32)->nullable()->after('category_id');
        });
    }

    public function down(): void
    {
        Schema::table('home_block_items', function (Blueprint $table) {
            $table->dropColumn('tile_size');
        });
    }
};
