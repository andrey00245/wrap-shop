<nav class="home-category wrap" aria-label="{{ __('header_footer.catalog_products') }}">
    <ul class="home-category__list">
        @foreach($mainCategories as $mainCategory)
            @php
                $categoryPath = $mainCategory->catalogPath(app()->getLocale());
                $categoryIcon = $mainCategory->getPreviewImage();
                if (!filled($categoryIcon)) {
                    $categoryIcon = $mainCategory->getImage();
                }
            @endphp

            @continue(!filled($categoryPath))

            <li class="home-category__cell">
                <a href="{{ route('products.category', ['path' => $categoryPath]) }}"
                   class="home-category-item"
                   title="{{ $mainCategory->name }}">
                    <figure class="home-category-item__icon flex-center">
                        <img
                            loading="lazy"
                            src="{{ filled($categoryIcon) ? $categoryIcon : asset('assets/img/logo.svg') }}"
                            alt="{{ $mainCategory->name }}"
                            width="48"
                            height="48">
                    </figure>
                    <span class="home-category-item__title">{{ $mainCategory->name }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</nav>
