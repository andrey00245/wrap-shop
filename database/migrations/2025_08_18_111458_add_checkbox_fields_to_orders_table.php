<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->uuid('checkbox_receipt_id')->nullable()->after('payment_status');
            $table->string('checkbox_status')->nullable()->after('checkbox_receipt_id');
            $table->json('checkbox_response')->nullable()->after('checkbox_status');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['checkbox_receipt_id', 'checkbox_status', 'checkbox_response']);
        });
    }
};
