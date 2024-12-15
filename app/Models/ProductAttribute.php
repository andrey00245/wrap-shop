<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Spatie\Translatable\HasTranslations;

class ProductAttribute extends Pivot
{
    use HasTranslations;

    public $translatable = ['value'];

    protected $fillable = ['product_id', 'attribute_id', 'value'];

    protected $table = 'products_attributes';

    protected $casts = [
        'value' => 'array',
    ];
}
