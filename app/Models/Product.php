<?php

namespace App\Models;

use App\Models\Attribute;
use App\Services\Pluralize;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Laravel\Scout\Searchable;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

/**
 * @method static Builder whereLikeInsensitive(string $column, string $value)
 *
 * @property $code
 * @property $external_code
 * @property $external_id
 * @property $barcodes
 * @property $article
 * @property $is_top_seller
 * @property $is_active
 * @property $details
 * @property $category_id
 * @property $name
 * @property $slug
 * @property $descriptions
 * @property $banner_title
 * @property $stock
 */
class Product extends Model implements HasMedia
{
    use HasFactory,
        HasTranslations,
        InteractsWithMedia,
        Searchable;

    public array $translatable = [
        'name',
        'descriptions',
        'banner_title',
        'slug',
        'meta_title',
        'meta_description',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'external_code',
        'external_id',
        'barcodes',
        'article',
        'is_top_seller',
        'is_active',
        'details',
        'category_id',
        'name',
        'slug',
        'descriptions',
        'banner_title',
        'banner_title',
        'meta_title',
        'meta_description',
        'stock',
    ];

    protected $attributes = [
        'stock' => 0,
        'name' => '{"en":"","ru":"","uk":""}',
        'descriptions' => '{"en":"","ru":"","uk":""}',
        'banner_title' => '{"en":"","ru":"","uk":""}',
        'meta_title' => '{"en":"","ru":"","uk":""}',
        'meta_description' => '{"en":"","ru":"","uk":""}',
    ];

    protected $attributes = [
        'stock' => 0,
        'name' => '{"en":"","ru":"","uk":""}',
        'descriptions' => '{"en":"","ru":"","uk":""}',
        'banner_title' => '{"en":"","ru":"","uk":""}',
    ];

    protected $casts = [
        'banner_title' => 'json',
        'slug' => 'json',
        'name' => 'json',
        'descriptions' => 'json',
        'meta_title' => 'json',
        'meta_description' => 'json',
        'stock' => 'float',
    ];

    /**
     * @param $query
     * @param $column
     * @param $value
     * @return Builder
     */
    public function scopeWhereLikeInsensitive($query, array $columns, string $value): Builder
    {
        $keywords = preg_split('/\s+/', mb_strtolower($value), -1, PREG_SPLIT_NO_EMPTY);

        $allKeywords = [];
        foreach ($keywords as $word) {
            $allKeywords[] = $word;
            $allKeywords[] = static::toTranslit($word);
        }

        $query->where(function ($outerQuery) use ($columns, $allKeywords) {
            foreach ($columns as $column) {
                $outerQuery->orWhere(function ($innerQuery) use ($column, $allKeywords) {
                    foreach ($allKeywords as $keyword) {
                        if (! empty($keyword)) {
                            $innerQuery->orWhereRaw("LOWER(products.{$column}) LIKE ?", ['%'.$keyword.'%'])
                                ->orWhereRaw("LOWER(products.{$column}) LIKE ?", [$keyword.'%']);
                        }
                    }
                });
            }
        });

        return $query;
    }

    /**
     * У списках на сайті: спочатку товари зі stock > 0, далі без наявності; далі лишайте свій orderBy.
     */
    public function scopeOrderByInStockFirst(Builder $query): Builder
    {
        $stock = $query->qualifyColumn('stock');

        return $query->orderByRaw('(CASE WHEN '.$stock.' > 0 THEN 0 ELSE 1 END) ASC');
    }

    /**
     * Розгортання значення атрибута (JSON / переклади) у плоский список рядків для Algolia.
     *
     * @return array<int, string>
     */
    protected static function flattenAttributeValueStringsForSearch(mixed $raw): array
    {
        if ($raw === null || $raw === '') {
            return [];
        }
        if (is_string($raw)) {
            $t = trim($raw);

            return $t === '' ? [] : [$t];
        }
        if (! is_array($raw)) {
            return [];
        }
        $out = [];
        foreach ($raw as $v) {
            $out = array_merge($out, self::flattenAttributeValueStringsForSearch($v));
        }

        return array_values(array_unique($out));
    }

    /**
     * Данные, которые отправляются в Algolia (Laravel Scout).
     */
    public function toSearchableArray(): array
    {
        // Подгружаем связи, которые нужны для индекса
        $this->loadMissing(['category', 'attributes']);

        // Не використовувати $this->attributes у циклі — це внутрішній масив колонок Model::$attributes, не зв'язок attributes().
        /** @var \Illuminate\Database\Eloquent\Collection<int, Attribute> $catalogAttributes */
        $catalogAttributes = $this->getRelation('attributes');

        // Переводы названия продукта
        $nameTranslations = method_exists($this, 'getTranslations')
            ? $this->getTranslations('name')
            : (array) $this->name;

        // Переводы названия категории
        $categoryNameTranslations = [];
        if ($this->category && method_exists($this->category, 'getTranslations')) {
            $categoryNameTranslations = $this->category->getTranslations('name');
        }

        // Бренд как строка
        $brandValue = $this->getBrand();
        if (is_array($brandValue)) {
            $brandValue = implode(' ', array_filter($brandValue));
        }

        // Видимі значення атрибутів (відтінок, структура тощо) — щоб знаходити «зелений», «сатинова» без цих слів у назві
        $excludeAttributeFields = [
            'name', 'application', 'purpose', 'benefits',
            'default_quantity', 'master_qualification', 'store_terms', 'warranty',
            'quantity_step', 'min_order_quantity', 'first_stock', 'second_stock', 'third_stock', 'under_order',
        ];
        $attributeValueStrings = [];
        foreach ($catalogAttributes as $attr) {
            if (! $attr->is_visible || in_array($attr->field_name, $excludeAttributeFields, true)) {
                continue;
            }
            $raw = $attr->pivot->value ?? null;
            foreach (self::flattenAttributeValueStringsForSearch($raw) as $piece) {
                $attributeValueStrings[] = $piece;
            }
        }

        $searchableText = mb_strtolower(implode(' ', array_filter(array_merge(
            array_values($nameTranslations),
            array_values($categoryNameTranslations),
            [(string) $this->code, (string) $this->article, (string) $brandValue],
            $attributeValueStrings,
        ))));

        return [
            'id' => $this->id,
            'code' => $this->code,
            'article' => $this->article,
            'name' => $nameTranslations,
            'category_id' => $this->category_id,
            'category' => $categoryNameTranslations,
            'brand' => $brandValue,
            /** Усі мови назви/категорії, код, артикул, бренд + значення видимих атрибутів (після зміни — scout:import) */
            'searchable_text' => $searchableText,
        ];
    }

