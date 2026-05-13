<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class FeedController extends Controller
{
    private static $categoryMappingCache = null;

    /**
     * Завантаження JSON-мапи категорій (з кешуванням)
     */
    private function loadCategoryMapping(): ?array
    {
        if (self::$categoryMappingCache !== null) {
            return self::$categoryMappingCache;
        }

        $paths = [
            storage_path('app/data/categories_google_map.json'),
            base_path('data/categories_google_map.json'),
        ];

        foreach ($paths as $path) {
            if (File::exists($path)) {
                $json = json_decode(File::get($path), true);
                if (is_array($json)) {
                    self::$categoryMappingCache = $json;
                    return $json;
                }
            }
        }

        self::$categoryMappingCache = [];
        return null;
    }

    /**
     * Пошук відповідності категорії в JSON-мапі
     */
    private function findMappingByCategoryId(int $categoryId, ?array $mapping = null): ?array
    {
        $mapping = $mapping ?? $this->loadCategoryMapping();
        if (!$mapping) return null;

        foreach ($mapping as $map) {
            if (isset($map['id']) && (int)$map['id'] === $categoryId) {
                return $map;
            }
        }
        return null;
    }

    /**
     * Отримання Google Product Category ID
     */
    private function getGoogleProductCategoryId(Category $category): string
    {
        $mapping = $this->loadCategoryMapping();
        if (!$mapping) return '3680'; // fallback

        $found = $this->findMappingByCategoryId($category->id, $mapping);
        if ($found && isset($found['google_category_id'])) {
            return (string)$found['google_category_id'];
        }

        if ($category->parent_id) {
            $parent = $this->findMappingByCategoryId((int)$category->parent_id, $mapping);
            if ($parent && isset($parent['google_category_id'])) {
                return (string)$parent['google_category_id'];
            }
        }

        return '3680';
    }

    /**
     * Генерація XML для масиву продуктів
     */
    private function generateFeedXml($products, string $feedTitle): string
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        // Створення <rss>
        $rss = $dom->createElement('rss');
        $rss->setAttribute('version', '2.0');
        $rss->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:g', 'http://base.google.com/ns/1.0');
        $dom->appendChild($rss);

        // Створення <channel>
        $channel = $dom->createElement('channel');
        $rss->appendChild($channel);

        $channel->appendChild($dom->createElement('title', $feedTitle));
        $channel->appendChild($dom->createElement('link', config('app.url')));
        $channel->appendChild($dom->createElement('description', 'Фід для ремаркетингу - ' . $feedTitle));

        $channel->appendChild($dom->createElementNS('http://base.google.com/ns/1.0', 'g:target_country', 'UA'));
        $channel->appendChild($dom->createElementNS('http://base.google.com/ns/1.0', 'g:currency', 'UAH'));

        // Товари
        foreach ($products as $product) {
            $item = $dom->createElement('item');
            $channel->appendChild($item);

            $category = $product->category ?? new Category();
            $googleCategoryId = $this->getGoogleProductCategoryId($category);

            $title = $product->getTranslation('name', app()->getLocale()) ?? ($product->name ?? '');
            $description = Str::limit(strip_tags($product->getTranslation('descriptions', app()->getLocale()) ?? ''), 500);

            // Формуємо шлях категорій
            $categoryParts = [];
            if ($category && $category->id) {
                $current = $category;
                while ($current) {
                    $categoryParts[] = is_array($current->name)
                        ? ($current->name['uk'] ?? $current->name['ru'] ?? $current->name['en'] ?? '')
                        : (string)$current->name;
                    $current = $current->parent ?? null;
                }
            }
            $productType = implode(' > ', array_reverse(array_filter($categoryParts)));

            $modelNumber = $product->article ?? ($product->code ?? null);

            // Упрощений хелпер для додавання тегів
            $append = function ($name, $value, $useCdata = true) use ($dom, $item) {
                if ($value !== null && $value !== '') {
                    $el = $dom->createElementNS('http://base.google.com/ns/1.0', $name);
                    if ($useCdata) {
                        $el->appendChild($dom->createCDATASection($value));
                    } else {
                        $el->appendChild($dom->createTextNode($value));
                    }
                    $item->appendChild($el);
                }
            };

            // Формування структури item
            $append('g:id', (string)$product->id);
            $append('g:title', $title);
            $append('g:description', $description);
            $append('g:link', route('products.show', ['product' => $product->slugEn]));
            $append('g:image_link', $product->getImage());
            $append('g:availability', $product->stock > 0 ? 'in stock' : 'out of stock', false);
            $append('g:condition', 'new', false);
            $append('g:price', number_format($product->getPrice(), 2, '.', '') . ' UAH', false);
            $append('g:google_product_category', $googleCategoryId);
            $append('g:product_type', $productType ?: 'Інше');
            $append('g:brand', $product->getBrand() ?? 'Wrap Shop');
            $append('g:identifier_exists', 'false', false);

            if ($modelNumber) {
                $append('g:model_number', (string)$modelNumber);
            }
        }

        // Додаємо заголовок XML перед поверненням
        return "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n" . $dom->saveXML($dom->documentElement);
    }

    /**
     * Фід для всіх категорій
     */
    public function remarketingAllCategories()
    {
        $categories = Category::with('children')->get();

        $categoryIds = $categories->flatMap(function ($cat) {
            return $cat->isParent()
                ? $cat->children()->pluck('id')->push($cat->id)
                : collect([$cat->id]);
        })->unique();

        $products = Product::whereIn('category_id', $categoryIds)
            ->where('is_active', 1)
            ->where('stock', '>', 0)
            ->whereHas('media', fn($q) => $q->where('collection_name', 'images'))
            ->with('category.parent')
            ->get();

        $xml = $this->generateFeedXml($products, config('app.name') . ' - Усі товари');

        return response($xml, 200)->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    /**
     * Фід для однієї категорії
     */
    public function remarketingByCategory(Request $request)
    {
        $categoryId = $request->get('category_id');
        if (!$categoryId) {
            return response()->json(['error' => 'Не вказано ID категорії'], 400);
        }

        $category = Category::find($categoryId);
        if (!$category) {
            return response()->json(['error' => 'Категорію не знайдено'], 404);
        }

        $categoryIds = $category->isParent()
            ? $category->children()->pluck('id')->push($category->id)
            : collect([$category->id]);

        $products = Product::whereIn('category_id', $categoryIds)
            ->where('is_active', 1)
            ->where('stock', '>', 0)
            ->whereHas('media', fn($q) => $q->where('collection_name', 'images'))
            ->with('category.parent')
            ->get();

        $xml = $this->generateFeedXml($products, config('app.name') . ' - ' . $category->name);

        return response($xml, 200)->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
