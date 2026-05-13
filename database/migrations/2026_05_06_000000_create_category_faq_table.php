<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_faq', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('faq_id');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->primary(['category_id', 'faq_id']);
            // На деяких прод-оточеннях таблиці можуть бути не InnoDB, і FK створити неможливо (errno 150).
            // Для pivot-таблиці це не критично: цілісність забезпечуємо на рівні застосунку/адмінки.
            $table->index('category_id');
            $table->index('faq_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_faq');
    }
};