    /**
     * Ограничиваем, какие товары попадают в индекс поиска.
     */
    public function shouldBeSearchable(): bool
    {
        return (bool) $this->is_active && $this->media()->exists();
    }

    /**
     * Один Scout-запит для /search, попапу та get-count: узгоджені параметри Algolia (typoTolerance тощо).
     */
    public static function scoutCatalogSearchQuery(string $query, ?int $take = null): \Laravel\Scout\Builder
    {
        $take ??= (int) config('app.algolia_search_max_hits', 50);
        $raw = config('app.algolia_search_typo_tolerance', 'strict');
        $typoTolerance = match (strtolower(trim((string) $raw))) {
            'true', '1', 'yes' => true,
            'false', '0', 'no' => false,
            'min' => 'min',
            default => 'strict',
        };

        return static::search($query)->take($take)->options([
            'typoTolerance' => $typoTolerance,
        ]);
    }

    protected static function toTranslit(string $text): string
    {
        $map = [
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'h', 'ґ' => 'g', 'д' => 'd',
            'е' => 'e', 'є' => 'ye', 'ж' => 'zh', 'з' => 'z', 'и' => 'y', 'і' => 'i',
            'ї' => 'yi', 'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u',
            'ф' => 'f', 'х' => 'kh', 'ц' => 'ts', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shch',
            'ь' => '', 'ю' => 'yu', 'я' => 'ya',

            'ё' => 'yo', 'э' => 'e', 'ъ' => '',
        ];

        return strtr(mb_strtolower($text), $map);
    }

    public function getSlugEnAttribute()
    {
        return $this->getTranslation('slug', 'en');
    }

    public function customBlocks()
    {
        return $this->belongsToMany(CustomBlock::class)
            ->using(CustomBlockProduct::class)
            ->withPivot('sort_order');
    }

    /**
     * Brand.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * @return bool
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function isFavorite(): bool
    {
        if (Auth::check()) {
            return $this->wishlists()->where('user_id', Auth::id())->exists();
        }
        if (session()?->has('wishlist')) {
            return in_array($this->id, session()?->get('wishlist', []), true);
        }
        return false;
    }

    /**
     * Category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function blogPosts(): BelongsToMany
    {
        return $this->belongsToMany(BlogPost::class, 'blog_post_product')
            ->withPivot('sort_order');
    }

    /**
     * Wishlists.
     */
    public function wishlists(): belongsToMany
    {
        return $this->belongsToMany(User::class, 'wishlists');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $conversions = MediaConversions::getConversionsConfig();

        foreach ($conversions as $conversionName => $config) {
            $conversion = $this->addMediaConversion($conversionName);

            if ($config['width'] && $config['height']) {
                $conversion->width($config['width'])->height($config['height']);
            }

            if ($config['quality']) {
                $conversion->quality($config['quality']);
            }

            if ($config['sharpen']) {
                $conversion->sharpen($config['sharpen']);
            }

            if ($config['format']) {
                $conversion->format($config['format']);
            }

            // Используем contain для правильного масштабирования без искажений
            if (isset($config['fit'])) {
                $conversion->fit(Fit::Contain);
            }

            $conversion->optimize();

            foreach ($config['collections'] as $collection) {
                $conversion->performOnCollections($collection);
            }

            $conversion->nonQueued();
        }

        // Конвертация видео для мобильных устройств (легкая версия)
        // ВАЖНО: Для работы конвертации видео нужен ffmpeg и пакет php-ffmpeg/php-ffmpeg
        // Пока что используем оригинальное видео, но структура готова для будущей конвертации
        // Конвертация видео будет реализована через отдельный Job или внешний сервис
        // TODO: Реализовать конвертацию видео через ffmpeg в отдельном Job
        // - mobile: 720p, битрейт ~2Mbps
        // - desktop: 1080p, битрейт ~5Mbps
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images');
        $this->addMediaCollection('banner_images');
    }

    /**
     * Prices.
     */
    public function prices(): HasMany
    {
        return $this->hasMany(ProductPrice::class);
    }

