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
        'order_column_name'  => 'sort_order',
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

        if ($media) {
            if ($media->hasGeneratedConversion('preview_webp')) {
                return $media->getUrl('preview_webp');
            }

            if ($media->hasGeneratedConversion('preview')) {
                return $media->getUrl('preview');
            }

            return $media->getUrl();
        }

        return '';
    }

    public function getMobileImageUrl(): ?string
    {
        $media = $this->getFirstMedia('main_mobile');
        if ($media === null) {
            return null;
        }

        if ($media->hasGeneratedConversion('mobile_webp')) {
            return $media->getUrl('mobile_webp');
        }

        return $media->getUrl() ?: null;
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->using(CustomBlockProduct::class)
            ->withPivot('sort_order')
            ->orderBy('pivot_sort_order')
            ->orderByRaw('CASE WHEN products.stock > 0 THEN 0 ELSE 1 END');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->performOnCollections('main')
            ->fit(\Spatie\Image\Enums\Fit::Crop, 683, 201)
            ->nonQueued();

        /** Десктопний слайд у кастомному блоці — вертикальна картка як у товарів */
        $this
            ->addMediaConversion('preview_webp')
            ->performOnCollections('main')
            ->fit(\Spatie\Image\Enums\Fit::Crop, 624, 1068)
            ->format('webp')
            ->quality(85)
            ->nonQueued();

        /** Мобільний full-width hero */
        $this
            ->addMediaConversion('mobile_webp')
            ->performOnCollections('main_mobile')
            ->fit(\Spatie\Image\Enums\Fit::Crop, 1080, 1350)
            ->format('webp')
            ->quality(88)
            ->nonQueued();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('main')->singleFile();
        $this->addMediaCollection('main_mobile')->singleFile();
    }
}
