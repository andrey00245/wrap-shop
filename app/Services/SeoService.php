<?php

namespace App\Services;

use App\Models\Category;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Support\Str;

class SeoService
{
    /**
     * Generate SEO title for product page
     */
    public function generateProductTitle(Product $product): string
    {
        $locale = app()->getLocale();

        // 1. Ручний meta_title з картки товару
        $manualMetaTitle = $this->getLocalizedValue($product->meta_title ?? null, $locale);
        if ($manualMetaTitle) {
            $title = $manualMetaTitle;
        } else {
            // 2. Шаблон із налаштувань
            $settings = Setting::query()->first();
            $template = $settings
                ? $this->getLocalizedValue($settings->product_meta_title_template ?? null, $locale)
                : null;

            if ($template) {
                $replacements = $this->buildProductSeoReplacements($product, $locale);
                $title = strtr($template, $replacements);
                $title = $this->cleanupSpaces($title);
            } else {
                // 3. Фолбек — стара логіка
                $productName = $this->getLocalizedValue($product->getName(), $locale);
                $categoryName = $this->getLocalizedValue($product->category?->name ?? '', $locale);

                $title = $productName;

                if ($categoryName) {
                    $title .= " - {$categoryName}";
                }

                $title .= ' | Wrap.Shop';
            }
        }

        // Limit title length (recommended 50-60 characters)
        if (strlen($title) > 60) {
            $title = Str::limit($title, 57).'...';
        }

        return $title;
    }

    /**
     * Generate SEO description for product page
     */
    public function generateProductDescription(Product $product): string
    {
        $locale = app()->getLocale();

        // 1. Ручний meta_description
        $manualMetaDescription = $this->getLocalizedValue($product->meta_description ?? null, $locale);
        if ($manualMetaDescription) {
            $description = $manualMetaDescription;
        } else {
            // 2. Шаблон із налаштувань
            $settings = Setting::query()->first();
            $template = $settings
                ? $this->getLocalizedValue($settings->product_meta_description_template ?? null, $locale)
                : null;

            if ($template) {
                $replacements = $this->buildProductSeoReplacements($product, $locale);
                $description = strtr($template, $replacements);
                $description = $this->cleanupSpaces($description);
            } else {
                // 3. Фолбек — стара логіка
                $productName = $this->getLocalizedValue($product->getName(), $locale);
                $rawDescription = $product->descriptions;
                $categoryName = $this->getLocalizedValue($product->category?->name ?? '', $locale);

                $cleanDescription = strip_tags((string) $rawDescription);

                if (strlen($cleanDescription) < 50) {
                    $cleanDescription = "{$productName}";
                    if ($categoryName) {
                        $cleanDescription .= " - {$categoryName}";
                    }
                    $cleanDescription .= '. Купити в інтернет-магазині Wrap.Shop з доставкою по Україні.';
                }

                $description = $cleanDescription;
            }
        }

        // Limit description length (recommended 150-160 characters)
        if (strlen($description) > 160) {
            $description = Str::limit($description, 157).'...';
        }

        return $description;
    }

    /**
     * Generate SEO title for category page
     */
    public function generateCategoryTitle(Category $category, ?Category $subcategory = null, ?Category $subsubcategory = null): string
    {
        // Используем meta_title если есть, иначе генерируем
        $currentCategory = $subsubcategory ?? $subcategory ?? $category;

        if ($currentCategory->meta_title) {
            $title = $currentCategory->meta_title;
        } else {
            $title = $category->name;

            if ($subcategory) {
                $title .= " - {$subcategory->name}";
            }

            if ($subsubcategory) {
                $title .= " - {$subsubcategory->name}";
            }

            $title .= ' | Wrap.Shop';
        }

        // Limit title length
        if (strlen($title) > 60) {
            $title = Str::limit($title, 57).'...';
        }

        return $title;
    }

