<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Category;
use App\Models\Product;
use App\Models\News;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

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

            // Категории
            Category::all()->each(function ($category) use ($sitemap, $localeCode) {
                $slug = $category->getTranslation('slug', $localeCode);
                $url = LaravelLocalization::localizeURL("/{$slug}");

                $sitemap->add(
                    Url::create($url)
                        ->setLastModificationDate($category->updated_at ?? now())
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority(1.0)
                );
            });

            // Продукты
            Product::where('is_active', true)->get()->each(function ($product) use ($sitemap, $localeCode) {
                $slug = $product->getTranslation('slug', $localeCode);
                if ($slug) {
                    $url = LaravelLocalization::localizeURL(route('products.show', ['product' => $slug], false));

                    $sitemap->add(
                        Url::create($url)
                            ->setLastModificationDate($product->updated_at ?? now())
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                            ->setPriority(0.8)
                    );
                }
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
}
