@extends('base.layouts.app')

@php
    $locale = app()->getLocale();
    $implementationsTitle = __('implementations.title') . ' | Wrap.Shop';
    $implementationsDescription = 'Наші реалізації - приклади робіт з плівками для автомобілів, детейлінгу та тюнінгу. Професійний підхід, якісні матеріали, унікальні рішення.';

    if ($locale === 'ru') {
        $implementationsDescription = 'Наши реализации - примеры работ с пленками для автомобилей, детейлинга и тюнинга. Профессиональный подход, качественные материалы, уникальные решения.';
    } elseif ($locale === 'en') {
        $implementationsDescription = 'Our implementations - examples of work with car wraps, detailing and tuning. Professional approach, quality materials, unique solutions.';
    }
@endphp

@section('title', $implementationsTitle)
@section('description', $implementationsDescription)
@section('keywords', 'реалізації, приклади робіт, плівки для авто, детейлінг, тюнінг, wrap shop')
@section('og_type', 'website')
@section('og_title', $implementationsTitle)
@section('og_description', $implementationsDescription)
@section('og_image', url('assets/img/og-default.jpg'))
@section('og_url', url()->current())
@section('twitter_card', 'summary_large_image')
@section('twitter_title', $implementationsTitle)
@section('twitter_description', $implementationsDescription)
@section('twitter_image', url('assets/img/og-default.jpg'))
@section('canonical', url()->current())

@push('styles')
    @if($theme ==='dark')
        <link rel="stylesheet" type="text/css" href="{{mix('build/css/style-blog-dark.css')}}">
        <link rel="stylesheet" type="text/css" href="{{mix('build/css/style-category-dark.css')}}">
    @else
        <link rel="stylesheet" type="text/css" href="{{mix('build/css/style-blog-light.css')}}">
        <link rel="stylesheet" type="text/css" href="{{mix('build/css/style-category-light.css')}}">
    @endif
    <link rel="stylesheet" type="text/css" href="{{mix('build/css/style-implementations.css')}}">
@endpush

@section('content')

@php
    // Собираем уникальные категории из прикреплённых к реализациям товаров
    $implementationCategories = $implementations
        ->pluck('product.category')
        ->filter()
        ->unique('id')
        ->values();
@endphp

<!-- Hero Section -->
<section class="category-page row implementations-page">
    <div class="category-top">
        <img class="products-background" src="{{asset('assets/img/implementations/hero-car-1.png')}}" alt="">
        <div class="products-background"></div>

    <!-- Breadcrumbs -->
        <nav class="category-breadcrumbs">
            <ul class="flex-center">
                <li>
                    <a href="{{route('index')}}"
                       title="{{__('header_footer.home')}}"
                       class="button">
                        <i class="far fa-chevron-left"></i>{{__('header_footer.home')}}
                    </a>
                </li>
                <li>
                    <span class="button">
                        <i class="far fa-chevron-left"></i>{{__('implementations.title')}}
                    </span>
                </li>
            </ul>
        </nav>

    <!-- Hero Title -->
        <h1 class="title">{{__('implementations.title')}}</h1>

    <!-- Category Filter Strip - Below title -->
        <nav class="category-child-nav">
            <ul>
            <!-- "All" category -->
                <li>
                    <a href="#" class="flex-center active" data-category="all">{{__('implementations.category_all')}}</a>
                </li>
            <!-- Dynamic categories from implementations' products -->
            @foreach($implementationCategories as $category)
                    <li>
                        <a href="#" class="flex-center" data-category="{{$category->slug}}">{{$category->name}}</a>
                    </li>
            @endforeach
            </ul>
        </nav>
    </div>
</section>

<!-- Description Section -->
<div class="implementations-description">
    <div class="container">
        <div class="description-content">
            {!! __('implementations.description') !!}
        </div>
    </div>
</div>

