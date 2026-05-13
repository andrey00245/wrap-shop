<?php

namespace App\Http\Controllers;

use App\Models\BlogAuthor;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $categories = BlogCategory::query()
            ->active()
            ->withPublishedPosts()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $featuredPost = BlogPost::query()
            ->published()
            ->featured()
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->with(['author', 'category', 'media'])
            ->first();

        if ($featuredPost === null) {
            $featuredPost = BlogPost::query()
                ->published()
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->with(['author', 'category', 'media'])
                ->first();
        }

        $latestQuery = BlogPost::query()
            ->published()
            ->with(['author', 'category', 'media'])
            ->orderByDesc('created_at')
            ->orderByDesc('id');

        if ($featuredPost !== null) {
            $latestQuery->where('id', '!=', $featuredPost->id);
        }

        $latestPosts = $latestQuery->take(3)->get();

        // Не дублюємо лише hero у блоках категорій. «Останні 3» навмисно не виключаємо —
        // інакше в категорії часто не лишається інших постів і зникає вся секція.
        $featuredId = $featuredPost?->id;

        $postsByCategory = [];
        foreach ($categories as $cat) {
            $postsByCategory[$cat->id] = BlogPost::query()
                ->published()
                ->where('blog_category_id', $cat->id)
                ->when($featuredId !== null, fn ($q) => $q->where('id', '!=', $featuredId))
                ->with(['author', 'category', 'media'])
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->take(2)
                ->get();
        }

        $categoryPublishedCounts = BlogPost::query()
            ->published()
            ->whereNotNull('blog_category_id')
            ->groupBy('blog_category_id')
            ->selectRaw('blog_category_id, COUNT(*) as aggregate')
            ->pluck('aggregate', 'blog_category_id');

        $blogTitle = __('blog.index_title');
        $blogDescription = __('blog.index_description');

        return view('base.pages.blog.index', compact(
            'categories',
            'featuredPost',
            'latestPosts',
            'postsByCategory',
            'categoryPublishedCounts',
            'blogTitle',
            'blogDescription'
        ));
    }

    public function show(BlogPost $blog_post): View
    {
        if (! $blog_post->isPublic()) {
            abort(404);
        }

        $blog_post->load(['author', 'category', 'media', 'productCatalogCategory.parent']);
        $blog_post->loadCount([
            'comments as approved_comments_count' => fn ($q) => $q->where('is_approved', true),
        ]);

        $comments = $blog_post->approvedRootComments()
            ->with(['replies' => fn ($q) => $q->approved()->orderBy('created_at')])
            ->get();

        $sidebarPosts = BlogPost::query()
            ->published()
            ->where('id', '!=', $blog_post->id)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->take(5)
            ->get();

        $categorySidebarPosts = collect();
        if ($blog_post->blog_category_id !== null) {
            $categorySidebarPosts = BlogPost::query()
                ->published()
                ->where('blog_category_id', $blog_post->blog_category_id)
                ->where('id', '!=', $blog_post->id)
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->take(5)
                ->get();
        }

        $similarPosts = BlogPost::query()
            ->published()
            ->where('id', '!=', $blog_post->id)
            ->when($blog_post->blog_category_id, fn ($q) => $q->where('blog_category_id', $blog_post->blog_category_id))
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->take(6)
            ->with(['author', 'category', 'media'])
            ->get();

        $catalog = $this->resolveBlogPostCatalogPresentation($blog_post);
        $products = $catalog['products'];
        $blogCatalogNavRoot = $catalog['navRoot'];
        $blogProductsSectionTitle = $catalog['sectionTitle'];
        $blogCatalogSidebarCategories = collect();
        if ($blogCatalogNavRoot !== null) {
            $blogCatalogNavRoot->load(['children' => fn ($q) => $q->orderBy('id')]);
            $blogCatalogSidebarCategories = $blogCatalogNavRoot->children;
        }

        $locale = app()->getLocale();
        $articleTitle = $blog_post->getTranslation('meta_title', $locale)
            ?: $blog_post->getTranslation('title', $locale).' | '.__('blog.blog_suffix');
        $articleDescription = strip_tags(
            (string) ($blog_post->getTranslation('meta_description', $locale)
                ?: $blog_post->getTranslation('excerpt', $locale)
                ?: '')
        );

        return view('base.pages.blog.show', compact(
            'blog_post',
            'comments',
            'sidebarPosts',
            'categorySidebarPosts',
            'similarPosts',
            'products',
            'blogCatalogNavRoot',
            'blogCatalogSidebarCategories',
            'blogProductsSectionTitle',
            'articleTitle',
            'articleDescription'
        ));
    }

    public function author(BlogAuthor $blog_author): View
    {
        $posts = $blog_author->publishedPosts()
            ->with(['category', 'media'])
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(12);

        $pageTitle = $blog_author->getTranslation('name', app()->getLocale())
            .' | '.__('blog.author_suffix');
        $pageDescription = __('blog.author_meta', ['name' => $blog_author->getTranslation('name', app()->getLocale())]);

        return view('base.pages.blog.author', compact('blog_author', 'posts', 'pageTitle', 'pageDescription'));
    }

    /**
     * Товари для блоку на сторінці статті та корінь навігації каталогу в лівому сайдбарі.
     *
     * @return array{products: Collection<int, Product>, navRoot: ?Category, sectionTitle: string}
     */
    private function resolveBlogPostCatalogPresentation(BlogPost $post): array
    {
        $manual = $post->attachedProducts()
            ->where('products.is_active', 1)
            ->orderByPivot('sort_order')
            ->with(['media', 'category.parent'])
            ->get();

        $focus = null;
        $products = collect();

        if ($manual->isNotEmpty()) {
            $products = $manual;
            $focus = $manual->first(fn (Product $p) => $p->category_id !== null)?->category;
            if ($focus === null && $post->productCatalogCategory) {
                $focus = $post->productCatalogCategory;
            }
        } elseif ($post->product_catalog_category_id !== null && $post->productCatalogCategory) {
            $focus = $post->productCatalogCategory->loadMissing('parent');
            $products = $this->blogProductsFromCatalogCategory($focus);
        }

        if ($products->isEmpty()) {
            return [
                'products' => collect(),
                'navRoot' => null,
                'sectionTitle' => '',
            ];
        }

        $navRoot = null;
        $sectionTitle = '';
        if ($focus !== null) {
            $navRoot = $focus->parent ?? $focus;
            $locale = app()->getLocale();
            $sectionTitle = (string) ($navRoot->getTranslation('name', $locale)
                ?: $navRoot->getTranslation('name', 'uk')
                ?: $navRoot->getTranslation('name', 'en'));
        }

        if ($sectionTitle === '') {
            $sectionTitle = (string) __('blog.products_section_fallback');
        }

        return [
            'products' => $products,
            'navRoot' => $navRoot,
            'sectionTitle' => $sectionTitle,
        ];
    }

    /**
     * @return Collection<int, Product>
     */
    private function blogProductsFromCatalogCategory(Category $category): Collection
    {
        $categoryIds = $category->allDescendantIds();

        return $this->blogProductsBaseQuery()
            ->whereIn('category_id', $categoryIds)
            ->where('stock', '>', 0)
            ->orderByDesc('id')
            ->take(10)
            ->get();
    }

    /**
     * Як раніше у filmCategoryProducts: лише активні з ціною та зображенням.
     */
    private function blogProductsBaseQuery(): Builder
    {
        return Product::query()
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
            ->with(['media', 'category']);
    }
}
