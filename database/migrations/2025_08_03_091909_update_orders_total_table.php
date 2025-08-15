<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Округляем значения до 2 знаков перед изменением типа
        DB::statement('UPDATE orders SET total = ROUND(total, 2)');

        // Меняем тип колонки
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('total', 10, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->float('total')->nullable()->change();
        });
    }
};
