@php
    $bannerLinks = config('home.banner_links', []);
    $currentUrl = rtrim(request()->url(), '/');
@endphp
@if(count($bannerLinks) > 0)
<nav class="banner-links-strip wrap" aria-label="{{ __('home.banner_links_label') }}">
    <ul class="banner-links-strip__list">
        @foreach($bannerLinks as $item)
            @php
                $href = isset($item['route'])
                    ? route($item['route'], $item['params'] ?? [])
                    : ($item['url'] ?? '#');
                $label = __('home.banner_links.' . $item['label']);
                $isActive = rtrim($currentUrl, '/') === rtrim($href, '/');
            @endphp
            <li class="banner-links-strip__item">
                <a href="{{ $href }}" class="banner-links-strip__link{{ $isActive ? ' is-active' : '' }}">{{ $label }}</a>
            </li>
        @endforeach
    </ul>
</nav>
@endif
