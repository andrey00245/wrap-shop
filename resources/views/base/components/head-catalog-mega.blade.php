@php
    /** @var \Illuminate\Support\Collection<int, \App\Models\Category> $productCategories */
    $productCategories = ($productCategories ?? collect())->values();
    $locale = app()->getLocale();
    $visibleChildrenLimit = 5;
    $promoImage = asset('assets/img/catalog/mega-promo.jpg');

    $promoCategory = $productCategories->first();
    $promoTitle = '';
    $promoUrl = '#';
    $promoCards = [];

    if ($promoCategory) {
        $promoTitle = $promoCategory->name;
        $promoUrl = route('products.category', ['path' => $promoCategory->catalogPath($locale)]);
        $promoCards = $promoCategory->menuSpotlightCards();
    }
@endphp

<nav class="head-catalog-mega" id="headCatalogMega" aria-label="{{ __('header_footer.catalog_products') }}" hidden>
    <div class="head-catalog-mega__panel">
        <div class="head-catalog-mega__inner">
            <div class="head-catalog-mega__layout">
                <div class="head-catalog-mega__grid">
                    @foreach($productCategories as $category)
                        @php
                            $categoryPath = $category->catalogPath($locale);
                            $resolvedMenuIcon = $category->resolveMenuIconUrl();
                            $categoryImage = filled($resolvedMenuIcon)
                                ? $resolvedMenuIcon
                                : asset('assets/img/logo.svg');
                            $children = $category->children ?? collect();
                            $hasMoreChildren = $children->count() > $visibleChildrenLimit;
                            $spotlightCards = $category->menuSpotlightCards();
                        @endphp

                        <section
                            class="head-catalog-mega__group"
                            data-promo-title="{{ $category->name }}"
                            data-promo-url="{{ route('products.category', ['path' => $categoryPath]) }}"
                            data-promo-products='@json($spotlightCards)'
                        >
                            <a href="{{ route('products.category', ['path' => $categoryPath]) }}"
                               class="head-catalog-mega__heading"
                               title="{{ $category->name }}">
                                <span class="head-catalog-mega__icon flex-center">
                                    <img
                                        src="{{ $categoryImage }}"
                                        alt=""
                                        width="83"
                                        height="50"
                                        loading="lazy"
                                    >
                                </span>
                                <span class="head-catalog-mega__title">{{ $category->name }}</span>
                            </a>

                            @if($children->isNotEmpty())
                                <ul class="head-catalog-mega__list">
                                    @foreach($children as $index => $child)
                                        @php
                                            $child->setRelation('parent', $category);
                                            $childPath = $child->catalogPath($locale);
                                            $grandChildren = $child->children ?? collect();
                                            $hasGrandChildren = $grandChildren->isNotEmpty();
                                            $isExtra = $index >= $visibleChildrenLimit;
                                        @endphp
                                        <li class="head-catalog-mega__item{{ $hasGrandChildren ? ' has-children' : '' }}{{ $isExtra ? ' is-extra' : '' }}">
                                            @if($hasGrandChildren)
                                                <button type="button"
                                                        class="head-catalog-mega__link head-catalog-mega__toggle"
                                                        aria-expanded="false"
                                                        title="{{ $child->name }}">
                                                    <span class="head-catalog-mega__link-text">{{ $child->name }}</span>
                                                    <span class="head-catalog-mega__chevron" aria-hidden="true"></span>
                                                </button>
                                                <ul class="head-catalog-mega__sublist">
                                                    <li class="head-catalog-mega__subitem">
                                                        <a href="{{ route('products.category', ['path' => $childPath]) }}"
                                                           class="head-catalog-mega__sublink"
                                                           title="{{ $child->name }}">
                                                            {{ $child->name }}
                                                        </a>
                                                    </li>
                                                    @foreach($grandChildren as $grandChild)
                                                        @php
                                                            $grandChild->setRelation('parent', $child);
                                                            $grandPath = $grandChild->catalogPath($locale);
                                                        @endphp
                                                        <li class="head-catalog-mega__subitem">
                                                            <a href="{{ route('products.category', ['path' => $grandPath]) }}"
                                                               class="head-catalog-mega__sublink"
                                                               title="{{ $grandChild->name }}">
                                                                {{ $grandChild->name }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <a href="{{ route('products.category', ['path' => $childPath]) }}"
                                                   class="head-catalog-mega__link"
                                                   title="{{ $child->name }}">
                                                    <span class="head-catalog-mega__link-text">{{ $child->name }}</span>
                                                </a>
                                            @endif
                                        </li>
                                    @endforeach

                                    @if($hasMoreChildren)
                                        <li class="head-catalog-mega__item head-catalog-mega__item--more">
                                            <button
                                                type="button"
                                                class="head-catalog-mega__more"
                                                aria-expanded="false"
                                                data-label-more="{{ __('header_footer.catalog_show_more') }}"
                                                data-label-less="{{ __('header_footer.catalog_show_less') }}"
                                            >
                                                <span class="head-catalog-mega__more-text">{{ __('header_footer.catalog_show_more') }}</span>
                                                <span class="head-catalog-mega__more-arrow" aria-hidden="true"></span>
                                            </button>
                                        </li>
                                    @endif
                                </ul>
                            @endif
                        </section>
                    @endforeach
                </div>

                @if($promoCategory)
                    <aside
                        class="head-catalog-mega__promo"
                        data-default-title="{{ $promoTitle }}"
                        data-default-url="{{ $promoUrl }}"
                        data-default-products='@json($promoCards)'
                    >
                        <div class="head-catalog-mega__promo-media">
                            <img
                                class="head-catalog-mega__promo-image"
                                src="{{ $promoImage }}"
                                alt="Wrap Shop"
                                width="300"
                                height="200"
                                loading="lazy"
                            >
                        </div>
                        <div class="head-catalog-mega__promo-body">
                            <p class="head-catalog-mega__promo-title">{{ $promoTitle }}</p>

                            <div class="head-catalog-mega__promo-products{{ count($promoCards) === 0 ? ' is-empty' : '' }}">
                                @for($i = 0; $i < 5; $i++)
                                    @php $card = $promoCards[$i] ?? null; @endphp
                                    <a
                                        href="{{ $card['url'] ?? '#' }}"
                                        class="head-catalog-mega__promo-product{{ empty($card) ? ' is-empty' : '' }}"
                                        title="{{ $card['name'] ?? '' }}"
                                        data-promo-slot="{{ $i }}"
                                    >
                                        <span class="head-catalog-mega__promo-product-thumb flex-center">
                                            <img
                                                class="head-catalog-mega__promo-product-image"
                                                src="{{ $card['image'] ?? '' }}"
                                                alt=""
                                                width="48"
                                                height="48"
                                                loading="lazy"
                                            >
                                        </span>
                                        <span class="head-catalog-mega__promo-product-meta">
                                            <span class="head-catalog-mega__promo-product-name">{{ $card['name'] ?? '' }}</span>
                                            <span class="head-catalog-mega__promo-product-price{{ empty($card['price'] ?? '') ? ' is-empty' : '' }}">{{ $card['price'] ?? '' }}</span>
                                        </span>
                                    </a>
                                @endfor
                            </div>

                            <a href="{{ $promoUrl }}" class="head-catalog-mega__promo-cta">
                                {{ __('header_footer.catalog_promo_cta') }}
                            </a>
                        </div>
                    </aside>
                @endif
            </div>
        </div>
    </div>
</nav>
