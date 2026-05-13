<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Делаем payment_method необязательным
            $table->string('payment_method')->nullable()->change();
            
            // Добавляем флаг быстрого заказа
            $table->boolean('is_fast_order')->default(false)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Возвращаем payment_method как обязательное
            $table->string('payment_method')->nullable(false)->change();
            
            // Удаляем флаг быстрого заказа
            $table->dropColumn('is_fast_order');
        });
    }
};
