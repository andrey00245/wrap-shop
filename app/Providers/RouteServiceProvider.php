<?php

namespace App\Providers;

use App\Models\BlogAuthor;
use App\Models\BlogPost;
use App\Models\Category;
use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/home';

    public function boot()
    {
        parent::boot();
        Route::bind('category', fn ($slug) => $this->resolveCategoryBySlug($slug));
        Route::bind('subcategory', fn ($slug) => $this->resolveCategoryBySlug($slug));
        Route::bind('subsubcategory', fn ($slug) => $this->resolveCategoryBySlug($slug));
        Route::bind('news_category', fn ($slug) => $this->resolveNewsCategoryBySlug($slug));
        Route::bind('news', fn ($slug) => $this->resolveNewsBySlug($slug));
        Route::bind('blog_author', fn (string $slug) => BlogAuthor::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail());
        Route::bind('blog_post', fn (string $slug) => $this->resolveBlogPostBySlug($slug));
    }

    public function map()
    {
        $this->mapWebRoutes();
    }

    protected function mapWebRoutes(): void
    {
        Route::middleware('web')
            ->group(base_path('routes/web.php'));
    }

    protected function resolveCategoryBySlug(string $slug): Category
    {
        return $this->applyTranslatableSlugScope(Category::query(), $slug)->firstOrFail();
    }

    protected function resolveNewsCategoryBySlug(string $slug): NewsCategory
    {
        return $this->applyTranslatableSlugScope(NewsCategory::query(), $slug)->firstOrFail();
    }

    protected function resolveNewsBySlug(string $slug): News
    {
        return $this->applyTranslatableSlugScope(News::query(), $slug)->firstOrFail();
    }

    protected function resolveBlogPostBySlug(string $slug): BlogPost
    {
        return $this->applyTranslatableSlugScope(BlogPost::query(), $slug)->firstOrFail();
    }

    protected function applyTranslatableSlugScope(Builder $query, string $slug): Builder
    {
        $locales = $this->getLocalePriorityOrder();

        return $query->where(function (Builder $innerQuery) use ($locales, $slug) {
            foreach ($locales as $index => $locale) {
                $method = $index === 0 ? 'whereJsonContains' : 'orWhereJsonContains';
                $innerQuery->{$method}("slug->{$locale}", $slug);
            }
        });
    }

    protected function getLocalePriorityOrder(): array
    {
        $configuredLocales = config('tab-translatable.locales', ['uk', 'ru', 'en']);
        $currentLocale = app()->getLocale();

        return array_values(array_unique(array_filter([$currentLocale, ...$configuredLocales])));
    }
}
