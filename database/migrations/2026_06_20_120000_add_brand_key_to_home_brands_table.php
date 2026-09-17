<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_brands', function (Blueprint $table) {
            $table->string('brand_key', 191)->nullable()->after('id');
        });

        DB::table('home_brands')
            ->whereNotNull('name')
            ->where('name', '!=', '')
            ->orderBy('id')
            ->each(function (object $row): void {
                DB::table('home_brands')
                    ->where('id', $row->id)
                    ->update([
                        'brand_key' => mb_strtolower(trim((string) $row->name)),
                    ]);
            });

        Schema::table('home_brands', function (Blueprint $table) {
            $table->unique('brand_key');
        });
    }

    public function down(): void
    {
        Schema::table('home_brands', function (Blueprint $table) {
            $table->dropUnique(['brand_key']);
            $table->dropColumn('brand_key');
        });
    }
};
