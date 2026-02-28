<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class BlogController extends Controller
{
    /**
     * Главная страница блога
     */
    public function index(): View
    {
        return view('base.pages.blog.index');
    }

    /**
     * Детальная страница статьи
     */
    public function show(): View
    {
        // Находим категорию "Плівки" или "Кольорові плівки"
        $category = Category::query()
            ->where(function ($query) {
                $query->where('name->uk', 'Плівки')
                    ->orWhere('name->uk', 'Кольорові плівки')
                    ->orWhere('name->uk', 'КОЛЬОРОВІ ПЛІВКИ');
            })
            ->first();

        // Если категория найдена, получаем все дочерние категории
        $categoryIds = [];
        if ($category) {
            $categoryIds = $category->allDescendantIds();
            $categoryIds[] = $category->id;
        }

        // Получаем продукты из категории "Плівки" и её подкатегорий
        $products = Product::query()
            ->where('is_active', 1)
            ->whereHas('prices', function ($query) {
                $query->where('type_id', function ($subQuery) {
                    $subQuery->select('id')
                        ->from('price_types')
                        ->where('external_id', 'bb2a9a14-26f6-11ee-0a80-0f50000d072e');
                })->where('price', '>', 0);
            })
            ->whereHas('media', function ($query) {
                $query->where('collection_name', 'images');
            })
            ->whereHas('category')
            ->when(!empty($categoryIds), function ($query) use ($categoryIds) {
                $query->whereIn('category_id', $categoryIds);
            })
            ->with(['media', 'category'])
            ->take(10)
            ->get();

        return view('base.pages.blog.show', compact('products'));
    }
}
