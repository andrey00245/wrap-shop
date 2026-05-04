<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE reviews DROP FOREIGN KEY reviews_product_id_foreign');
            DB::statement('ALTER TABLE reviews MODIFY product_id BIGINT UNSIGNED NULL');
            DB::statement('ALTER TABLE reviews ADD CONSTRAINT reviews_product_id_foreign FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE');
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            $fallbackId = DB::table('products')->orderBy('id')->value('id');
            if ($fallbackId !== null) {
                DB::table('reviews')->whereNull('product_id')->update(['product_id' => $fallbackId]);
            }

            DB::statement('ALTER TABLE reviews DROP FOREIGN KEY reviews_product_id_foreign');
            DB::statement('ALTER TABLE reviews MODIFY product_id BIGINT UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE reviews ADD CONSTRAINT reviews_product_id_foreign FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE');
        }
    }
};
