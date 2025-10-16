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

    public function getPreviewImage(): string
    {
        $media = $this->getFirstMedia('main');
        if ($media && $media->hasGeneratedConversion('preview_webp')) {
            return $media->getUrl('preview_webp');
        }
        
        return $this->getFirstMediaUrl('main');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->using(CustomBlockProduct::class)
            ->withPivot('sort_order')
            ->orderBy('pivot_sort_order'); // Чтобы сразу выдавать отсортированный список
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->width(683)
            ->height(201)
            ->nonQueued();
            
        $this
            ->addMediaConversion('preview_webp')
            ->width(683)
            ->height(201)
            ->format('webp')
            ->nonQueued();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('main')->singleFile();
    }
}
