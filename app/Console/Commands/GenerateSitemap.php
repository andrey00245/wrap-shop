<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\News;
use App\Models\Product;
use App\Models\SeoFilterPage;
use Illuminate\Console\Command;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'generate:sitemap';

    protected $description = 'Генерация sitemap.xml со всеми доступными страницами';

    public function handle()
    {
        $sitemap = Sitemap::create();

        $locales = LaravelLocalization::getSupportedLocales();

        foreach ($locales as $localeCode => $props) {
            LaravelLocalization::setLocale($localeCode);

            // Статические страницы
            $sitemap->add(Url::create(LaravelLocalization::localizeURL(route('index'))));
            $sitemap->add(Url::create(LaravelLocalization::localizeURL(route('about-us'))));
            $sitemap->add(Url::create(LaravelLocalization::localizeURL(route('contacts'))));
            $sitemap->add(Url::create(LaravelLocalization::localizeURL(route('delivery'))));
            $sitemap->add(Url::create(LaravelLocalization::localizeURL(route('privacy-policy'))));
            $sitemap->add(Url::create(LaravelLocalization::localizeURL(route('checkout'))));

            $categoriesById = Category::all()->keyBy('id');

            $sitemap->add(
                Url::create(LaravelLocalization::localizeURL(route('products.catalog')))
                    ->setLastModificationDate(now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                    ->setPriority(1.0)
            );

            // Категории (повний шлях від кореня)
            $categoriesById->each(function ($category) use ($sitemap, $localeCode, $categoriesById) {
                $path = $this->categoryPathForLocale($category, $localeCode, $categoriesById);
                if ($path === '') {
                    return;
                }
                $url = LaravelLocalization::localizeURL(route('products.category', ['path' => $path]));

                $sitemap->add(
                    Url::create($url)
                        ->setLastModificationDate($category->updated_at ?? now())
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority(1.0)
                );
            });

            // SEO-сторінки фільтрів
            SeoFilterPage::where('is_active', 1)
                ->with('category')
                ->get()
                ->each(function (SeoFilterPage $seoPage) use ($sitemap, $localeCode, $categoriesById) {
                    $category = $seoPage->category;
                    if (! $category) {
                        return;
                    }
                    $basePath = $this->categoryPathForLocale($category, $localeCode, $categoriesById);
                    if ($basePath === '') {
                        return;
                    }
                    $path = $basePath.'/'.$seoPage->slug;
                    $url = LaravelLocalization::localizeURL(route('products.category', ['path' => $path]));

                    $sitemap->add(
                        Url::create($url)
                            ->setLastModificationDate($seoPage->updated_at ?? now())
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                            ->setPriority(0.9)
                    );
                });

            // Продукты
            Product::where('is_active', true)->get()->each(function ($product) use ($sitemap, $localeCode) {
                $slug = $product->getTranslation('slug', $localeCode);
                $url = LaravelLocalization::localizeURL(route('products.show', ['product' => $slug], false));

                $sitemap->add(
                    Url::create($url)
                        ->setLastModificationDate($product->updated_at ?? now())
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority(1.0)
                );
            });

            // Новости
            $sitemap->add(Url::create(LaravelLocalization::localizeURL(route('news.index'))));
            News::with('category')->get()->each(function ($news) use ($sitemap, $localeCode) {
                $catSlug = optional($news->category)->getTranslation('slug', $localeCode);
                $newsSlug = $news->getTranslation('slug', $localeCode);
                if ($catSlug && $newsSlug) {
                    $url = LaravelLocalization::localizeURL("/news/{$catSlug}/{$newsSlug}");
                    $sitemap->add(Url::create($url));
                }
            });
        }

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('✅ sitemap.xml успешно сгенерирован.');
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Category>  $categoriesById
     */
    private function categoryPathForLocale(Category $category, string $localeCode, $categoriesById): string
    {
        $chain = [];
        $cursor = $category;
        while ($cursor) {
            $chain[] = $cursor;
            $cursor = $cursor->parent_id ? $categoriesById->get($cursor->parent_id) : null;
        }
        $chain = array_reverse($chain);
        $segments = [];
        foreach ($chain as $c) {
            $slug = $c->getTranslation('slug', $localeCode) ?: $c->getTranslation('slug', 'en');
            if ($slug !== null && $slug !== '') {
                $segments[] = $slug;
            }
        }

        return implode('/', $segments);
    }
}
