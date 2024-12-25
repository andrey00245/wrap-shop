<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }
}
