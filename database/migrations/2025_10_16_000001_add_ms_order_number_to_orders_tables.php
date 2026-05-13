<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('ms_order_number')->nullable()->after('moysklad_id');
        });

        if (Schema::hasTable('fast_orders')) {
            Schema::table('fast_orders', function (Blueprint $table) {
                $table->string('ms_order_number')->nullable()->after('status');
            });
        }
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('ms_order_number');
        });

        if (Schema::hasTable('fast_orders')) {
            Schema::table('fast_orders', function (Blueprint $table) {
                $table->dropColumn('ms_order_number');
            });
        }
    }
};


