@props(['category'])
@php
    $locale = app()->getLocale();
    $categoryPath = $category->catalogPath($locale);
    $categoryLabel = $category->menuLabel($locale);
    $categoryIcon = $category->resolveMenuIconUrl();
    $children = $category->children ?? collect();
    $hasChildren = $children->isNotEmpty();
@endphp

@if($hasChildren)
    <li class="item head-catalog-mob__item has-children">
        <div class="head-catalog-mob__row">
            <a href="{{ route('products.category', ['path' => $categoryPath]) }}"
               class="head-catalog-mob__link"
               title="{{ $categoryLabel }}">
                <span class="head-catalog-mob__icon flex-center">
                    <img
                        loading="lazy"
                        src="{{ filled($categoryIcon) ? $categoryIcon : asset('assets/img/logo.svg') }}"
                        alt=""
                        width="50"
                        height="30"
                    >
                </span>
                <span class="head-catalog-mob__title">{{ $categoryLabel }}</span>
            </a>
            <button type="button"
                    class="head-catalog-mob__toggle"
                    aria-expanded="false"
                    aria-label="{{ $categoryLabel }}">
                <span class="head-catalog-mob__chevron" aria-hidden="true"></span>
            </button>
        </div>
        <ul class="head-catalog-mob__sublist">
            <li class="head-catalog-mob__subitem">
                <a href="{{ route('products.category', ['path' => $categoryPath]) }}"
                   class="head-catalog-mob__sublink"
                   title="{{ $categoryLabel }}">
                    <span class="head-catalog-mob__subtext">{{ $categoryLabel }}</span>
                </a>
            </li>
            @foreach($children as $childCategory)
                @php
                    $childCategory->setRelation('parent', $category);
                    $childPath = $childCategory->catalogPath($locale);
                    $childLabel = $childCategory->menuLabel($locale);
                    $grandChildren = $childCategory->children ?? collect();
                    $hasGrandChildren = $grandChildren->isNotEmpty();
                @endphp
                <li class="head-catalog-mob__subitem{{ $hasGrandChildren ? ' has-children' : '' }}">
                    @if($hasGrandChildren)
                        <button type="button"
                                class="head-catalog-mob__sublink head-catalog-mob__subtoggle"
                                aria-expanded="false"
                                title="{{ $childLabel }}">
                            <span class="head-catalog-mob__subtext">{{ $childLabel }}</span>
                            <span class="head-catalog-mob__chevron" aria-hidden="true"></span>
                        </button>
                        <ul class="head-catalog-mob__nested">
                            <li>
                                <a href="{{ route('products.category', ['path' => $childPath]) }}"
                                   class="head-catalog-mob__nestedlink"
                                   title="{{ $childLabel }}">
                                    {{ $childLabel }}
                                </a>
                            </li>
                            @foreach($grandChildren as $grandChild)
                                @php
                                    $grandChild->setRelation('parent', $childCategory);
                                    $grandPath = $grandChild->catalogPath($locale);
                                    $grandLabel = $grandChild->menuLabel($locale);
                                @endphp
                                <li>
                                    <a href="{{ route('products.category', ['path' => $grandPath]) }}"
                                       class="head-catalog-mob__nestedlink"
                                       title="{{ $grandLabel }}">
                                        {{ $grandLabel }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <a href="{{ route('products.category', ['path' => $childPath]) }}"
                           class="head-catalog-mob__sublink"
                           title="{{ $childLabel }}">
                            <span class="head-catalog-mob__subtext">{{ $childLabel }}</span>
                        </a>
                    @endif
                </li>
            @endforeach
        </ul>
    </li>
@else
    <li class="item head-catalog-mob__item">
        <div class="head-catalog-mob__row">
            <a href="{{ route('products.category', ['path' => $categoryPath]) }}"
               class="head-catalog-mob__link"
               title="{{ $categoryLabel }}">
                <span class="head-catalog-mob__icon flex-center">
                    <img
                        loading="lazy"
                        src="{{ filled($categoryIcon) ? $categoryIcon : asset('assets/img/logo.svg') }}"
                        alt=""
                        width="50"
                        height="30"
                    >
                </span>
                <span class="head-catalog-mob__title">{{ $categoryLabel }}</span>
            </a>
        </div>
    </li>
@endif
