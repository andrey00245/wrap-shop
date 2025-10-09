<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Response;

class RobotsController extends Controller
{
    /**
     * Generate dynamic robots.txt
     */
    public function index(): Response
    {
        $categories = Category::whereNull('parent_id')
            ->select('slug')
            ->get()
            ->pluck('slug')
            ->toArray();

        $robots = $this->generateRobotsContent($categories);
        
        return response($robots, 200)
            ->header('Content-Type', 'text/plain; charset=UTF-8')
            ->header('Cache-Control', 'public, max-age=3600'); // Кэшируем на 1 час
    }

    /**
     * Generate robots.txt content
     */
    private function generateRobotsContent(array $categories): string
    {
        $baseUrl = config('app.url');
        $sitemapUrl = $baseUrl . '/sitemap.xml';

        $content = "# Robots.txt для Wrap.Shop - Интернет-магазин пленок для автомобилей\n";
        $content .= "# Автоматически генерируется на основе структуры сайта\n";
        $content .= "# Последнее обновление: " . now()->format('Y-m-d H:i:s') . "\n\n";

        // Основные поисковые роботы
        $content .= "# Основные поисковые роботы\n";
        $content .= "User-agent: Googlebot\n";
        $content .= "Allow: /\n";
        $content .= "Crawl-delay: 1\n\n";

        $content .= "User-agent: Bingbot\n";
        $content .= "Allow: /\n";
        $content .= "Crawl-delay: 1\n\n";

        $content .= "User-agent: YandexBot\n";
        $content .= "Allow: /\n";
        $content .= "Crawl-delay: 2\n\n";

        // Все остальные роботы
        $content .= "# Все остальные роботы\n";
        $content .= "User-agent: *\n";
        $content .= "Allow: /\n";
        $content .= "Crawl-delay: 2\n\n";

        // Sitemap
        $content .= "# Sitemap\n";
        $content .= "Sitemap: {$sitemapUrl}\n\n";

        // Запрещенные директории
        $content .= "# Запрещенные директории (админка, системные файлы)\n";
        $disallowedDirs = [
            '/admin/',
            '/nova/',
            '/storage/',
            '/vendor/',
            '/api/',
            '/command-runner',
            '/build/',
            '/node_modules/',
        ];

        foreach ($disallowedDirs as $dir) {
            $content .= "Disallow: {$dir}\n";
        }
        $content .= "\n";

        // Запрещенные страницы
        $content .= "# Запрещенные страницы (личные данные, корзина, оформление)\n";
        $disallowedPages = [
            '/checkout',
            '/cart',
            '/user/',
            '/account/',
            '/login',
            '/register',
            '/password/',
            '/email/',
        ];

        foreach ($disallowedPages as $page) {
            $content .= "Disallow: {$page}\n";
        }
        $content .= "\n";

        // Запрещенные параметры URL
        $content .= "# Запрещенные параметры URL (дубли контента)\n";
        $disallowedParams = [
            '/*?sort=*',
            '/*?filter=*',
            '/*?page=*',
            '/*?search=*',
            '/*?utm_*',
            '/*?fbclid=*',
            '/*?gclid=*',
        ];

        foreach ($disallowedParams as $param) {
            $content .= "Disallow: {$param}\n";
        }
        $content .= "\n";

        // Запрещенные файлы
        $content .= "# Запрещенные файлы\n";
        $disallowedFiles = [
            '/*.json$',
            '/*.xml$',
            '/*.txt$',
            '/*.log$',
            '/*.sql$',
        ];

        foreach ($disallowedFiles as $file) {
            $content .= "Disallow: {$file}\n";
        }
        $content .= "\n";

        // Разрешенные важные страницы
        $content .= "# Разрешенные важные страницы\n";
        $allowedPages = [
            '/about-us',
            '/contacts',
            '/shipping-and-payment',
            '/privacy-policy',
            '/news',
            '/delivery',
        ];

        foreach ($allowedPages as $page) {
            $content .= "Allow: {$page}\n";
        }

        // Разрешенные категории (динамически)
        if (!empty($categories)) {
            $content .= "\n# Разрешенные категории товаров\n";
            foreach ($categories as $category) {
                $content .= "Allow: /{$category}\n";
            }
        }

        // Блокируем агрессивных ботов
        $content .= "\n# Блокируем агрессивных ботов\n";
        $aggressiveBots = [
            'AhrefsBot',
            'MJ12bot',
            'DotBot',
            'SemrushBot',
            'BLEXBot',
            'MegaIndex',
            'DataForSeoBot',
        ];

        foreach ($aggressiveBots as $bot) {
            $content .= "User-agent: {$bot}\n";
            $content .= "Disallow: /\n\n";
        }

        return $content;
    }
}