<!-- Brands Strip -->
<div class="implementations-brands">
    <div class="container">
        <div class="brands-logos">
            <img src="{{asset('assets/img/brands/car-brands-stript.png')}}" alt="Car Brands" class="brands-strip-image brands-strip-desktop">
            <img src="{{asset('assets/img/brands/Car_Brands_mobile.png')}}" alt="Car Brands" class="brands-strip-image brands-strip-mobile">
        </div>
    </div>
</div>

<!-- Gallery Section -->
<div class="implementations-gallery">
    <div class="container">
        @if($implementations->count() > 0)
        <div class="gallery-grid">
            @foreach($implementations as $index => $implementation)
                <div class="gallery-item"
                     data-category="{{$implementation->product->category->slug ?? 'all'}}"
                     data-category-name="{{$implementation->product->category->name ?? __('implementations.category_decorative')}}"
                     data-implementation-id="{{$implementation->id}}"
                     data-title="{{$implementation->title}}"
                     data-description="{{$implementation->descriptions ?? ''}}"
                     data-main-image="{{$implementation->getImage()}}"
                     data-product-name="{{$implementation->product->getName() ?? ''}}"
                     data-product-image="{{$implementation->product->getPreviewImage() ?? ''}}"
                     data-product-description="{{$implementation->product->descriptions ?? ''}}"
                     data-product-slug="{{$implementation->product ? route('products.show', ['product' => $implementation->product->slugEn]) : ''}}"
                     data-gallery-images="{{json_encode($implementation->getMedia('main')->map(function($media) { return $media->getUrl(); })->toArray())}}"
                     data-gallery-previews="{{json_encode($implementation->getMedia('main')->map(function($media) {
                         // Сначала проверяем preview_webp (более легкий формат)
                         if ($media->hasGeneratedConversion('preview_webp')) {
                             return $media->getUrl('preview_webp');
                         }
                         // Затем preview (если webp нет)
                         if ($media->hasGeneratedConversion('preview')) {
                             return $media->getUrl('preview');
                         }
                         // Fallback на оригинал
                         return $media->getUrl();
                     })->toArray())}}">
                    <div class="gallery-item-clickable">
                        {{-- Показываем превью, оригинал подгружаем по клику через data-main-image --}}
                        <img src="{{$implementation->getPreviewImage()}}" alt="{{$implementation->title}}">
                    </div>
                    <div class="gallery-item-title">{{$implementation->title}}</div>

                    <!-- Accordion Details -->
                    <div class="gallery-item-details">
                        <div class="details-inner">
                            <div class="details-header-top">
                                <div class="details-category" data-category-name="{{$implementation->product->category->name ?? __('implementations.category_decorative')}}"></div>
                            </div>
                            <div class="details-product-info">
                                <div class="product-image">
                                    <img class="details-product-image" data-product-image="{{$implementation->product->getPreviewImage() ?? ''}}" src="" alt="">
                                </div>
                                @if($implementation->product)
                                    <a class="product-name details-product-name"
                                       href="{{route('products.show', ['product' => $implementation->product->slugEn])}}"
                                       data-product-name="{{$implementation->product->getName() ?? ''}}"></a>
                                @else
                                    <div class="product-name details-product-name"
                                         data-product-name=""></div>
                                @endif
                            </div>
                            <div class="details-divider"></div>
                             <div class="details-gallery"
                                  data-gallery-images='@json($implementation->getMedia('main')->map(function($media) { return $media->getUrl(); })->toArray())'
                                  data-gallery-previews='@json($implementation->getMedia('main')->map(function($media) {
                                      // Сначала проверяем preview_webp (более легкий формат)
                                      if ($media->hasGeneratedConversion('preview_webp')) {
                                          return $media->getUrl('preview_webp');
                                      }
                                      // Затем preview (если webp нет)
                                      if ($media->hasGeneratedConversion('preview')) {
                                          return $media->getUrl('preview');
                                      }
                                      // Fallback на оригинал если конверсий нет
                                      return $media->getUrl();
                                  })->toArray())'>
                                <!-- Gallery images will be inserted here later -->
                            </div>
                            <div class="details-description" data-description="{{$implementation->descriptions ?? ''}}"></div>
                        </div>
                    </div>
                    <div class="details-footer">
                        <button class="details-close">{{__('implementations.collapse')}}</button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- More Projects Button -->
        <div class="more-projects">
            <a href="#" class="more-projects-btn">{{__('implementations.more_projects')}}</a>
        </div>
        @else
        <div class="gallery-empty">
            <p>{{__('implementations.no_implementations')}}</p>
        </div>
        @endif
    </div>
