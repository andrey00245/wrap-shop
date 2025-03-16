<?php

namespace App\Jobs;

use App\Services\ProductService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateProductJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected int $offset;
    protected int $limit;
    protected ProductService $productService;

    public function __construct(ProductService $productService, $offset, $limit)
    {
        $this->productService = $productService;
        $this->offset = $offset;
        $this->limit = $limit;
    }


    /**
     * @throws \Exception
     */
    public function handle(): void
    {
        $this->productService->processProducts($this->offset,$this->limit);
    }
}
