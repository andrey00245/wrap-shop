<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Support\Str;

class SeoService
{
    /**
     * Generate SEO title for product page
     */
    public function generateProductTitle(Product $product): string
    {
        $productName = $product->getName();
        $categoryName = $product->category?->name ?? '';
        $locale = app()->getLocale();
        
        // Base title with product name
        $title = $productName;
        
        // Add category if available
        if ($categoryName) {
            $title .= " - {$categoryName}";
        }
        
        // Add brand/site name
        $title .= " | Wrap.Shop";
        
        // Limit title length (recommended 50-60 characters)
        if (strlen($title) > 60) {
            $title = Str::limit($title, 57) . '...';
        }
        
        return $title;
    }
    
    /**
     * Generate SEO description for product page
     */
    public function generateProductDescription(Product $product): string
    {
        $productName = $product->getName();
        $description = $product->descriptions;
        $categoryName = $product->category?->name ?? '';
        $price = $product->getPrice();
        
        // Clean description from HTML tags
        $cleanDescription = strip_tags($description);
        
        // If description is too short, create one from product name and category
        if (strlen($cleanDescription) < 50) {
            $cleanDescription = "{$productName}";
            if ($categoryName) {
                $cleanDescription .= " - {$categoryName}";
            }
            $cleanDescription .= ". Купити в інтернет-магазині Wrap.Shop з доставкою по Україні.";
        }
        
        // Limit description length (recommended 150-160 characters)
        if (strlen($cleanDescription) > 160) {
            $cleanDescription = Str::limit($cleanDescription, 157) . '...';
        }
        
        return $cleanDescription;
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
            
            $title .= " | Wrap.Shop";
        }
        
        // Limit title length
        if (strlen($title) > 60) {
            $title = Str::limit($title, 57) . '...';
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
            
            $description .= " в інтернет-магазині Wrap.Shop. Широкий вибір товарів, доставка по Україні, гарантія якості.";
        }
        
        // Limit description length
        if (strlen($description) > 160) {
            $description = Str::limit($description, 157) . '...';
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
            $title = $news->title . " | Wrap.Shop";
        } elseif ($category) {
            $title = $category->name . " | Wrap.Shop";
        } else {
            $title = "Новини та акції | Wrap.Shop";
        }
        
        // Limit title length
        if (strlen($title) > 60) {
            $title = Str::limit($title, 57) . '...';
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
                $description = Str::limit($description, 157) . '...';
            }
            return $description;
        } elseif ($category) {
            return "Новини та акції в категорії {$category->name} від Wrap.Shop. Останні оновлення, знижки та спеціальні пропозиції.";
        } else {
            return "Новини та акції від Wrap.Shop. Останні оновлення, знижки та спеціальні пропозиції для автомобілістів.";
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
}