    /**
     * Generate SEO description for category page
     */
    public function generateCategoryDescription(Category $category, ?Category $subcategory = null, ?Category $subsubcategory = null): string
    {
        // Используем meta_description если есть, иначе генерируем
        $currentCategory = $subsubcategory ?? $subcategory ?? $category;

        if ($currentCategory->meta_description) {
            $description = $currentCategory->meta_description;
        } else {
            $description = "Купити {$category->name}";

            if ($subcategory) {
                $description .= " {$subcategory->name}";
            }

            if ($subsubcategory) {
                $description .= " {$subsubcategory->name}";
            }

            $description .= ' в інтернет-магазині Wrap.Shop. Широкий вибір товарів, доставка по Україні, гарантія якості.';
        }

        // Limit description length
        if (strlen($description) > 160) {
            $description = Str::limit($description, 157).'...';
        }

        return $description;
    }

    /**
     * Generate SEO title for home page
     */
    public function generateHomeTitle(): string
    {
        return __('seo.default_title');
    }

    /**
     * Generate SEO description for home page
     */
    public function generateHomeDescription(): string
    {
        return __('seo.default_description');
    }

    /**
     * Generate SEO title for news page
     */
    public function generateNewsTitle(?NewsCategory $category = null, ?News $news = null): string
    {
        if ($news) {
            $title = $news->title.' | Wrap.Shop';
        } elseif ($category) {
            $title = $category->name.' | Wrap.Shop';
        } else {
            $title = 'Новини та акції | Wrap.Shop';
        }

        // Limit title length
        if (strlen($title) > 60) {
            $title = Str::limit($title, 57).'...';
        }

        return $title;
    }

    /**
     * Generate SEO description for news page
     */
    public function generateNewsDescription(?NewsCategory $category = null, ?News $news = null): string
    {
        if ($news) {
            $description = strip_tags($news->description);
            if (strlen($description) > 160) {
                $description = Str::limit($description, 157).'...';
            }

            return $description;
        } elseif ($category) {
            return "Новини та акції в категорії {$category->name} від Wrap.Shop. Останні оновлення, знижки та спеціальні пропозиції.";
        } else {
            return 'Новини та акції від Wrap.Shop. Останні оновлення, знижки та спеціальні пропозиції для автомобілістів.';
        }
    }

    /**
     * Generate Open Graph title
     */
    public function generateOgTitle(string $title): string
    {
        return $title;
    }

    /**
     * Generate Open Graph description
     */
    public function generateOgDescription(string $description): string
    {
        return $description;
    }

    /**
     * Generate Open Graph image URL
     */
    public function generateOgImage(?string $imageUrl = null): string
    {
        if ($imageUrl) {
            return url($imageUrl);
        }

        return url('assets/img/og-default.jpg');
    }

    /**
     * Generate canonical URL
     */
    public function generateCanonicalUrl(string $url): string
    {
        return url($url);
    }

    /**
     * Побудова плейсхолдерів для шаблонів товарів.
     *
     * @return array<string,string>
     */
    protected function buildProductSeoReplacements(Product $product, string $locale): array
    {
        $productName = $this->getLocalizedValue($product->getName(), $locale);
        $siteName = __('seo.site_name');

        $price = null;
        try {
            $price = $product->getPrice();
        } catch (\Throwable $e) {
            $price = null;
        }

        $formattedPrice = $price !== null
            ? number_format((float) $price, 0, '.', ' ').' грн'
            : '';

        $article = $product->article ?? '';

        return [
            '{mod_title}' => (string) $productName,
            '{price}' => $formattedPrice,
            '{site}' => (string) $siteName,
            '{article_for_display}' => (string) $article,
        ];
    }

    /**
     * Акуратно дістає значення для поточної мови з масиву / JSON або повертає рядок як є.
     */
    protected function getLocalizedValue($value, string $locale): string
    {
        if (is_array($value)) {
            if (isset($value[$locale]) && $value[$locale] !== '') {
                return (string) $value[$locale];
            }

            foreach (['uk', 'ru', 'en'] as $fallback) {
                if (isset($value[$fallback]) && $value[$fallback] !== '') {
                    return (string) $value[$fallback];
                }
            }

            $first = reset($value);

            return is_string($first) ? $first : '';
        }

        return (string) ($value ?? '');
    }

    /**
     * Прибирає подвійні пробіли та пробіли перед розділовими знаками.
     */
    protected function cleanupSpaces(string $text): string
    {
        $text = preg_replace('/\s+/', ' ', $text ?? '') ?? '';
        $text = preg_replace('/\s+([,.!?;:])/u', '$1', $text) ?? $text;

        return trim($text);
    }
}
