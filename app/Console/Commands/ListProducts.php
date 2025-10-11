<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class ListProducts extends Command
{
    protected $signature = 'products:list {--limit=50 : Количество продуктов для показа} {--offset=0 : Смещение}';
    protected $description = 'Показать список номеров продуктов с их external_id';

    public function handle()
    {
        $limit = $this->option('limit');
        $offset = $this->option('offset');

        $this->info("📋 Список продуктов (лимит: {$limit}, смещение: {$offset})");

        $products = Product::select('id', 'code', 'external_id', 'name', 'created_at', 'updated_at')
            ->whereNotNull('external_id')
            ->orderBy('id')
            ->offset($offset)
            ->limit($limit)
            ->get();

        if ($products->isEmpty()) {
            $this->warn('Продукты не найдены');
            return 0;
        }

        $this->table(
            ['ID', 'Code', 'External ID', 'Name (UK)', 'Created', 'Updated'],
            $products->map(function ($product) {
                return [
                    $product->id,
                    $product->code,
                    $product->external_id,
                    $product->getTranslation('name', 'uk'),
                    $product->created_at->format('Y-m-d H:i:s'),
                    $product->updated_at->format('Y-m-d H:i:s'),
                ];
            })
        );

        $this->info("\nВсего продуктов: " . Product::whereNotNull('external_id')->count());
        $this->info("Показано: {$products->count()}");

        return 0;
    }
}
