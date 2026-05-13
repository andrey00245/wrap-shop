<?php

namespace App\Models;

use App\Enums\HomeBlockLayout;
use App\Enums\HomeBlockType;
use App\Support\HomeIndexCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class HomeBlock extends Model
{
    use HasTranslations;

    public array $translatable = ['title'];

    protected $fillable = [
        'type',
        'title',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'type' => HomeBlockType::class,
        'title' => 'array',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(static function () {
            HomeIndexCache::flush();
        });

        static::deleted(static function () {
            HomeIndexCache::flush();
        });
    }

    public function items(): HasMany
    {
        return $this->hasMany(HomeBlockItem::class)->orderBy('sort_order');
    }

    public function getTypeEnum(): HomeBlockType
    {
        if ($this->type instanceof HomeBlockType) {
            return $this->type;
        }

        return HomeBlockType::tryFrom((string) $this->type) ?? HomeBlockType::Custom;
    }

    /**
     * Макет сітки для шаблону категорій на головній: задається типом блоку, не полем в адмінці.
     * Окремі типи згодом можуть мати свої Blade-шаблони з власною розкладкою.
     */
    public function categoriesTemplateLayout(): HomeBlockLayout
    {
        return match ($this->getTypeEnum()) {
            HomeBlockType::Kits => HomeBlockLayout::Grid,
            HomeBlockType::Categories => HomeBlockLayout::LeftBig,
            default => HomeBlockLayout::LeftBig,
        };
    }
}
