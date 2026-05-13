<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('redirects', function (Blueprint $table) {
            $table->id();
            // URL, с которого редиректим (относительный путь, без домена)
            $table->string('from_url')->unique();
            // URL, на который ведём (может быть относительным или полным)
            $table->string('to_url');
            // HTTP‑код редиректа, по ТЗ — 301 по умолчанию
            $table->unsignedSmallInteger('status_code')->default(301);
            // Флаг активности редиректа
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('redirects');
    }
};
