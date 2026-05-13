<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\Category;
use App\Models\Implementation;
use App\Models\Product;
use App\Models\SeoFilterPage;
use App\Services\PaginationPages;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProductController extends Controller
{
    protected array $availableSortValue = [
        'name',
        'price',
        'is_top_seller',
        'created_at',
    ];

    protected array $availableSortDirection = [
        'asc',
        'desc',
    ];

    /**
     * Handle the incoming request.
     */
    public function index(): View
    {
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
            ->with(['media'])
            ->orderByInStockFirst()
            ->orderByDesc('id')
            ->paginate(6);

        $mainCategories = Category::query()->whereNull('parent_id')->get();

        $categories = $products->take(5)->map(function ($product) {
            return $product->category;
        })->unique('id');

        return view('base.pages.products.index', compact(
            'categories',
            'products',
            'mainCategories'
        )
        );
    }

    /**
     *  Handle the incoming request.
     */
    public function show(Product $product): View
    {
        // Базовый набор кандидатов для рекомендаций:
        // активные товары с ценой, картинками, из той же категории, кроме текущего товара
        $candidates = Product::query()
            ->where('id', '<>', $product->id)
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
            ->where('category_id', $product->category_id)
            ->with(['media', 'category'])
            ->orderByInStockFirst()
            ->orderByDesc('id')
            ->get();

        // Сначала ищем максимально похожие: по основному цвету
        $similarByColor = $candidates->filter(function (Product $productItem) use ($product): bool {
            return $product->getMainColor() !== null
                && $product->getMainColor() === $productItem->getMainColor();
        });

        // Если по цвету нашли товары — используем их как основу
        $products = $similarByColor->isNotEmpty() ? $similarByColor : collect();

        // Если по цвету ничего не нашли или нашли мало — пробуем по бренду
        if ($products->count() < 4) {
            $similarByBrand = $candidates->filter(function (Product $productItem) use ($product): bool {
                return $product->getBrand() !== null
                    && $product->getBrand() === $productItem->getBrand();
            });

            // Добавляем товары по бренду, которых ещё нет в коллекции
            $existingIds = $products->pluck('id')->toArray();
            $similarByBrand->each(function ($item) use (&$products, $existingIds) {
                if (! in_array($item->id, $existingIds)) {
                    $products->push($item);
                    $existingIds[] = $item->id;
                }
            });
        }

        // Если всё ещё мало товаров — добавляем остальные из категории
        if ($products->count() < 4) {
            $existingIds = $products->pluck('id')->toArray();
            $candidates->each(function ($item) use (&$products, $existingIds) {
                if (! in_array($item->id, $existingIds) && $products->count() < 10) {
                    $products->push($item);
                    $existingIds[] = $item->id;
                }
            });
        }

        // Гарантируем уникальность по ID и ограничиваем количество
        $products = $products
            ->unique('id')
            ->sortByDesc(fn (Product $productItem) => $productItem->stock > 0)
            ->take(10)
            ->values();

        $exampleWorks = Implementation::query()->where('is_active', true)->take(12)->get();
        $latestCategory = Category::query()
            ->whereHas('products', function ($query) use ($products) {
                $query->whereIn('products.id', $products->pluck('id')->toArray());
            })
            ->get();

        $viewProducts = session()->get('viewProducts', []);
        if (! in_array($product->id, $viewProducts, true)) {
            $viewProducts[] = $product->id;
            session()?->put('viewProducts', $viewProducts);
        }

        $reviews = $product->reviews->where('is_active', true)->sortByDesc('created_at');
        $maxReviewRating = (float) ($reviews->max('rating') ?? 0);
        $averageRaw = (float) ($reviews->avg('rating') ?? 0);
        $count = $reviews->count();
        $average = Product::normalizeReviewAverageForFiveStarScale($averageRaw, $maxReviewRating);
        $aggregateRatingJsonLd = Product::buildAggregateRatingJsonLd($averageRaw, $count, $maxReviewRating);
        $averagePercent = ($average / 5) * 100;

        $productCategoryBreadcrumbs = $product->category
            ? $this->buildCategoryBreadcrumbs($product->category)
            : [];

        return view('base.pages.products.show', [
            'product' => $product,
            'recommends' => $products,
            'exampleWorks' => $exampleWorks,
            'latestCategory' => $latestCategory,
            'reviews' => $reviews,
            'average' => $average,
            'count' => $count,
            'aggregateRatingJsonLd' => $aggregateRatingJsonLd,
            'averagePercent' => $averagePercent,
            'productCategoryBreadcrumbs' => $productCategoryBreadcrumbs,
        ]);
    }

    /**
     * Сторінка всього каталогу: /catalog — усі товари з фільтрами (верхній рівень для breadcrumbs).
     */
    public function catalog(): View|array|SymfonyResponse
    {
        $categories = Category::query()->pluck('id')->all();
        if ($categories === []) {
            $categories = [-1];
        }
        $childrenCategories = Category::query()->whereNull('parent_id')->get();
        $selectedFilterValues = request()->except([
            'page', 'sort_by', 'sort_direction', 'min_price',
            'max_price', 'in_stock', 'category_id', 'path',
            'search', 'description',
        ]);

        return $this->renderCategoryProductListing(
            $categories,
            $childrenCategories,
            [],
            $selectedFilterValues,
            null,
            null,
            null,
            null,
            '',
            true
        );
    }

    /**
     * Чи вибрані значення атрибутів у query (фільтри OCF) — тоді потрібен «повний» SQL-шлях як у категорії.
     */
    protected function catalogSelectionHasAttributeFilters(array $selectedFilterValues): bool
    {
        foreach ($selectedFilterValues as $value) {
            if (! is_array($value)) {
                continue;
            }
            foreach ($value as $item) {
                if ($item === null || $item === '') {
                    continue;
                }

                return true;
            }
        }

        return false;
    }

    /**
     * Algolia або SQL LIKE (як /search) для списку товарів із join по цінах — /catalog?search=...
     */
    protected function applyCatalogListingSearchFilter(Builder $query): Builder
    {
        if (! request()->filled('search')) {
            return $query;
        }

        $searchValue = (string) request()->get('search');
        $includeDescription = request()->boolean('description');
        $useAlgolia = config('scout.driver') === 'algolia' && ! $includeDescription;

        if ($useAlgolia) {
            $ids = Product::scoutCatalogSearchQuery($searchValue)->keys();
            if ($ids->isEmpty()) {
                return $query->whereRaw('0 = 1');
            }

            return $query->whereIn('products.id', $ids->map(fn ($id) => (int) $id)->unique()->values()->all());
        }

        $columns = ['products.name', 'products.code'];
        if ($includeDescription) {
            $columns[] = 'products.descriptions';
        }

        return $query->whereLikeInsensitive($columns, $searchValue);
    }

    /**
     * /catalog без фільтрів атрибутів: пагінація в БД + агрегати цін (без завантаження всіх товарів у пам’ять).
     */
    protected function renderCatalogRootProductListing(
        $childrenCategories,
        array $categoryBreadcrumbs,
        array $selectedFilterValues,
        ?SeoFilterPage $seoFilterPage,
        string $categoryPath
    ): View|array|SymfonyResponse {
        $sortBy = request()->get('sort_by');
        $sortDirection = request()->get('sort_direction');

        if ($sortBy
            && $sortDirection
            && in_array($sortBy, $this->availableSortValue, true)
            && in_array($sortDirection, $this->availableSortDirection, true)
        ) {
            $sortBy = request()->get('sort_by') === 'name'
                ? 'products.name->'.App::getLocale()
                : $sortBy;
        } else {
            $sortBy = 'id';
            $sortDirection = 'desc';
        }

        $products = Product::query()
            ->join('product_prices', 'products.id', '=', 'product_prices.product_id')
            ->join('price_types', 'product_prices.type_id', '=', 'price_types.id')
            ->where('products.is_active', 1)
            ->where('price_types.external_id', 'bb2a9a14-26f6-11ee-0a80-0f50000d072e')
            ->where('product_prices.price', '>', 0)
            ->whereNotNull('products.category_id')
            ->whereHas('media', function ($query) {
                $query->where('collection_name', 'images');
            })
            ->whereHas('attributes')
            ->select('products.*', DB::raw('MAX(product_prices.price) as price'))
            ->groupBy('products.id',
                'products.code',
                'products.external_code',
                'products.external_id',
                'products.barcodes',
                'products.slug',
                'products.article',
                'products.is_top_seller',
                'products.is_best_seller',
                'products.is_active',
                'products.name',
                'products.descriptions',
                'products.meta_title',
                'products.meta_description',
                'products.category_id',
                'products.created_at',
                'products.updated_at',
                'products.stock',
                'products.banner_title')
            ->orderByInStockFirst()
            ->orderBy($sortBy, $sortDirection)
            ->with([
                'products_attributes' => function ($query) {
                    $query->join('attributes', 'products_attributes.attribute_id', '=', 'attributes.id');
                },
            ]);

        if (request()->get('in_stock')) {
            $products = $products->where('stock', '>', 0)
                ->whereDoesntHave('attributes', function ($subQ) {
                    $subQ->where('field_name', 'under_order')
                        ->where('value', 'так');
                });
        }

        $products = $this->applyCatalogListingSearchFilter($products);

        $rate = Product::getCurrencyRate();
        $boundsQuery = Product::query()
            ->join('product_prices', 'products.id', '=', 'product_prices.product_id')
            ->join('price_types', 'product_prices.type_id', '=', 'price_types.id')
            ->where('products.is_active', 1)
            ->where('price_types.external_id', 'bb2a9a14-26f6-11ee-0a80-0f50000d072e')
            ->where('product_prices.price', '>', 0)
            ->whereNotNull('products.category_id')
            ->whereHas('media', function ($query) {
                $query->where('collection_name', 'images');
            })
            ->whereHas('attributes')
            ->when(request()->get('in_stock'), function ($q) {
                $q->where('stock', '>', 0)
                    ->whereDoesntHave('attributes', function ($subQ) {
                        $subQ->where('field_name', 'under_order')
                            ->where('value', 'так');
                    });
            });

        $bounds = $this->applyCatalogListingSearchFilter($boundsQuery)
            ->selectRaw('MIN(product_prices.price) as min_p, MAX(product_prices.price) as max_p')
            ->first();

        $minPrice = (float) ($bounds->min_p ?? 0) * $rate;
        $maxPrice = (float) ($bounds->max_p ?? 0) * $rate;
        $step = max(1, (int) ceil(($maxPrice - $minPrice) / 4));

        if (request()->get('min_price') && request()->get('max_price')) {
            $products = $products
                ->where('product_prices.price', '>=', request()->get('min_price') / $rate)
                ->where('product_prices.price', '<=', request()->get('max_price') / $rate);
        }

        /*
         * Не використовуємо paginate() на JOIN+GROUP BY: Eloquent гідрує «криві» Product (немає media/імені).
         * Беремо id сторінки з агрегуючого запиту, потім завантажуємо повні моделі.
         */
        $perPage = 30;
        $currentPage = max(1, (int) request()->get('page', 1));

        $total = (int) DB::query()->fromSub(
            $products->clone()->reorder()->select('products.id'),
            'catalog_id_sub'
        )->count();

        $orderedIds = (clone $products)
            ->forPage($currentPage, $perPage)
            ->pluck('products.id')
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        $pageItems = collect();
        if ($orderedIds !== []) {
            $pageItems = Product::query()
                ->whereIn('id', $orderedIds)
                ->with([
                    'media',
                    'category',
                    'products_attributes' => function ($query) {
                        $query->join('attributes', 'products_attributes.attribute_id', '=', 'attributes.id');
                    },
                ])
                ->get()
                ->sortBy(fn (Product $p) => array_search((int) $p->id, $orderedIds, true))
                ->values();
        }

        $paginator = new LengthAwarePaginator(
            $pageItems,
            $total,
            $perPage,
            $currentPage,
            ['path' => url()->current(), 'pageName' => 'page']
        );

        $facet = Product::buildCatalogRootFacetData($products->clone());
        $attributes = $facet['attributes'];
        $attributesArray = $facet['attributes_count'];

        $responseArray = Product::getCatalogRootListingCountsResponse(
            $paginator->total(),
            request(),
            $selectedFilterValues,
            $attributesArray
        );

        if (request()->ajax()) {
            return $this->ajaxProductListingJson($paginator);
        }

        $paginationPages = PaginationPages::getPages(max(1, (int) request()->query('page', 1)), $paginator->lastPage());

        return $this->productListingIndexResponse('base.pages.products.index', [
            'products' => $paginator,
            'category' => null,
            'pages' => $paginationPages,
            'subcategory' => null,
            'subsubcategory' => null,
            'childrenCategories' => $childrenCategories,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'step' => $step,
            'attributes' => $attributes->unique('field_name'),
            'responseArray' => $responseArray,
            'categoryBreadcrumbs' => $categoryBreadcrumbs,
            'seoFilterPage' => $seoFilterPage,
            'categoryPath' => $categoryPath,
            'isCatalogRoot' => true,
        ]);
    }

    /**
     * Один маршрут для категорій і SEO-сторінок фільтрів.
     * URL: /{category_slug} або /{category_slug}/{filter_slug} або /cat/subcat або /cat/subcat/{filter_slug}.
     * Якщо останній сегмент збігається з slug у seo_filter_pages для поточної категорії — показуємо SEO-сторінку.
     */
    public function categoryOrSeoFilter(string $path)
    {
        $path = trim((string) $path, '/');
        if ($path === '') {
            abort(404);
        }

        $segments = array_filter(explode('/', $path));
        $segmentCount = count($segments);

        if ($segmentCount === 1) {
            // Один сегмент — тільки категорія, без SEO-фільтра
            $resolved = $this->resolvePathToCategories($path);
            if ($resolved === null) {
                abort(404);
            }
            [$category, $subcategory, $subsubcategory] = $resolved;

            return $this->renderCategoryListing($category, $subcategory, $subsubcategory, null);
        }

        if ($segmentCount === 2) {
            $category = $this->resolveRootCategoryBySlug($segments[0]);
            if (! $category) {
                abort(404);
            }
            $lastSlug = $segments[1];

            $seoPage = SeoFilterPage::query()
                ->where('category_id', $category->id)
                ->whereRaw('LOWER(slug) = ?', [mb_strtolower($lastSlug)])
                ->where('is_active', true)
                ->with(['category', 'attribute'])
                ->first();

            if ($seoPage) {
                $filterValues = $seoPage->getSelectedFilterValues();
                foreach ($filterValues as $key => $values) {
                    // Додаємо значення і в request(), і в query(), щоб шаблон фільтрів бачив їх як обрані
                    request()->merge([$key => $values]);
                    request()->query->add([$key => $values]);
                }

                return $this->renderCategoryListing($category, null, null, $seoPage);
            }

            $resolved = $this->resolvePathToCategories($path);
            if ($resolved === null) {
                abort(404);
            }
            [$category, $subcategory, $subsubcategory] = $resolved;

            return $this->renderCategoryListing($category, $subcategory, $subsubcategory, null);
        }

        if ($segmentCount >= 3) {
            $category = $this->resolveRootCategoryBySlug($segments[0]);
            if (! $category) {
                abort(404);
            }
            $subcategory = $this->resolveChildCategoryBySlug($category->id, $segments[1]);
            if (! $subcategory) {
                abort(404);
            }
            $lastSlug = $segments[2];

            $seoPage = SeoFilterPage::query()
                ->where('category_id', $subcategory->id)
                ->whereRaw('LOWER(slug) = ?', [mb_strtolower($lastSlug)])
                ->where('is_active', true)
                ->with(['category', 'attribute'])
                ->first();

            if ($seoPage) {
                $filterValues = $seoPage->getSelectedFilterValues();
                foreach ($filterValues as $key => $values) {
                    request()->merge([$key => $values]);
                    request()->query->add([$key => $values]);
                }

                return $this->renderCategoryListing($category, $subcategory, null, $seoPage);
            }

            $resolved = $this->resolvePathToCategories($path);
            if ($resolved === null) {
                abort(404);
            }
            [$category, $subcategory, $subsubcategory] = $resolved;

            return $this->renderCategoryListing($category, $subcategory, $subsubcategory, null);
        }

        abort(404);
    }

    protected function resolveRootCategoryBySlug(string $slug): ?Category
    {
        $locale = App::getLocale();
        $slugPath = '$.'.preg_replace('/[^a-z_]/', '', $locale);

        return Category::query()
            ->whereNull('parent_id')
            ->where(function ($q) use ($slug, $slugPath) {
                $q->whereRaw('JSON_UNQUOTE(JSON_EXTRACT(slug, ?)) = ?', [$slugPath, $slug])
                    ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(slug, '$.en')) = ?", [$slug]);
            })
            ->first();
    }

    protected function resolveChildCategoryBySlug(int $parentId, string $slug): ?Category
    {
        $locale = App::getLocale();
        $slugPath = '$.'.preg_replace('/[^a-z_]/', '', $locale);

        return Category::query()
            ->where('parent_id', $parentId)
            ->where(function ($q) use ($slug, $slugPath) {
                $q->whereRaw('JSON_UNQUOTE(JSON_EXTRACT(slug, ?)) = ?', [$slugPath, $slug])
                    ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(slug, '$.en')) = ?", [$slug]);
            })
            ->first();
    }

    protected function hasOnlyOneAttributeFilter(array $selectedFilterValues): bool
    {
        if (count($selectedFilterValues) !== 1) {
            return false;
        }
        $values = reset($selectedFilterValues);

        return is_array($values) && count($values) === 1;
    }

    protected function findSeoPageForSingleFilter(int $categoryId, array $selectedFilterValues): ?SeoFilterPage
    {
        if (count($selectedFilterValues) !== 1) {
            return null;
        }
        $fieldName = array_key_first($selectedFilterValues);
        $filterValues = $selectedFilterValues[$fieldName];
        if (! is_array($filterValues) || count($filterValues) !== 1) {
            return null;
        }
        $value = reset($filterValues);
        $attribute = Attribute::query()->where('field_name', $fieldName)->first();
        if (! $attribute) {
            return null;
        }

        $valueNormalized = mb_strtolower(trim((string) $value));

        return SeoFilterPage::query()
            ->where('category_id', $categoryId)
            ->where('attribute_id', $attribute->id)
            ->whereRaw('LOWER(TRIM(filter_value)) = ?', [$valueNormalized])
            ->where('is_active', true)
            ->first();
    }

    /**
     * Розбиває path на сегменти і знаходить категорії по slug (перший — коренева, далі — діти).
     * Підтримує довільну глибину: корінь + ланцюг slug-ів; повертає [root, рівень2 або null, лист якщо глибина ≥3].
     */
    protected function resolvePathToCategories(string $path): ?array
    {
        $segments = array_filter(explode('/', $path));
        if (empty($segments)) {
            return null;
        }

        $locale = App::getLocale();
        $slugPath = '$.'.preg_replace('/[^a-z_]/', '', $locale);

        $seg0 = $segments[0];
        $category = Category::query()
            ->whereNull('parent_id')
            ->where(function ($q) use ($seg0, $slugPath) {
                $q->whereRaw('JSON_UNQUOTE(JSON_EXTRACT(slug, ?)) = ?', [$slugPath, $seg0])
                    ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(slug, '$.en')) = ?", [$seg0]);
            })
            ->first();
        if (! $category) {
            return null;
        }

        $n = count($segments);
        if ($n === 1) {
            return [$category, null, null];
        }

        $subcategory = null;
        $subsubcategory = null;
        $current = $category;

        for ($i = 1; $i < $n; $i++) {
            $seg = $segments[$i];
            $child = Category::query()
                ->where('parent_id', $current->id)
                ->where(function ($q) use ($seg, $slugPath) {
                    $q->whereRaw('JSON_UNQUOTE(JSON_EXTRACT(slug, ?)) = ?', [$slugPath, $seg])
                        ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(slug, '$.en')) = ?", [$seg]);
                })
                ->first();
            if (! $child) {
                return null;
            }
            if ($i === 1) {
                $subcategory = $child;
            }
            $current = $child;
        }

        if ($n >= 3) {
            $subsubcategory = $current;
        }

        return [$category, $subcategory, $subsubcategory];
    }

    /**
     * Повний slug-шлях каталогу від кореня до категорії (як у breadcrumbs).
     */
    protected function categoryPathFromLeaf(Category $leaf): string
    {
        $chain = [];
        $cursor = $leaf;
        while ($cursor) {
            $chain[] = $cursor;
            $cursor = $cursor->parent;
        }
        $chain = array_reverse($chain);

        return implode('/', array_map(
            fn (Category $c) => $this->resolveCategorySlugForRoute($c),
            $chain
        ));
    }

    /**
     * Список товарів категорії з фільтрами. Опційно — SEO-сторінка фільтра (власні meta + seo_text).
     */
    protected function renderCategoryListing(
        Category $category,
        ?Category $subcategory,
        ?Category $subsubcategory,
        ?SeoFilterPage $seoFilterPage = null
    ) {
        $selectedFilterValues = request()->except([
            'page', 'sort_by', 'sort_direction', 'min_price',
            'max_price', 'in_stock', 'category_id', 'path',
            'search', 'description',
        ]);

        $currentCategory = $subsubcategory ?? $subcategory ?? $category;

        if ($seoFilterPage === null && $this->hasOnlyOneAttributeFilter($selectedFilterValues)) {
            $redirectSeo = $this->findSeoPageForSingleFilter($currentCategory->id, $selectedFilterValues);
            if ($redirectSeo) {
                $categoryPath = $this->categoryPathFromLeaf($currentCategory);
                $url = route('products.category', ['path' => $categoryPath.'/'.$redirectSeo->slug]);
                if (request()->query()) {
                    $url = $url.'?'.http_build_query(request()->only(['page', 'sort_by', 'sort_direction', 'min_price', 'max_price', 'in_stock']));
                }

                return redirect()->to($url, 301);
            }
        }
        $categoryBreadcrumbs = $this->buildCategoryBreadcrumbs($currentCategory);

        // Получаем вложенные ID
        $categories = $currentCategory->children()->exists()
            ? $currentCategory->allDescendantIds()
            : [$currentCategory->id];

        // Для отображения подкатегорий на странице
        $childrenCategories = $currentCategory->children;

        $categoryPath = $this->categoryPathFromLeaf($currentCategory);

        return $this->renderCategoryProductListing(
            $categories,
            $childrenCategories,
            $categoryBreadcrumbs,
            $selectedFilterValues,
            $category,
            $subcategory,
            $subsubcategory,
            $seoFilterPage,
            $categoryPath,
            false
        );
    }

    /**
     * Спільний рендер сторінки списку товарів (категорія або весь каталог /catalog).
     *
     * @param  array<int, int>  $categories
     */
    protected function renderCategoryProductListing(
        array $categories,
        $childrenCategories,
        array $categoryBreadcrumbs,
        array $selectedFilterValues,
        ?Category $category,
        ?Category $subcategory,
        ?Category $subsubcategory,
        ?SeoFilterPage $seoFilterPage,
        string $categoryPath,
        bool $isCatalogRoot
    ): View|array|SymfonyResponse {
        if ($isCatalogRoot && ! $this->catalogSelectionHasAttributeFilters($selectedFilterValues)) {
            return $this->renderCatalogRootProductListing(
                $childrenCategories,
                $categoryBreadcrumbs,
                $selectedFilterValues,
                $seoFilterPage,
                $categoryPath
            );
        }

        $sortBy = request()->get('sort_by');
        $sortDirection = request()->get('sort_direction');

        if ($sortBy
            && $sortDirection
            && in_array($sortBy, $this->availableSortValue, true)
            && in_array($sortDirection, $this->availableSortDirection, true)
        ) {
            $sortBy = request()->get('sort_by') === 'name'
                ? 'products.name->'.App::getLocale()
                : $sortBy;
        } else {
            $sortBy = 'id';
            $sortDirection = 'desc';
        }

        $products = Product::query()
            ->join('product_prices', 'products.id', '=', 'product_prices.product_id')
            ->join('price_types', 'product_prices.type_id', '=', 'price_types.id')
            ->where('products.is_active', 1)
            ->where('price_types.external_id', 'bb2a9a14-26f6-11ee-0a80-0f50000d072e')
            ->where('product_prices.price', '>', 0)
            ->whereIn('category_id', $categories)
            ->whereHas('media', function ($query) {
                $query->where('collection_name', 'images');
            })
            ->select('products.*', DB::raw('MAX(product_prices.price) as price'))
            ->groupBy('products.id',
                'products.code',
                'products.external_code',
                'products.external_id',
                'products.barcodes',
                'products.slug',
                'products.article',
                'products.is_top_seller',
                'products.is_best_seller',
                'products.is_active',
                'products.name',
                'products.descriptions',
                'products.meta_title',
                'products.meta_description',
                'products.category_id',
                'products.created_at',
                'products.updated_at',
                'products.stock',
                'products.banner_title')
            ->orderByInStockFirst()
            ->orderBy($sortBy, $sortDirection)
            ->with([
                'products_attributes' => function ($query) {
                    $query->join('attributes', 'products_attributes.attribute_id', '=', 'attributes.id');
                },
            ]);

        if (request()->get('in_stock')) {
            $products = $products->where('stock', '>', 0)
                ->whereDoesntHave('attributes', function ($subQ) {
                    $subQ->where('field_name', 'under_order')
                        ->where('value', 'так');
                });
        }

        $temp = $products->get();

        $maxPrice = $temp->max('price') * Product::getCurrencyRate();
        $minPrice = $temp->min('price') * Product::getCurrencyRate();
        $step = ceil(($maxPrice - $minPrice) / 4);

        if (request()->get('min_price') && request()->get('max_price')) {
            $productsAllCollection = $products
                ->where('product_prices.price', '>=', request()->get('min_price') / Product::getCurrencyRate())
                ->where('product_prices.price', '<=', request()->get('max_price') / Product::getCurrencyRate())
                ->get();
        } else {
            $productsAllCollection = $temp;
        }

        $productsAllCollection = $productsAllCollection->filter(function ($product) use ($selectedFilterValues) {
            foreach ($selectedFilterValues as $key => $filterValues) {
                if (! is_array($filterValues)) {
                    continue;
                }
                $matched = false;
                foreach ($product->products_attributes as $products_attribute) {
                    if ($products_attribute->field_name === $key && Product::attributeValueMatchesFilter($products_attribute->value, $filterValues)) {
                        $matched = true;
                        break;
                    }
                }
                if (! $matched) {
                    return false;
                }
            }

            return $product;
        });

        $perPage = 30;
        $attributes = collect();

        foreach ($productsAllCollection as $product) {
            $attributes[] = $product->getProductAttributes();
        }

        $products = new LengthAwarePaginator($productsAllCollection->forPage(request()->get('page'), $perPage), $productsAllCollection->count(), $perPage, request()->get('page'), ['path' => url()->current(), 'pageName' => 'page']);

        $attributesArray = [];

        foreach ($attributes->flatten()->unique('field_name') as $attribure) {
            foreach ($attribure->getPivotValue() as $value) {
                $attributesArray[$attribure->field_name][$value]['count'] = 0;
            }
        }

        $responseArray = Product::getCountProducts($categories, request(), $selectedFilterValues, $attributesArray);

        if (request()->ajax()) {
            return $this->ajaxProductListingJson($products);
        }

        $paginationPages = PaginationPages::getPages(max(1, (int) request()->query('page', 1)), $products->lastPage());

        return $this->productListingIndexResponse('base.pages.products.index', [
            'products' => $products,
            'category' => $category,
            'pages' => $paginationPages,
            'subcategory' => $subcategory,
            'subsubcategory' => $subsubcategory,
            'childrenCategories' => $childrenCategories,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'step' => $step,
            'attributes' => $attributes->flatten()->unique('field_name') ?? collect(),
            'responseArray' => $responseArray,
            'categoryBreadcrumbs' => $categoryBreadcrumbs,
            'seoFilterPage' => $seoFilterPage,
            'categoryPath' => $categoryPath,
            'isCatalogRoot' => $isCatalogRoot,
        ]);
    }

    protected function buildCategoryBreadcrumbs(Category $currentCategory): array
    {
        $chain = [];
        $cursor = $currentCategory;

        while ($cursor) {
            $chain[] = $cursor;
            $cursor = $cursor->parent;
        }

        $chain = array_reverse($chain);
        $breadcrumbs = [];

        $breadcrumbs[] = [
            'name' => __('product-index.catalog_breadcrumb'),
            'url' => route('products.catalog'),
        ];

        foreach ($chain as $index => $category) {
            $slugs = [];
            foreach (range(0, $index) as $position) {
                if (! isset($chain[$position])) {
                    continue;
                }
                $slugs[] = $this->resolveCategorySlugForRoute($chain[$position]);
            }
            $path = implode('/', $slugs);

            $breadcrumbs[] = [
                'name' => $this->resolveCategoryNameForLocale($category),
                'url' => route('products.category', ['path' => $path]),
            ];
        }

        return $breadcrumbs;
    }

    protected function resolveCategorySlugForRoute(Category $category): string
    {
        $locale = App::getLocale();
        $slug = $category->getTranslation('slug', $locale);

        return $slug ?: $category->slugEn;
    }

    protected function resolveCategoryNameForLocale(Category $category): string
    {
        $locale = App::getLocale();
        $name = $category->getTranslation('name', $locale);

        return $name ?: $category->name;
    }

    /**
     * @throws \JsonException
     */
    public function getCount(Request $request)
    {
        $selectedFilterValues = [];
        $categories = [];

        $category = Category::query()->where('id', $request->get('category_id'))->first();
        if ($category !== null) {
            if ($category->hasChildren()) {
                $categories = $category->children()->pluck('id');
            } else {
                $categories[] = $category->id;
            }
        }

        if (empty($categories)) {
            $categories = Category::all()->pluck('id');
        }

        foreach ($request->get('filters') as $key => $filterType) {
            foreach ($filterType as $key_i => $filterValue) {
                if (in_array('true', $filterValue, true)) {
                    $selectedFilterValues[$key][] = $key_i;
                }
            }
        }

        // Той самий набір товарів, що й на /search (Algolia + ліміт hits або SQL name/code)
        $restrictSearchProductIds = null;
        if ($request->filled('search')) {
            $includeDescription = $request->boolean('description');
            $useAlgolia = config('scout.driver') === 'algolia'
                && ! $includeDescription;

            if ($useAlgolia) {
                $restrictSearchProductIds = Product::scoutCatalogSearchQuery((string) $request->get('search'))->keys();
            }
        }

        return response()->json(Product::getCountProducts(
            $categories,
            $request,
            $selectedFilterValues,
            null,
            $restrictSearchProductIds
        ));
    }
}
