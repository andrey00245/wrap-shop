<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_block_items', function (Blueprint $table) {
            $table->string('kits_catalog_mode', 16)->nullable()->after('kit_description');
        });
    }

    public function down(): void
    {
        Schema::table('home_block_items', function (Blueprint $table) {
            $table->dropColumn('kits_catalog_mode');
        });
    }
};