</div>

<!-- Modal Window for Implementation Details -->
<div class="implementation-modal" id="implementationModal">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-body">
            <div class="modal-image-wrapper">
                <a class="modal-main-image-link" href="" data-fancybox="modal-gallery">
                    <img class="modal-main-image" src="" alt="">
                </a>
                <div class="modal-main-title"></div>
            </div>
            <div class="modal-details">
                <div class="details-header-top">
                    <div class="details-category"></div>
                </div>
                <div class="details-product-info">
                    <div class="product-image">
                        <img class="details-product-image" src="" alt="">
                    </div>
                    <a class="product-name details-product-name" href=""></a>
                </div>
                <div class="details-divider"></div>
                <div class="details-gallery">
                    <!-- Gallery images will be inserted here -->
                </div>
                <div class="details-description"></div>
                <div class="details-footer">
                    <button class="details-close">{{__('implementations.collapse')}}</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Category Filter + "More projects" logic
    $(document).ready(function() {
        var currentCategory = 'all';
        var ITEMS_LIMIT = 6; // показываем 6 карточек вместо 3

        function getItemsForCategory(category) {
            if (!category || category === 'all') {
                return $('.gallery-item');
            }
            return $('.gallery-item[data-category="' + category + '"]');
        }

        // Фильтрация + ограничение до ITEMS_LIMIT
        function filterByCategory(category) {
            currentCategory = category || 'all';

            var $allItems = $('.gallery-item');
            $allItems.hide().removeClass('limited-hidden');

            var $selected = getItemsForCategory(currentCategory);

            if ($selected.length === 0) {
                $('.more-projects').hide();
                return;
            }

            if ($selected.length > ITEMS_LIMIT) {
                $selected.slice(0, ITEMS_LIMIT).show();
                $selected.slice(ITEMS_LIMIT).addClass('limited-hidden'); // остаются скрытыми до клика "Більше проєктів"
                $('.more-projects').show();
            } else {
                $selected.show();
                $('.more-projects').hide();
            }
        }

        // При загрузке показываем первые 3 для категории "all"
        filterByCategory('all');

        $('.category-child-nav a').on('click', function(e) {
            e.preventDefault();
            $('.category-child-nav a').removeClass('active');
            $(this).addClass('active');

            // При смене категории закрываем открытые детали и сбрасываем стили/порядок
            $('.gallery-item').removeClass('is-open').css('order', '');
            $('.gallery-item .gallery-item-details').css({
                'max-height': '',
                'opacity': '',
                'overflow': '',
                'display': '',
                'visibility': ''
            });

            // Закрываем модальное окно если открыто
            closeModal();

            var category = $(this).data('category') || $(this).attr('data-category') || 'all';
            filterByCategory(category);
        });

        // Автоподгрузка при скролле для "Більше проєктів"
        let loading = false;
        const galleryGrid = document.querySelector('.gallery-grid');
        
        window.addEventListener('scroll', function() {
            if (loading) return;
            
            const $moreProjects = $('.more-projects');
            if ($moreProjects.length === 0 || !$moreProjects.is(':visible')) return;
            
            if (galleryGrid) {
                const rect = galleryGrid.getBoundingClientRect();
                const isVisible = rect.bottom <= window.innerHeight + 1500;
                
                if (isVisible) {
                    loading = true;
                    $moreProjects.show();
                    
                    // Показываем следующие элементы
                    var $selected = getItemsForCategory(currentCategory);
                    var $hidden = $selected.filter('.limited-hidden');
                    
                    if ($hidden.length > 0) {
                        // Показываем следующие ITEMS_LIMIT элементов
                        var $toShow = $hidden.slice(0, ITEMS_LIMIT);
                        $toShow.show().removeClass('limited-hidden');
                        
                        // Если больше нет скрытых элементов, скрываем кнопку
                        if ($hidden.length <= ITEMS_LIMIT) {
                            $moreProjects.hide();
                        }
                    } else {
                        $moreProjects.hide();
                    }
                    
                    setTimeout(function() {
                        loading = false;
                    }, 300);
                }
            }
        });

        // Кнопка "Більше проєктів" — показываем все для текущей категории и скрываем кнопку
        $('.more-projects-btn').on('click', function(e) {
            e.preventDefault();
            var $selected = getItemsForCategory(currentCategory);
            $selected.show().removeClass('limited-hidden');
            $('.more-projects').hide();
        });

        // Function to fill modal/accordion with data
        function fillImplementationData(item, targetElement) {
            const categoryName = item.data('category-name');
            const productName = item.data('product-name');
            const productImage = item.data('product-image');
            const description = item.data('description');
            const mainImageOriginal = item.data('main-image');

            // Get gallery images
            let galleryImages = [];
            const galleryData = item.data('gallery-images');
            if (galleryData) {
                try {
                    galleryImages = typeof galleryData === 'string' ? JSON.parse(galleryData) : galleryData;
                } catch (e) {
                    console.warn('Error parsing gallery images:', e);
                }
            }

            // Get gallery previews
            let galleryPreviews = [];
            const galleryPreviewsData = item.data('gallery-previews');
            if (galleryPreviewsData) {
                try {
                    galleryPreviews = typeof galleryPreviewsData === 'string' ? JSON.parse(galleryPreviewsData) : galleryPreviewsData;
                } catch (e) {
                    console.warn('Error parsing gallery previews:', e);
                }
            }

            // Fill category
            const defaultCategory = '{{__('implementations.category_default')}}';
            targetElement.find('.details-category').text(categoryName || defaultCategory);

            // Fill product name and link
            const $productName = targetElement.find('.details-product-name');
            $productName.text(productName || '');
            const productSlug = item.data('product-slug');
            if (productSlug && $productName.is('a')) {
                $productName.attr('href', productSlug);
            } else if (!productSlug && $productName.is('a')) {
                // Convert to div if no link
                const text = $productName.text();
                $productName.replaceWith($('<div>', { class: 'product-name details-product-name', text: text }));
            }

            // Fill product image
            if (productImage) {
                targetElement.find('.details-product-image').attr('src', productImage).attr('alt', productName || '');
            } else {
                targetElement.find('.details-product-image').attr('src', '').attr('alt', '');
            }

            // Fill description
            const descText = description || '';
            const $descriptionBlock = targetElement.find('.details-description');
            if (descText && descText.trim() !== '') {
                $descriptionBlock.html(descText).show();
            } else {
                $descriptionBlock.hide();
            }

            // Fill gallery
            const $gallery = targetElement.find('.details-gallery');
            $gallery.empty();

            const implementationId = item.data('implementation-id');
            const galleryGroup = 'gallery-' + implementationId;

            // Fill main image and title in modal
            if (targetElement.hasClass('modal-details')) {
                const $modal = $('#implementationModal');
                if (mainImageOriginal) {
                    const $mainImage = $modal.find('.modal-main-image');
                    const $mainImageLink = $modal.find('.modal-main-image-link');
                    $mainImage.attr('src', mainImageOriginal).attr('alt', item.data('title') || '');
                    $mainImageLink.attr('href', mainImageOriginal);
                    // Use the same gallery group as thumbnails
                    $mainImageLink.attr('data-fancybox', galleryGroup);
                }
                const mainTitle = productName || item.data('title') || '';
                $modal.find('.modal-main-title').text(mainTitle);
            }

            if (galleryImages && galleryImages.length > 0) {
                // Add main image first
                const mainImageIndex = galleryImages.indexOf(mainImageOriginal);
                const mainPreviewSrc = (mainImageIndex !== -1 && galleryPreviews && galleryPreviews.length > mainImageIndex && galleryPreviews[mainImageIndex])
                    ? galleryPreviews[mainImageIndex]
                    : mainImageOriginal;

                if (mainImageOriginal && mainPreviewSrc) {
                    const $mainThumb = $('<a>', {
                        href: mainImageOriginal,
                        'data-fancybox': galleryGroup
                    });

                    const $mainThumbImg = $('<img>', {
                        class: 'gallery-thumbnail',
                        src: mainPreviewSrc,
                        alt: '',
                        loading: 'lazy'
                    });

                    $mainThumb.append($mainThumbImg);
                    $gallery.append($mainThumb);
                }

                // Add other images
                galleryImages.forEach((originalSrc, index) => {
                    if (originalSrc !== mainImageOriginal) {
                        const previewSrc = (galleryPreviews && galleryPreviews.length > index && galleryPreviews[index]) ? galleryPreviews[index] : originalSrc;

                        const $thumb = $('<a>', {
                            href: originalSrc,
                            'data-fancybox': galleryGroup
                        });

                        const $thumbImg = $('<img>', {
                            class: 'gallery-thumbnail',
                            src: previewSrc,
                            alt: '',
                            loading: 'lazy'
                        });

                        $thumb.append($thumbImg);
                        $gallery.append($thumb);
                    }
                });

                // Initialize fancybox
                if (typeof $.fancybox !== 'undefined') {
                    $gallery.find('[data-fancybox]').fancybox({
                        buttons: ['close'],
                        loop: true,
                        keyboard: true,
                        arrows: true,
                        infobar: false,
                        toolbar: false,
                        caption: ''
                    });
                }
            }
        }

        // Function to open modal
        function openModal(item) {
            const $modal = $('#implementationModal');
            const $modalDetails = $modal.find('.modal-details');
            
            fillImplementationData(item, $modalDetails);
            
            // Initialize fancybox for modal main image and gallery after data is filled
            setTimeout(function() {
                const $mainImageLink = $modal.find('.modal-main-image-link');
                const $galleryLinks = $modalDetails.find('.details-gallery [data-fancybox]');
                
                if (typeof $.fancybox !== 'undefined') {
                    // Initialize fancybox for all gallery items including main image
                    $mainImageLink.add($galleryLinks).fancybox({
                        buttons: ['close'],
                        loop: true,
                        keyboard: true,
                        arrows: true,
                        infobar: false,
                        toolbar: false,
                        caption: ''
                    });
                }
            }, 100);
            
            $modal.addClass('is-open');
            $('body').css('overflow', 'hidden'); // Prevent body scroll
        }

        // Function to close modal
        function closeModal() {
            const $modal = $('#implementationModal');
            $modal.removeClass('is-open');
            $('body').css('overflow', ''); // Restore body scroll
        }

        // Responsive Accordion/Modal - используем делегирование событий
        $(document).off('click', '.gallery-item-clickable').on('click', '.gallery-item-clickable', function(e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();

            const item = $(this).closest('.gallery-item');
            const details = item.find('.gallery-item-details');
            const isDesktop = window.matchMedia('(min-width: 1024px)').matches;
            const windowWidth = $(window).width();
            const innerWidth = window.innerWidth;
            const isDesktopConfirmed = isDesktop && windowWidth >= 1024 && innerWidth >= 1024;

            if (item.length === 0) {
                return;
            }

            // При первом открытии подменяем превьюшку на оригинал
            const $mainImg = item.find('.gallery-item-clickable img');
            const originalSrc = item.data('main-image');
            if (originalSrc && !$mainImg.data('loaded-original')) {
                $mainImg.attr('src', originalSrc);
                $mainImg.data('loaded-original', true);
            }

            // Проверяем, открыта ли уже эта карточка (для мобильных)
            const isCurrentlyOpen = item.hasClass('is-open');

            if (isCurrentlyOpen && !isDesktopConfirmed) {
                // На мобильных: если открыта - открываем fancybox
                const $gallery = details.find('.details-gallery');
                const $firstLink = $gallery.find('[data-fancybox]').first();

                if ($firstLink.length > 0 && typeof $.fancybox !== 'undefined') {
                    $firstLink.trigger('click');
                } else {
                    const mainImageOriginal = item.data('main-image');
                    let galleryImages = [];
                    const galleryData = item.data('gallery-images');
                    if (galleryData) {
                        try {
                            galleryImages = typeof galleryData === 'string' ? JSON.parse(galleryData) : galleryData;
                        } catch (e) {
                            console.warn('Error parsing gallery images:', e);
                        }
                    }

                    if (typeof $.fancybox !== 'undefined' && mainImageOriginal) {
                        const allImages = [mainImageOriginal];
                        if (galleryImages && galleryImages.length > 0) {
                            galleryImages.forEach(img => {
                                if (img !== mainImageOriginal && !allImages.includes(img)) {
                                    allImages.push(img);
                                }
                            });
                        }

                        const fancyboxItems = allImages.map((src) => {
                            return {
                                src: src,
                                type: 'image',
                                opts: { caption: '' }
                            };
                        });

                        $.fancybox.open(fancyboxItems, {
                            buttons: ['close'],
                            loop: true,
                            keyboard: true,
                            arrows: true,
                            infobar: false,
                            toolbar: false,
                            caption: ''
                        });
                    }
                }
                return;
            }

            // Desktop: открываем модальное окно
            if (isDesktopConfirmed) {
                openModal(item);
                return;
            }

            // Mobile: открываем аккордеон
            // Закрываем другие карточки
            $('.gallery-item').not(item).removeClass('is-open').find('.gallery-item-details').css({
                    'max-height': '',
                    'opacity': '',
                    'overflow': '',
                    'display': '',
                    'visibility': ''
            });

            // Открываем текущую карточку
                item.addClass('is-open');
            fillImplementationData(item, details);

                requestAnimationFrame(function() {
                    setTimeout(function() {
                        details.css({
                            'max-height': '2000px',
                            'opacity': '1',
                            'visibility': 'visible'
                        });
                    }, 50);
                });
        });

        // Close button for accordion (mobile)
        $(document).on('click', '.gallery-item .details-close', function(e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            const item = $(this).closest('.gallery-item');
            if (item.length > 0) {
                item.removeClass('is-open');
                item.find('.gallery-item-details').css({
                    'max-height': '',
                    'opacity': '',
                    'overflow': '',
                    'display': '',
                    'visibility': ''
                });
            }
        });

        // Close button for modal
        $(document).on('click', '.modal-overlay, .implementation-modal .details-close', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Close if clicking overlay or close button
            if ($(e.target).hasClass('modal-overlay') || $(e.target).hasClass('details-close')) {
                closeModal();
            }
        });

        // Close on Escape key
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                // Проверяем, открыт ли fancybox
                if (typeof $.fancybox !== 'undefined' && $.fancybox.getInstance() && $.fancybox.getInstance().isVisible) {
                    return;
                }
                
                // Закрываем модальное окно если открыто
                if ($('#implementationModal').hasClass('is-open')) {
                    closeModal();
                    return;
                }
                
                // Закрываем аккордеон на мобильных
                const openItems = $('.gallery-item.is-open');
                if (openItems.length > 0) {
                    openItems.each(function() {
                        const item = $(this);
                        item.removeClass('is-open');
                        item.find('.gallery-item-details').css({
                            'max-height': '',
                            'opacity': '',
                            'overflow': '',
                            'display': '',
                            'visibility': ''
                        });
                    });
                }
            }
        });
    });
</script>
@endpush

@push('fixed-catalog')
    @include('base.components.categories-catalog')
@endpush


@endsection
