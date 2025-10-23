@php
    $locale = app()->getLocale();
    $currentUrl = url()->current();
    $siteName = 'Wrap.Shop';
    $siteDescription = 'Інтернет-магазин плівок для автомобілів, матеріалів для детейлінгу та тюнінгу';

    if ($locale === 'ru') {
        $siteDescription = 'Интернет-магазин пленок для автомобилей, материалов для детейлинга и тюнинга';
    } elseif ($locale === 'en') {
        $siteDescription = 'Online store of car wraps, detailing materials and tuning';
    }
@endphp

@if(isset($product))
    {{-- Product JSON-LD --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Product",
        "name": "{{ $product->getName() }}",
        "description": "{{ strip_tags($product->descriptions) }}",
        "sku": "{{ $product->code }}",
        "brand": {
            "@type": "Brand",
            "name": "Wrap.Shop"
        },
        "category": "{{ $product->category?->name ?? '' }}",
        "offers": {
            "@type": "Offer",
            "price": "{{ $product->getPrice() }}",
            "priceCurrency": "UAH",
            "availability": "{{ $product->getStock() > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}",
            "seller": {
                "@type": "Organization",
                "name": "Wrap.Shop"
            }
        },
        "image": [
            @foreach($product->getMedia('images') as $key => $image)
                "{{ $image->getUrl() }}"{{ $key < $product->getMedia('images')->count() - 1 ? ',' : '' }}
            @endforeach
        ],
      "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "{{ min($average ?? 1, 5) }}",
    "reviewCount": "{{ $count ?? 0 }}"
       }
    }
    </script>
@elseif(isset($category))
    {{-- Category JSON-LD --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "CollectionPage",
        "name": "{{ $category->name }}",
        "description": "{{ $category->name }} - {{ $siteDescription }}",
        "url": "{{ $currentUrl }}",
        "mainEntity": {
            "@type": "ItemList",
            "name": "{{ $category->name }}",
            "description": "Товари в категорії {{ $category->name }}"
        }
    }
    </script>
@elseif(isset($news))
    {{-- News Article JSON-LD --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Article",
        "headline": "{{ $news->title }}",
        "description": "{{ strip_tags($news->description) }}",
        "datePublished": "{{ $news->created_at->toISOString() }}",
        "dateModified": "{{ $news->updated_at->toISOString() }}",
        "author": {
            "@type": "Organization",
            "name": "Wrap.Shop"
        },
        "publisher": {
            "@type": "Organization",
            "name": "Wrap.Shop",
            "logo": {
                "@type": "ImageObject",
                "url": "{{ url('assets/img/logo.png') }}"
            }
        },
        "url": "{{ $currentUrl }}"
    }
    </script>
@else
    {{-- Organization JSON-LD --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "{{ $siteName }}",
        "description": "{{ $siteDescription }}",
        "url": "{{ url('/') }}",
        "logo": "{{ url('assets/img/logo.png') }}",
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "+38-066-000-32-02",
            "contactType": "customer service",
            "areaServed": "UA",
            "availableLanguage": ["Ukrainian", "Russian", "English"]
        },
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "Вул. Ізюмська 5А",
            "addressLocality": "Київ",
            "addressCountry": "UA"
        },
        "sameAs": [
            "https://www.instagram.com/wrap.shop/",
            "https://t.me/wrapshop"
        ]
    }
    </script>
@endif
