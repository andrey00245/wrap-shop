<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class Category extends Model implements HasMedia
{
    use HasFactory,
        InteractsWithMedia,
        HasTranslations;

    protected $translatable = ['name', 'slug', 'meta_title', 'meta_description', 'meta_keywords', 'h1', 'content', 'seo_text'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'h1',
        'content',
        'seo_text',
    ];

    protected $casts = [
        'name' => 'json',
        'slug' => 'json',
        'meta_title' => 'json',
        'meta_description' => 'json',
        'meta_keywords' => 'json',
        'h1' => 'json',
        'content' => 'json',
        'seo_text' => 'json',
    ];

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview_webp')
            ->width(310)
            ->height(310)
            ->format('webp')
            ->nonQueued();
    }

    public function getAllChildren()
    {
        $children = $this->children;
        $allChildren = collect($children);

        foreach ($children as $child) {
            $allChildren = $allChildren->merge($child->getAllChildren());
        }

        return $allChildren;
    }

    public function allDescendantIds(): array
    {
        $ids = [$this->id];

        foreach ($this->children as $child) {
            $ids = array_merge($ids, $child->allDescendantIds());
        }

        return $ids;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('main')->singleFile();
    }

    public function getSlugEnAttribute()
    {
      return $this->getTranslation('slug', 'en');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function getNameUkAttribute()
    {
        return $this->getTranslation('name', 'uk');
    }

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

    public function isParent(): bool
    {
       return is_null($this->parent_id);
    }

    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }
}
