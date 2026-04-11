@php
    $locale = app()->getLocale();
    $currentUrl = url()->current();
    $siteName = 'Wrap.Shop';
    $siteDescription = 'Інтернет-магазин плівок для автомобілів, матеріалів для детейлінгу та тюнінгу';
    $isHome = \Illuminate\Support\Facades\Route::currentRouteName() === 'index';

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
    {{-- Category JSON-LD (CollectionPage) --}}
    @php
        $categoryDescription = $category->content ?? $category->seo_text ?? ($category->name . ' - ' . $siteDescription);
        $categoryDescription = strip_tags($categoryDescription);

        $categoryFaqs = \App\Models\Faq::active()
            ->ordered()
            ->get();

        $categoryFaqJson = null;
        if ($categoryFaqs->isNotEmpty()) {
            $categoryFaqJson = [
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => $categoryFaqs->map(function (\App\Models\Faq $faq) use ($locale) {
                    return [
                        '@type' => 'Question',
                        'name' => $faq->getTranslation('question', $locale),
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => strip_tags($faq->getTranslation('answer', $locale)),
                        ],
                    ];
                })->toArray(),
            ];
        }
    @endphp
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "CollectionPage",
        "name": "{{ $category->name }}",
        "description": "{{ $categoryDescription }}",
        "url": "{{ $currentUrl }}",
        "about": {
            "@type": "Thing",
            "name": "{{ $category->name }}"
        },
        "publisher": {
            "@type": "Organization",
            "name": "{{ $siteName }}",
            "logo": {
                "@type": "ImageObject",
                "url": "{{ url('assets/img/logo.png') }}"
            }
        },
        "mainEntity": {
            "@type": "ItemList",
            "name": "{{ $category->name }}",
            "description": "{{ $locale === 'ru' ? 'Товары в категории' : ($locale === 'en' ? 'Products in category' : 'Товари в категорії') }} {{ $category->name }}"
        }
    }
    </script>
    @if($categoryFaqJson)
        <script type="application/ld+json">
            {!! json_encode($categoryFaqJson, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
        </script>
    @endif
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
            "telephone": "{{ $settings?->phone_view ?: ($settings?->phone ?: '+38-066-000-32-02') }}",
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
    @php
        $homeFaqJson = null;
        if ($isHome) {
            $homeFaqs = \App\Models\Faq::active()
                ->ordered()
                ->get();

            if ($homeFaqs->isNotEmpty()) {
                $homeFaqJson = [
                    '@context' => 'https://schema.org',
                    '@type' => 'FAQPage',
                    'mainEntity' => $homeFaqs->map(function (\App\Models\Faq $faq) use ($locale) {
                        return [
                            '@type' => 'Question',
                            'name' => $faq->getTranslation('question', $locale),
                            'acceptedAnswer' => [
                                '@type' => 'Answer',
                                'text' => strip_tags($faq->getTranslation('answer', $locale)),
                            ],
                        ];
                    })->toArray(),
                ];
            }
        }
    @endphp
    @if($homeFaqJson)
        <script type="application/ld+json">
            {!! json_encode($homeFaqJson, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
        </script>
    @endif
@endif
