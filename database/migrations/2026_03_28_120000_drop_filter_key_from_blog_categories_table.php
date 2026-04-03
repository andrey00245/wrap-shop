<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('blog_categories') || ! Schema::hasColumn('blog_categories', 'filter_key')) {
            return;
        }

        Schema::table('blog_categories', function (Blueprint $table) {
            $table->dropUnique(['filter_key']);
            $table->dropColumn('filter_key');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('blog_categories') || Schema::hasColumn('blog_categories', 'filter_key')) {
            return;
        }

        Schema::table('blog_categories', function (Blueprint $table) {
            $table->string('filter_key')->nullable()->unique()->after('slug');
        });
    }
};
