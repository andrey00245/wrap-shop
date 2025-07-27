<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class CustomBlock extends Model implements HasMedia, Sortable
{
    use HasFactory;
    use HasTranslations;
    use  SortableTrait;
    use  InteractsWithMedia;

    protected $translatable = ['name'];

    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
    ];

    protected $casts = [
        'name' => 'json',
    ];

    public function getImage(): string
    {
        return $this->getFirstMediaUrl('main');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->width(683)
            ->height(201)
            ->format('jpg')
            ->nonQueued();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('main')->singleFile();
    }
}
