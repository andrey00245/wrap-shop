<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Product;

class ProductVolumeSelector extends Component
{
    public Product $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function volumeVariants()
    {
        return $this->product->allVolumeVariants();
    }

    public function render()
    {
        return view('components.product-volume-selector');
    }
}
