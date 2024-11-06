<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Nova\Fields\DateTime;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class Implementation extends Model implements HasMedia
{
    use HasFactory,
        InteractsWithMedia,
        HasTranslations;

    protected $translatable = ['descriptions'];

    protected $with = ['media'];

    protected $fillable = [
        'is_active',
        'title',
        'descriptions',
        'data',
    ];

    protected $casts = [
        'descriptions' => 'json',
        'data' => 'date',
    ];

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