    /**
     * Attributes.
     */
    public function products_attributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class);
    }

    /**
     * Attributes.
     */
    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'products_attributes')
            ->using(ProductAttribute::class)
            ->withPivot('value');
    }

    /**
     * YouTube видео
     */
    public function youtubeVideos(): HasMany
    {
        return $this->hasMany(ProductVideo::class)->orderBy('sort_order');
    }

    public function purpose(): BelongsTo
    {
        return $this->belongsTo(Purpose::class);
    }

    public function getNameAttribute($value)
    {
        return $value;
    }

    /**
     * @throws \JsonException
     */
    public function getPrice()
    {
        return $this->prices()->whereHas('type', function ($query) {
            $query->where('price_types.external_id', 'bb2a9a14-26f6-11ee-0a80-0f50000d072e');
        })->value('price') * self::getCurrencyRate();
    }

    public function getImage(?string $conversion = null): string
    {
        if ($conversion) {
            $media = $this->getFirstMedia('images');
            if ($media) {
                return $media->getUrl($conversion);
            }
        }

        return $this->getFirstMediaUrl('images');
    }

    public function getPreviewImage(): string
    {
        $media = $this->getFirstMedia('images');
        if ($media) {
            // Сначала пробуем preview_webp
            if ($media->hasGeneratedConversion('preview_webp')) {
                return $media->getUrl('preview_webp');
            }

            // Если preview_webp нет, пробуем gallery
            if ($media->hasGeneratedConversion('gallery')) {
                return $media->getUrl('gallery');
            }

            // Если gallery нет, пробуем preview
            if ($media->hasGeneratedConversion('preview')) {
                return $media->getUrl('preview');
            }
        }

        return $this->getFirstMediaUrl('images');
    }

    public function getPreviewImage(): string
    {
        $media = $this->getFirstMedia('images');
        if ($media && $media->hasGeneratedConversion('preview_webp')) {
            return $media->getUrl('preview_webp');
        }
        
        return $this->getFirstMediaUrl('images');
    }

    public function getBannerImages()
    {
        return $this->getMedia('banner_images');
    }

    public function getVideos()
    {
        return $this->getMedia('videos');
    }

    /**
     * Получить все YouTube видео продукта
     */
    public function getYoutubeVideos()
    {
        return $this->youtubeVideos;
    }

    /**
     * Проверить, есть ли YouTube видео.
     * Безопасно возвращает false, если таблица product_videos отсутствует (миграция не выполнена).
     */
    public function hasYoutubeVideos(): bool
    {
        try {
            return $this->youtubeVideos()->count() > 0;
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Получить URL видео для мобильных устройств (легкая версия)
     * Если конверсия не существует, возвращает оригинал
     */
    public function getMobileVideoUrl(): ?string
    {
        $video = $this->getFirstMedia('videos');
        if (! $video) {
            return null;
        }

        // Пытаемся получить легкую версию для мобильных
        if ($video->hasGeneratedConversion('mobile')) {
            return $video->getUrl('mobile');
        }

        // Если конверсии нет, возвращаем оригинал
        return $video->getUrl();
    }

    /**
     * Получить URL видео для десктопа (качественная версия)
     * Если конверсия не существует, возвращает оригинал
     */
    public function getDesktopVideoUrl(): ?string
    {
        $video = $this->getFirstMedia('videos');
        if (! $video) {
            return null;
        }

        // Пытаемся получить качественную версию для десктопа
        if ($video->hasGeneratedConversion('desktop')) {
            return $video->getUrl('desktop');
        }

        // Если конверсии нет, возвращаем оригинал
        return $video->getUrl();
    }

    /**
     * Получить первое видео
     */
    public function getFirstVideo()
    {
        return $this->getFirstMedia('videos');
    }

    /**
     * Получить постер (первый кадр) видео
     * Если постер не существует, возвращает null
     */
    public function getVideoPoster(): ?string
    {
        $video = $this->getFirstMedia('videos');
        if (! $video) {
            return null;
        }

        // Проверяем существование файла постера напрямую
        // Job создает постер в: storage/app/public/media-library/conversions/{id}/poster_{file_name}.jpg
        // И сохраняет в: storage/app/public/{id}/poster/{file_name}.jpg
        $originalPath = $video->getPath();

        // Вариант 1: Постер в директории медиа (после saveConversion)
        $posterDirectory = dirname($originalPath).'/poster';
        $posterFileName = pathinfo($video->file_name, PATHINFO_FILENAME).'.jpg';
        $posterPath = $posterDirectory.'/'.$posterFileName;

        // Вариант 2: Постер в директории конверсий (временный путь)
        $conversionsPath = storage_path('app/public/media-library/conversions/'.$video->id);
        $conversionsPosterPath = $conversionsPath.'/poster_'.pathinfo($video->file_name, PATHINFO_FILENAME).'.jpg';

        // Проверяем оба варианта
        if (file_exists($posterPath)) {
            // Возвращаем публичный URL
            $publicPath = str_replace(storage_path('app/public'), '', $posterPath);

            return asset('storage'.$publicPath);
        } elseif (file_exists($conversionsPosterPath)) {
            // Если постер еще в директории конверсий, перемещаем его
            if (! is_dir($posterDirectory)) {
                mkdir($posterDirectory, 0755, true);
            }
            if (copy($conversionsPosterPath, $posterPath)) {
                $publicPath = str_replace(storage_path('app/public'), '', $posterPath);

                return asset('storage'.$publicPath);
            }
        }

        // Если постер не создан, возвращаем null (будет использован fallback)
        return null;
    }

    public function getProductAttributes()
    {
        return $this->attributes()
            ->where('is_visible', true) // Показываем только видимые атрибуты
            ->whereNotIn('field_name', [
                'name',
                'application',
                'purpose',
                'benefits',
                'default_quantity',
                'master_qualification',
                'store_terms',
                'warranty',
                'quantity_step',
                'min_order_quantity',
                'first_stock',
                'second_stock',
                'third_stock',
                'under_order',
            ])
            ->get();
    }

    /**
     * Чи збігається значення атрибута товара з масивом значень фільтра (регістр і пробіли ігноруються).
     */
    public static function attributeValueMatchesFilter(mixed $productValue, array $filterValues): bool
    {
        $normalize = fn (string $v): string => mb_strtolower(trim((string) $v));
        $filterNormalized = array_map($normalize, array_map('strval', $filterValues));
        $productValues = is_array($productValue) ? array_values($productValue) : [$productValue];
        $productFlat = array_filter(array_map(fn ($v) => is_scalar($v) ? (string) $v : null, $productValues));
        foreach ($productFlat as $pv) {
            if (in_array($normalize($pv), $filterNormalized, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  \Illuminate\Support\Collection<int, int>|null  $restrictSearchProductIds  Якщо задано (пошук через Algolia) — лічильники лише по цих id, без SQL LIKE.
     */
    public static function getCountProducts($categories, $request, $selectedFilterValues, $arrAttr = null, ?Collection $restrictSearchProductIds = null)
    {
        if ($request->get('filters')) {
            $responseArray['attributes_count'] = self::mergeClientFiltersIntoAttributesCount(
                $request->get('filters'),
                is_array($arrAttr) ? $arrAttr : []
            );
        } else {
            $responseArray['attributes_count'] = $arrAttr ?? [];
        }

        $withPrice = function ($query) {
            $query->where('type_id', DB::table('price_types')
                ->where('external_id', 'bb2a9a14-26f6-11ee-0a80-0f50000d072e')
                ->value('id'))
                ->where('price', '>', 0);
        };

        if ($request->get('min_price') && $request->get('max_price')) {
            if ((int) $request->get('min_price') !== 0 && (int) $request->get('max_price') !== 0) {
                $withPrice = function ($query) use ($request) {
                    $query->where('type_id', DB::table('price_types')
                        ->where('external_id', 'bb2a9a14-26f6-11ee-0a80-0f50000d072e')
                        ->value('id'))
                        ->where('price', '>=', (int) $request->get('min_price') / Product::getCurrencyRate())
                        ->where('price', '<=', (int) $request->get('max_price') / Product::getCurrencyRate());
                };
            }
        }

        $products = collect();

        if ($request->get('search')) {

            // Як на сторінці пошуку (SearchController): name + code, опційно descriptions
            $columns = ['name', 'code'];
            $searchValue = $request->get('search');
            $includeDescription = $request->boolean('description');
            if ($includeDescription) {
                $columns[] = 'descriptions';
            }

            $products = self::query()
                ->where('is_active', 1)
                ->whereIn('category_id', $categories)
                ->when(
                    $restrictSearchProductIds !== null,
                    function ($query) use ($restrictSearchProductIds) {
                        if ($restrictSearchProductIds->isEmpty()) {
                            $query->whereRaw('0 = 1');

                            return;
                        }
                        $query->whereIn('id', $restrictSearchProductIds->all());
                    },
                    function ($query) use ($columns, $searchValue) {
                        if ($searchValue) {
                            $query->whereLikeInsensitive($columns, $searchValue);
                        }
                    }
                )
                ->whereHas('attributes')
                ->whereHas('media')
                ->whereHas('prices', $withPrice)
                ->with(['products_attributes' => function ($query) {
                    $query->join('attributes', 'products_attributes.attribute_id', '=', 'attributes.id');
                }])
                ->get();
        } else {
            $productsQuery = self::query()
                ->where('is_active', 1)
                ->whereIn('category_id', $categories)
                ->whereHas('attributes')
                ->whereHas('media')
                ->whereHas('prices', $withPrice)
                ->with(['products_attributes' => function ($query) {
                    $query->join('attributes', 'products_attributes.attribute_id', '=', 'attributes.id');
                }]);

            if ($request->filled('in_stock')) {
                $productsQuery = $productsQuery->where('stock', '>', 0)
                    ->whereDoesntHave('attributes', function ($subQ) {
                        $subQ->where('field_name', 'under_order')
                            ->where('value', 'так');
                    });
            }

            $products = $productsQuery->get();
        }

        $selectedProducts = $products->filter(function ($product) use ($selectedFilterValues) {
            foreach ($selectedFilterValues as $key => $filterValues) {
                if (! is_array($filterValues)) {
                    continue;
                }
                $matched = false;
                foreach ($product->products_attributes as $products_attribute) {
                    if ($products_attribute->field_name === $key && self::attributeValueMatchesFilter($products_attribute->value, $filterValues)) {
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

        if (empty($selectedFilterValues) && (int) $request->get('min_price') === 0 && (int) $request->get('max_price') === 0) {
            $responseArray['total_count'] = __('product-index.select_filters');
        } else {
            $responseArray['total_count'] = __('product-index.show_products.'.Pluralize::getDeclension($selectedProducts->count(), App::getLocale()), ['count' => $selectedProducts->count()]);
        }

        $nonSelectedProducts = $products->filter(function ($product) use ($selectedFilterValues) {
            foreach ($selectedFilterValues as $key => $filterValues) {
                if (! is_array($filterValues)) {
                    continue;
                }
                $matched = false;
                foreach ($product->products_attributes as $products_attribute) {
                    if ($products_attribute->field_name === $key
                        && self::attributeValueMatchesFilter($products_attribute->value, $filterValues)) {
                        $matched = true;
                        break;
                    }
                }
                if (! $matched) {
                    return $product;
                }
            }

            return false;
        });

        foreach ($responseArray['attributes_count'] as $key_i => $item) {
            foreach ($item as $key_j => $attribute) {
                if (! array_key_exists('count', (array) $responseArray['attributes_count'][$key_i][$key_j])) {
                    (array) $responseArray['attributes_count'][$key_i][$key_j]['count'] = 0;
                }
            }
        }

        foreach ($selectedProducts as $product) {
            foreach ($product->products_attributes as $products_attribute) {
                if (! array_key_exists($products_attribute->field_name, $responseArray['attributes_count'])) {
                    continue;
                }
                $facetKey = self::normalizePivotValueForCatalogFacetKey($products_attribute->value);
                if ($facetKey !== null && isset($responseArray['attributes_count'][$products_attribute->field_name][$facetKey]['count'])) {
                    $responseArray['attributes_count'][$products_attribute->field_name][$facetKey]['count']++;
                }
            }
        }

        foreach ($nonSelectedProducts as $product) {
            $testArr = [];
            $valArr = [];
            foreach ($selectedFilterValues as $keyI => $selectedFilterValue) {
                $testArr[] = $keyI;
                if (! is_array($selectedFilterValue)) {
                    continue;
                }
                foreach ($selectedFilterValue as $filterValue) {
                    $valArr[] = $filterValue;
                }
            }

            $tempAddCount = 0;
            $tempField = '';

            foreach ($product->products_attributes as $products_attribute) {
                if (in_array($products_attribute->field_name, $testArr, true)) {
                    if (self::attributeValueMatchesFilter($products_attribute->value, $valArr)) {
                        $tempAddCount++;
                    } else {
                        $tempField = $products_attribute->field_name;
                    }
                }
            }

            if ($tempAddCount === count($testArr) - 1) {
                foreach ($product->products_attributes as $products_attribute) {
                    if ($products_attribute->field_name !== $tempField) {
                        continue;
                    }
                    $facetKey = self::normalizePivotValueForCatalogFacetKey($products_attribute->value);
                    if ($facetKey !== null && isset($responseArray['attributes_count'][$products_attribute->field_name][$facetKey]['count'])) {
                        $responseArray['attributes_count'][$products_attribute->field_name][$facetKey]['count']++;
                    }
                }
            }
        }

        $referer = $request->header('referer');
        $sortParams = [];
        $search = [];
        $prices = [];
        $newUrl = '';
        $listingPath = $request->get('_listing_path');

        if ($listingPath) {
            $newUrl = route('products.category', ['path' => $listingPath]);
        } elseif ($referer) {
            $parsedUrl = parse_url($referer);
            $newUrl = ($parsedUrl['scheme'] ?? '').'://'.($parsedUrl['host'] ?? '').($parsedUrl['path'] ?? '');

            if (isset($parsedUrl['query'])) {
                parse_str($parsedUrl['query'], $queryParams);
                $allowedParams = ['sort_by', 'sort_direction'];
                $sortParams = Arr::only($queryParams, $allowedParams);
            }
        }

        if ($referer !== null && $referer !== '' && preg_match('/\b'.preg_quote(route('search'), '/').'\b/u', $referer)) {
            if ($request->get('search')) {
                $search = ['search' => $request->get('search')];
            }
            if ($request->get('category_id')) {
                $search += ['category_id' => $request->get('category_id')];
            }
            if ($request->get('sub_category')) {
                $search += ['sub_category' => $request->get('sub_category')];
            }
            if ($request->get('description')) {
                $search += ['description' => $request->get('description')];
            }
        }

        if ((int) $request->get('min_price') !== 0 && (int) $request->get('max_price') !== 0) {
            $prices = [
                'min_price' => $request->get('min_price'),
                'max_price' => $request->get('max_price'),
            ];
        }

        $merged = array_merge($search, $prices, $selectedFilterValues, $sortParams);

        if ($request->get('in_stock')) {
            $inStock = [
                'in_stock' => $request->get('in_stock'),
            ];
            $merged = array_merge($merged, $inStock);
        }

        if (http_build_query($merged) !== '') {
            $responseArray['new_url'] = $newUrl.'?'.http_build_query($merged);
        } else {
            $responseArray['new_url'] = $newUrl;
        }

        return $responseArray;

    }

    /**
     * Клієнтський filters (ключі як у data-filter, напр. «3M») + серверний каркас лічильників (ключі facet, напр. «3m»).
     *
     * @param  array<string, array<string, array{active?: bool|string}>>  $clientFilters
     * @param  array<string, array<string, array{count?: int}>>  $arrAttr
     * @return array<string, array<string, array{count: int, active: bool}>>
     */
    public static function mergeClientFiltersIntoAttributesCount(array $clientFilters, array $arrAttr): array
    {
        $out = [];

        foreach ($arrAttr as $field => $items) {
            if (! is_array($items)) {
                continue;
            }
            foreach ($items as $facetKey => $data) {
                $out[$field][$facetKey] = [
                    'count' => (int) ($data['count'] ?? 0),
                    'active' => false,
                ];
            }
        }

        foreach ($clientFilters as $field => $values) {
            if (! is_array($values)) {
                continue;
            }
            foreach ($values as $displayValue => $state) {
                $facetKey = self::normalizePivotValueForCatalogFacetKey($displayValue);
                if ($facetKey === null) {
                    continue;
                }
                if (! isset($out[$field][$facetKey])) {
                    $out[$field][$facetKey] = ['count' => 0, 'active' => false];
                }
                $active = is_array($state) ? ($state['active'] ?? false) : false;
                $out[$field][$facetKey]['active'] = filter_var($active, FILTER_VALIDATE_BOOLEAN);
            }
        }

        return $out;
    }

    /**
     * Лічильники для /catalog без повного завантаження всіх товарів (лише total + new_url).
     *
     * @param  array<string, array<string, array<string, mixed>>>  $attributesArray
     */
    public static function getCatalogRootListingCountsResponse(int $total, $request, array $selectedFilterValues, array $attributesArray): array
    {
        $responseArray = [
            'attributes_count' => $attributesArray,
        ];

        foreach ($responseArray['attributes_count'] as $key_i => $item) {
            foreach ($item as $key_j => $attribute) {
                if (! array_key_exists('count', (array) $responseArray['attributes_count'][$key_i][$key_j])) {
                    (array) $responseArray['attributes_count'][$key_i][$key_j]['count'] = 0;
                }
            }
        }

        $responseArray['total_count'] = __('product-index.show_products.'.Pluralize::getDeclension($total, App::getLocale()), ['count' => $total]);

        $referer = $request->header('referer');
        $sortParams = [];
        $search = [];
        $prices = [];
        $newUrl = '';

        if ($referer) {
            $parsedUrl = parse_url($referer);
            $newUrl = ($parsedUrl['scheme'] ?? '').'://'.($parsedUrl['host'] ?? '').($parsedUrl['path'] ?? '');

            if (isset($parsedUrl['query'])) {
                parse_str($parsedUrl['query'], $queryParams);
                $allowedParams = ['sort_by', 'sort_direction'];
                $sortParams = Arr::only($queryParams, $allowedParams);
            }
        }

        if ($referer !== null && $referer !== '' && preg_match('/\b'.preg_quote(route('search'), '/').'\b/u', $referer)) {
            if ($request->get('search')) {
                $search = ['search' => $request->get('search')];
            }
            if ($request->get('category_id')) {
                $search += ['category_id' => $request->get('category_id')];
            }
            if ($request->get('sub_category')) {
                $search += ['sub_category' => $request->get('sub_category')];
            }
            if ($request->get('description')) {
                $search += ['description' => $request->get('description')];
            }
        }

        if ((int) $request->get('min_price') !== 0 && (int) $request->get('max_price') !== 0) {
            $prices = [
                'min_price' => $request->get('min_price'),
                'max_price' => $request->get('max_price'),
            ];
        }

        $merged = array_merge($search, $prices, $selectedFilterValues, $sortParams);

        if ($request->get('in_stock')) {
            $merged = array_merge($merged, ['in_stock' => $request->get('in_stock')]);
        }

        if (http_build_query($merged) !== '') {
            $responseArray['new_url'] = $newUrl.'?'.http_build_query($merged);
        } else {
            $responseArray['new_url'] = $newUrl;
        }

        return $responseArray;
    }

    /**
     * Текст значення атрибута для відображення в фільтрі (локаль сайту або задана).
     */
    public static function extractPivotValueForDisplay(mixed $rawValue, ?string $locale = null): ?string
    {
        if ($rawValue === null) {
            return null;
        }

        $locale = $locale ?? App::getLocale();

        if (is_array($rawValue)) {
            $v = $rawValue[$locale] ?? reset($rawValue);

            return $v !== null && $v !== '' ? trim((string) $v) : null;
        }

        if (is_string($rawValue)) {
            $decoded = json_decode($rawValue, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $v = $decoded[$locale] ?? reset($decoded);

                return $v !== null && $v !== '' ? trim((string) $v) : null;
            }

            $trimmed = trim($rawValue);

            return $trimmed !== '' ? $trimmed : null;
        }

        $s = trim((string) $rawValue);

        return $s !== '' ? $s : null;
    }

    /**
     * Ключ для групування лічильників OCF (регістр ігнорується).
     */
    public static function normalizePivotValueForCatalogFacetKey(mixed $rawValue, ?string $locale = null): ?string
    {
        $display = self::extractPivotValueForDisplay($rawValue, $locale);

        return $display !== null && $display !== '' ? mb_strtolower($display) : null;
    }

    /**
     * Один варіант написання для UI, якщо в БД є дублі лише за регістром.
     *
     * @param  \Illuminate\Support\Collection<int, string>|array<int, string>  $variants
     */
    public static function pickCanonicalAttributeDisplayValue(Collection|array $variants): string
    {
        $variants = collect($variants)
            ->map(fn ($v) => trim((string) $v))
            ->filter(fn ($v) => $v !== '')
            ->unique()
            ->values();

        if ($variants->isEmpty()) {
            return '';
        }

        $capitalized = $variants->first(fn ($v) => preg_match('/^\p{Lu}/u', $v) === 1);

        return $capitalized ?? $variants->sortByDesc(fn ($v) => mb_strlen($v))->first();
    }

    /**
     * Список атрибутів і лічильники для /catalog без завантаження всіх товарів у пам’ять (чанки по id).
     *
     * @return array{attributes: \Illuminate\Support\Collection<int, Attribute>, attributes_count: array<string, array<string, array{count: int}>>}
     */
    public static function buildCatalogRootFacetData(Builder $catalogJoinedProductsQuery): array
    {
        $excludedFieldNames = [
            'name',
            'application',
            'purpose',
            'benefits',
            'default_quantity',
            'master_qualification',
            'store_terms',
            'warranty',
            'quantity_step',
            'min_order_quantity',
            'first_stock',
            'second_stock',
            'third_stock',
            'under_order',
        ];

        $attributes = Attribute::query()
            ->where('is_visible', true)
            ->whereNotIn('field_name', $excludedFieldNames)
            ->orderBy('id')
            ->get();

        $attributesArray = [];
        foreach ($attributes as $attribute) {
            foreach ($attribute->getPivotValue() as $value) {
                $facetKey = self::normalizePivotValueForCatalogFacetKey($value);
                if ($facetKey === null) {
                    continue;
                }
                $attributesArray[$attribute->field_name][$facetKey]['count'] = 0;
            }
        }

        $idSub = $catalogJoinedProductsQuery->clone()->reorder()->select('products.id');

        DB::query()
            ->fromSub($idSub->toBase(), 'catalog_product_ids')
            ->orderBy('catalog_product_ids.id')
            ->chunk(400, function ($rows) use (&$attributesArray) {
                $ids = collect($rows)->pluck('id')->map(fn ($id) => (int) $id)->unique()->values()->all();
                if ($ids === []) {
                    return;
                }

                $chunk = Product::query()
                    ->whereIn('id', $ids)
                    ->with(['products_attributes' => function ($query) {
                        $query->join('attributes', 'products_attributes.attribute_id', '=', 'attributes.id')
                            ->select('products_attributes.*', 'attributes.field_name as attribute_field_name');
                    }])
                    ->get();

                foreach ($chunk as $product) {
                    foreach ($product->products_attributes as $pa) {
                        $field = $pa->attribute_field_name ?? $pa->field_name ?? null;
                        if ($field === null || ! isset($attributesArray[$field])) {
                            continue;
                        }
                        $keyVal = self::normalizePivotValueForCatalogFacetKey($pa->value);
                        if ($keyVal === null || ! array_key_exists($keyVal, $attributesArray[$field])) {
                            continue;
                        }
                        $attributesArray[$field][$keyVal]['count']++;
                    }
                }
            });

        return [
            'attributes' => $attributes,
            'attributes_count' => $attributesArray,
        ];
    }

    /**
     * Середній рейтинг у шкалі 0–5 для зірок/UI (у БД інколи зберігають 0–100 як «відсотки»).
     */
    public static function normalizeReviewAverageForFiveStarScale(float $average, float $maxIndividualRating): float
    {
        if ($maxIndividualRating > 5.01 || $average > 5.01) {
            $average /= 20.0;
        }

        return min(5.0, max(0.0, $average));
    }

    /**
     * aggregateRating для JSON-LD (Google: ratingValue у межах worstRating..bestRating). Без відгуків — null.
     *
     * @return array<string, mixed>|null
     */
    public static function buildAggregateRatingJsonLd(float $averageRaw, int $reviewCount, float $maxIndividualRating): ?array
    {
        if ($reviewCount < 1) {
            return null;
        }

        $normalized = self::normalizeReviewAverageForFiveStarScale($averageRaw, $maxIndividualRating);
        $ratingValue = round(min(5.0, max(1.0, $normalized)), 1);

        return [
            '@type' => 'AggregateRating',
            'ratingValue' => $ratingValue,
            'bestRating' => 5,
            'worstRating' => 1,
            'reviewCount' => $reviewCount,
        ];
    }

    /**
     * Кількість заповнених зірок (1–5) для одного відгуку з БД (легасі: значення 0–100).
     */
    public static function reviewRatingStarsCount(float $storedRating): int
    {
        $n = (int) round(self::normalizeReviewAverageForFiveStarScale($storedRating, $storedRating));

        return min(5, max(0, $n));
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug->en';
    }

    public function getBenefits()
    {
        return $this->attributes()->where('field_name', 'benefits')->first()?->pivot?->value;
    }

    public function getApplication()
    {
        return $this->attributes()->where('field_name', 'application')->first()?->pivot?->value;
    }

    public function getDefaultQuantity()
    {
        return $this->attributes()->where('field_name', 'default_quantity')->first()?->pivot?->value ?? 1;
    }

    public function getFirstStock()
    {
        return $this->attributes()->where('field_name', 'first_stock')->first()?->pivot?->value;
    }

    public function getSecondStock()
    {
        return $this->attributes()->where('field_name', 'second_stock')->first()?->pivot?->value;
    }

    public function getThirdStock()
    {
        return $this->attributes()->where('field_name', 'third_stock')->first()?->pivot?->value;
    }

    public function getStock(): float
    {
        return (float) $this->stock;
    }

    public function getSmallPrice(): float
    {
        return $this->prices()->whereHas('type', function ($query) {
            $query->where('price_types.external_id', 'bb2a9b0f-26f6-11ee-0a80-0f50000d072f');
        })->value('price') * self::getCurrencyRate();
    }

    public function getBigPrice(): float
    {
        return $this->prices()->whereHas('type', function ($query) {
            $query->where('price_types.external_id', 'bb2a9b91-26f6-11ee-0a80-0f50000d0730');
        })->value('price') * self::getCurrencyRate();
    }

    public function getWarranty()
    {
        return $this->attributes()->where('field_name', 'warranty')->first()?->pivot?->value;
    }

    public function getStoreTerms()
    {
        return $this->attributes()->where('field_name', 'store_terms')->first()?->pivot?->value;
    }

    public function getMainColor()
    {
        return $this->attributes()->where('field_name', 'main_shade')->first()?->pivot?->value;
    }

    public function getBrand()
    {
        return $this->attributes()->where('field_name', 'brand')->first()?->pivot?->value;
    }

    public function getRoomTemperature()
    {
        return $this->attributes()->where('field_name', 'room_temperature')->first()?->pivot?->value;
    }

    public function getMasterQualification()
    {
        return $this->attributes()->where('field_name', 'master_qualification')->first()?->pivot?->value;
    }

    public function getPriceByDollars($price)
    {
        return round($price / self::getCurrencyRate(), 0);
    }

    //    public function customBlocks()
    //    {
    //        return $this->belongsToMany(CustomBlock::class);
    //    }

    public function getMinOrderCount()
    {
        return $this->attributes()->where('field_name', 'min_order_quantity')->first()?->pivot?->value ?? 1;
    }

    public function getOrderStep()
    {
        return $this->attributes()->where('field_name', 'quantity_step')->first()?->pivot?->value ?? 1;
    }

    public function getUnderOrder()
    {
        return $this->attributes()->where('field_name', 'under_order')->first()?->pivot?->value;
    }

    public function getRollSize()
    {
        return $this->attributes()->where('field_name', 'roll_size')->first()?->pivot?->value;
    }

    public function getStructure()
    {
        return $this->attributes()->where('field_name', 'structure')->first();
    }

    public function getPurpose()
    {
        return $this->attributes()->where('field_name', 'purpose')->first();
    }

    public function getName()
    {
        return $this->attributes()->where('field_name', 'name')->first()?->pivot?->value
            ?? $this->name;
    }

    public function getType()
    {
        return $this->attributes()->where('field_name', 'type')->first();
    }

    /**
     * @throws \JsonException
     */
    public function getPriceByCount($count)
    {
        $productPrice = $this->getPrice();

        if ($this->getRollSize()) {

            if ($count >= $this->getSecondStock() && $count <= $this->getThirdStock()) {
                $productPrice = $this->getSmallPrice();
            }

            if ($count >= $this->getThirdStock()) {
                $productPrice = $this->getBigPrice();
            }

            return $productPrice * $count;
        }

        return $productPrice * $count;
    }

    /**
     * Возвращает цену за единицу в UAH с учётом количества (ценовые пороги).
     */
    public function getUnitPriceForQuantity(int $count): float
    {
        $unitPrice = $this->getPrice();

        if ($this->getRollSize()) {
            if ($count >= $this->getThirdStock()) {
                $unitPrice = $this->getBigPrice();
            } elseif ($count >= $this->getSecondStock() && $count <= $this->getThirdStock()) {
                $unitPrice = $this->getSmallPrice();
            }
        }

        return $unitPrice;
    }

    public static function getCurrencyRate()
    {
        $currency = Setting::query()->value('currency');

        return $currency ?? 42;
    }

    public function volumeVariants()
    {
        return $this->belongsToMany(Product::class, 'product_volume_variants', 'product_id', 'variant_product_id');
    }

    public function isVolumeVariantOf()
    {
        return $this->belongsToMany(Product::class, 'product_volume_variants', 'variant_product_id', 'product_id');
    }

    public function allVolumeVariants()
    {
        return $this->volumeVariants->merge($this->isVolumeVariantOf);
    }

    public function hasVolumeAttribute()
    {
        return $this->attributes()->where('field_name', 'volume')->exists();
    }

    public function scopeHasVolume($query)
    {
        return $query->whereHas('attributes', function ($q) {
            $q->where('field_name', 'volume');
        });
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getIsTranslatedAttribute(): bool
    {
        $nameRu = $this->getTranslation('name', 'ru');
        $nameEn = $this->getTranslation('name', 'en');
        $descRu = $this->getTranslation('descriptions', 'ru');
        $descEn = $this->getTranslation('descriptions', 'en');

        // Проверяем основные поля продукта
        if (
            empty(strip_tags($nameRu)) ||
            empty(strip_tags($nameEn)) ||
            empty(strip_tags($descRu)) ||
            empty(strip_tags($descEn))
        ) {
            return false;
        }

        if (
            trim(strip_tags($nameRu)) === trim(strip_tags($nameEn)) ||
            trim(strip_tags($descRu)) === trim(strip_tags($descEn))
        ) {
            return false;
        }

        // Проверяем переводы атрибутов
        $productAttributes = $this->products_attributes()->get();
        foreach ($productAttributes as $attribute) {
            $value = $attribute->value;

            // Если значение не является массивом (не переведено), пропускаем
            if (! is_array($value)) {
                continue;
            }

            $valueRu = $value['ru'] ?? '';
            $valueEn = $value['en'] ?? '';

            // Если хотя бы один атрибут не переведен, продукт считается не переведенным
            if (empty($valueRu) || empty($valueEn)) {
                return false;
            }

            // Если переводы одинаковые, продукт считается не переведенным
            if (trim($valueRu) === trim($valueEn)) {
                return false;
            }
        }

        return true;
    }
}
