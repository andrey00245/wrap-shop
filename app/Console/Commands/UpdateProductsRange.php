<?php

namespace App\Console\Commands;

use App\Jobs\UpdateProductJob;
use App\Services\ProductService;
use Illuminate\Console\Command;

class UpdateProductsRange extends Command
{
    protected $signature = 'products:update {start} {end}';

    protected $description = 'Обновление продуктов в сервисе с разбивкой на чанки по 500 товаров';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $start = (int)$this->argument('start');
        $end = (int)$this->argument('end');
        $productService = app(ProductService::class);

        $chunkSize = 500;

        for ($offset = $start; $offset < $end; $offset += $chunkSize) {
            $limit = min($chunkSize, $end - $offset);

            UpdateProductJob::dispatch($productService, $offset, $limit);

            $this->info("Запущена джоба с офсетом {$offset} и лимитом {$limit}.");
        }

        $this->info("Все джобы были запущены.");
    }
}
