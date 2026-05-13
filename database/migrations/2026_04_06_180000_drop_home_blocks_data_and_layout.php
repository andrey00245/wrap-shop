<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_blocks', function (Blueprint $table) {
            if (Schema::hasColumn('home_blocks', 'data')) {
                $table->dropColumn('data');
            }
            if (Schema::hasColumn('home_blocks', 'layout')) {
                $table->dropColumn('layout');
            }
        });
    }

    public function down(): void
    {
        Schema::table('home_blocks', function (Blueprint $table) {
            $table->string('layout', 32)->nullable();
            $table->json('data')->nullable();
        });
    }
};
