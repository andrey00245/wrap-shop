<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->string('photo')->nullable()->after('text');
            $table->string('moderation_status', 20)->default('pending')->after('is_active');
            $table->timestamp('moderated_at')->nullable()->after('moderation_status');
        });

        DB::table('reviews')
            ->where('is_active', true)
            ->update([
                'moderation_status' => 'approved',
                'moderated_at' => now(),
            ]);
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['photo', 'moderation_status', 'moderated_at']);
        });
    }
};
