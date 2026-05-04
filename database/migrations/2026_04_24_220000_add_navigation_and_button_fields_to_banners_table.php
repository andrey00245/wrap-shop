<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->string('nav_title')->nullable()->after('url');
            $table->string('button_text')->nullable()->after('nav_title');
            $table->string('button_url')->nullable()->after('button_text');
        });
    }

    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn(['nav_title', 'button_text', 'button_url']);
        });
    }
};
