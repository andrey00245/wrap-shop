<?php

namespace App\Models;

use App\Models\Traits\HasJsonTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class Product extends Model implements HasMedia
{
    use HasFactory,
        HasTranslations,
        InteractsWithMedia;

    public $translatable = [
        'name',
        'descriptions',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'external_code',
        'external_id',
        'barcodes',
        'article',
        'is_top_seller',
        'is_active',
        'details',
        'category_id',
        'name',
        'descriptions',
    ];

    protected $casts = [
        'details' => 'json',
    ];

    /**
     * Brand.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @param Media|null $media
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->width(310)
            ->height(310)
            ->format('png')
            ->nonQueued();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images');
    }

    /**
     * Prices.
     */
    public function prices(): HasMany
    {
        return $this->hasMany(ProductPrice::class);
    }

    /**
     * Prices.
     */
    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'products_attributes')
            ->using(ProductAttribute::class)
            ->withPivot('value');
    }

    /**
     * @return BelongsTo
     */
    public function purpose(): BelongsTo
    {
        return $this->belongsTo(Purpose::class);
    }

    public function getNameAttribute($value)
    {
        return $value;
    }

    public function getPrice()
    {
        return $this->prices()->whereHas('type', function ($query) {
            $query->where('price_types.external_id', 'bb2a9a14-26f6-11ee-0a80-0f50000d072e');
        })->value('price');
    }

    public function getImage(): string
    {
        return $this->getFirstMediaUrl('images');
    }
}
