<?php

namespace App\Models;

use App\Support\HomeIndexCache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\Translatable\HasTranslations;

class HomeBlockItemQuickLink extends Model implements Sortable
{
    use HasTranslations;
    use SortableTrait;

    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
        'sort_on_has_many' => true,
    ];

    public array $translatable = ['custom_title'];

    protected $fillable = [
        'home_block_item_id',
        'category_id',
        'custom_title',
        'sort_order',
    ];

    protected $casts = [
        'custom_title' => 'array',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(static function (HomeBlockItemQuickLink $link) {
            if ($link->sort_order === null) {
                $link->sort_order = 0;
            }
        });

        static::saved(static function () {
            HomeIndexCache::flush();
        });

        static::deleted(static function () {
            HomeIndexCache::flush();
        });
    }

    public function buildSortQuery(): Builder
    {
        return static::query()->where('home_block_item_id', $this->home_block_item_id);
    }

    public function homeBlockItem(): BelongsTo
    {
        return $this->belongsTo(HomeBlockItem::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function displayTitle(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();
        $custom = $this->getTranslation('custom_title', $locale);
        if (filled($custom)) {
            return $custom;
        }

        return $this->category?->getTranslation('name', $locale)
            ?? $this->category?->getTranslation('name', 'uk')
            ?? '';
    }

    public function catalogUrl(?string $locale = null): ?string
    {
        if ($this->category === null) {
            return null;
        }
        $locale = $locale ?: app()->getLocale();

        return route('products.category', ['path' => $this->category->catalogPath($locale)]);
    }
}
