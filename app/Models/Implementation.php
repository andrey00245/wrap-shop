<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Implementation extends Model implements HasMedia, Sortable
{
    use HasFactory,
        InteractsWithMedia,
        SortableTrait,
        HasTranslations;

    protected $translatable = ['descriptions'];

    protected $with = ['media'];

    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
    ];

    protected $fillable = [
        'is_active',
        'title',
        'descriptions',
        'data',
        'sort_order'
    ];

    protected $casts = [
        'descriptions' => 'json',
        'data' => 'date',
    ];

    protected static function booted()
    {
        static::addGlobalScope('sortOrder', function ($query) {
            $query->orderBy('sort_order', 'asc');
        });
    }

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
        $this->addMediaCollection('main')->singleFile();
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getImage(): string
    {
        return $this->getFirstMediaUrl('images');
    }

    public function getData(): string
    {
        return (new Carbon($this->data))->format('Y-m-d');
    }
}
