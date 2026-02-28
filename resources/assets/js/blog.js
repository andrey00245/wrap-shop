/**
 * Blog Page JavaScript
 */

import { customBlockSliderInitialization, imageSliderInProduct } from "./sliderInitialization";

$(document).ready(function() {
    // Category filter functionality
    $('.category-filter').on('click', function() {
        $('.category-filter').removeClass('active');
        $(this).addClass('active');
        
        const category = $(this).data('category');
        
        // TODO: Implement filtering logic when connected to backend
        console.log('Filter by category:', category);
    });
    
    // Smooth scroll for table of contents links
    $('.toc-list a').on('click', function(e) {
        e.preventDefault();
        const target = $(this.getAttribute('href'));
        
        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top - 100
            }, 500);
        }
    });
    
    // Article progress tracking (simple version)
    function updateArticleProgress() {
        const windowHeight = $(window).height();
        const documentHeight = $(document).height();
        const scrollTop = $(window).scrollTop();
        
        const progress = Math.round((scrollTop / (documentHeight - windowHeight)) * 100);
        
        if (progress >= 0 && progress <= 100) {
            $('.progress-text').text(progress + '/100');
        }
    }
    
    // Update progress on scroll
    if ($('.article-progress').length) {
        $(window).on('scroll', updateArticleProgress);
        updateArticleProgress(); // Initial call
    }
    
    // Latest Publications Slider on Mobile
    function initLatestSlider() {
        const $slider = $('#latest-slider');
        const $dots = $('.latest-slider-dots');
        
        if ($slider.length && $(window).width() <= 768) {
            const $cards = $slider.find('.article-card');
            const totalSlides = $cards.length;
            let currentSlide = 0;
            
            // Remove any existing click handlers to prevent duplicates
            $dots.off('click', '.dot');
            $slider.off('touchstart touchmove touchend');
            
            // Hide all cards except first
            $cards.css({
                'display': 'none',
                'visibility': 'hidden',
                'opacity': '0',
                'position': 'absolute',
                'width': '100%'
            });
            $cards.eq(0).css({
                'display': 'block',
                'visibility': 'visible',
                'opacity': '1',
                'position': 'relative'
            });
            
            // Create dots
            $dots.empty();
            for (let i = 0; i < totalSlides; i++) {
                $dots.append('<li><button class="dot' + (i === 0 ? ' active' : '') + '" data-slide="' + i + '"></button></li>');
            }
            
            // Go to slide function
            function goToSlide(index) {
                if (index < 0 || index >= totalSlides) return;
                
                // Hide all cards
                $cards.css({
                    'display': 'none',
                    'visibility': 'hidden',
                    'opacity': '0',
                    'position': 'absolute'
                });
                
                // Show only the selected card
                $cards.eq(index).css({
                    'display': 'block',
                    'visibility': 'visible',
                    'opacity': '1',
                    'position': 'relative',
                    'width': '100%'
                });
                
                // Update dots
                $dots.find('.dot').removeClass('active');
                $dots.find('.dot[data-slide="' + index + '"]').addClass('active');
                currentSlide = index;
            }
            
            // Dot click handler
            $dots.on('click', '.dot', function() {
                const slideIndex = $(this).data('slide');
                goToSlide(slideIndex);
            });
            
            // Touch/swipe handlers for swipe navigation
            let touchStartX = 0;
            let touchEndX = 0;
            const minSwipeDistance = 50; // Minimum distance for a swipe
            
            $slider.on('touchstart', function(e) {
                touchStartX = e.originalEvent.touches[0].clientX;
            });
            
            $slider.on('touchmove', function(e) {
                // Allow default scrolling if user is scrolling vertically
                const touchMoveY = e.originalEvent.touches[0].clientY;
                const touchStartY = e.originalEvent.touches[0].clientY;
                const verticalDistance = Math.abs(touchMoveY - touchStartY);
                const horizontalDistance = Math.abs(e.originalEvent.touches[0].clientX - touchStartX);
                
                // If vertical scroll is greater than horizontal, allow default behavior
                if (verticalDistance > horizontalDistance) {
                    return;
                }
                
                // Prevent default if horizontal swipe
                if (horizontalDistance > 10) {
                    e.preventDefault();
                }
            });
            
            $slider.on('touchend', function(e) {
                touchEndX = e.originalEvent.changedTouches[0].clientX;
                const swipeDistance = touchStartX - touchEndX;
                
                // Check if swipe distance is sufficient
                if (Math.abs(swipeDistance) > minSwipeDistance) {
                    if (swipeDistance > 0) {
                        // Swipe left - next slide
                        goToSlide(currentSlide + 1);
                    } else {
                        // Swipe right - previous slide
                        goToSlide(currentSlide - 1);
                    }
                }
            });
        } else {
            // Desktop: show all cards
            $slider.find('.article-card').css({
                'display': 'flex',
                'visibility': 'visible',
                'opacity': '1',
                'position': 'relative'
            });
            $dots.hide();
        }
    }
    
    // Category Sections Slider on Mobile
    function initCategorySliders() {
        $('.category-section').each(function() {
            const $section = $(this);
            const $slider = $section.find('.articles-grid-2');
            const category = $slider.data('category');
            const $dots = $section.find('.category-slider-dots[data-category="' + category + '"]');
            
            if ($slider.length && $(window).width() <= 768) {
                const $cards = $slider.find('.article-card');
                const totalSlides = $cards.length;
                let currentSlide = 0;
                
                // Remove any existing click handlers to prevent duplicates
                $dots.off('click', '.dot');
                $slider.off('touchstart touchmove touchend');
                
                // Hide all cards except first
                $cards.css({
                    'display': 'none',
                    'visibility': 'hidden',
                    'opacity': '0',
                    'position': 'absolute',
                    'width': '100%'
                });
                $cards.eq(0).css({
                    'display': 'block',
                    'visibility': 'visible',
                    'opacity': '1',
                    'position': 'relative'
                });
                
                // Create dots
                $dots.empty();
                for (let i = 0; i < totalSlides; i++) {
                    $dots.append('<li><button class="dot' + (i === 0 ? ' active' : '') + '" data-slide="' + i + '"></button></li>');
                }
                
                // Go to slide function
                function goToSlide(index) {
                    if (index < 0 || index >= totalSlides) return;
                    
                    // Hide all cards
                    $cards.css({
                        'display': 'none',
                        'visibility': 'hidden',
                        'opacity': '0',
                        'position': 'absolute'
                    });
                    
                    // Show only the selected card
                    $cards.eq(index).css({
                        'display': 'block',
                        'visibility': 'visible',
                        'opacity': '1',
                        'position': 'relative',
                        'width': '100%'
                    });
                    
                    // Update dots
                    $dots.find('.dot').removeClass('active');
                    $dots.find('.dot[data-slide="' + index + '"]').addClass('active');
                    currentSlide = index;
                }
                
                // Dot click handler
                $dots.on('click', '.dot', function() {
                    const slideIndex = $(this).data('slide');
                    goToSlide(slideIndex);
                });
                
                // Touch/swipe handlers for swipe navigation
                let touchStartX = 0;
                let touchEndX = 0;
                const minSwipeDistance = 50; // Minimum distance for a swipe
                
                $slider.on('touchstart', function(e) {
                    touchStartX = e.originalEvent.touches[0].clientX;
                });
                
                $slider.on('touchmove', function(e) {
                    // Allow default scrolling if user is scrolling vertically
                    const touchMoveY = e.originalEvent.touches[0].clientY;
                    const touchStartY = e.originalEvent.touches[0].clientY;
                    const verticalDistance = Math.abs(touchMoveY - touchStartY);
                    const horizontalDistance = Math.abs(e.originalEvent.touches[0].clientX - touchStartX);
                    
                    // If vertical scroll is greater than horizontal, allow default behavior
                    if (verticalDistance > horizontalDistance) {
                        return;
                    }
                    
                    // Prevent default if horizontal swipe
                    if (horizontalDistance > 10) {
                        e.preventDefault();
                    }
                });
                
                $slider.on('touchend', function(e) {
                    touchEndX = e.originalEvent.changedTouches[0].clientX;
                    const swipeDistance = touchStartX - touchEndX;
                    
                    // Check if swipe distance is sufficient
                    if (Math.abs(swipeDistance) > minSwipeDistance) {
                        if (swipeDistance > 0) {
                            // Swipe left - next slide
                            goToSlide(currentSlide + 1);
                        } else {
                            // Swipe right - previous slide
                            goToSlide(currentSlide - 1);
                        }
                    }
                });
            } else {
                // Desktop: show all cards
                $slider.find('.article-card').css({
                    'display': 'flex',
                    'visibility': 'visible',
                    'opacity': '1',
                    'position': 'relative'
                });
                $dots.hide();
            }
        });
    }
    
    // Trim article titles to text before colon
    function trimArticleTitles() {
        $('.article-title').each(function() {
            const $title = $(this);
            const text = $title.text();
            const colonIndex = text.indexOf(':');
            if (colonIndex !== -1) {
                $title.text(text.substring(0, colonIndex));
            }
        });
    }
    
    // Categories accordion
    function initCategoriesAccordion() {
        // Закрываем аккордеон на мобильных устройствах при загрузке
        function checkMobileAndCloseAccordion() {
            const isMobile = window.innerWidth <= 768;
            const $accordionHeader = $('.article-categories-sidebar .accordion-header');
            const $accordionContent = $('.article-categories-sidebar .accordion-content');
            
            if (isMobile) {
                // На мобильных - закрываем если открыт
                if ($accordionHeader.hasClass('active')) {
                    $accordionHeader.removeClass('active');
                    $accordionContent.removeClass('active');
                    $accordionContent.css({
                        'max-height': '0',
                        'overflow': 'hidden',
                        'visibility': 'hidden',
                        'display': 'block'
                    });
                }
            } else {
                // На десктопе - открываем если закрыт (по умолчанию должен быть открыт)
                if (!$accordionHeader.hasClass('active')) {
                    $accordionHeader.addClass('active');
                    $accordionContent.addClass('active');
                    $accordionContent.removeAttr('style');
                }
            }
        }
        
        // Проверяем при загрузке
        checkMobileAndCloseAccordion();
        
        // Проверяем при изменении размера окна с задержкой для оптимизации
        let resizeTimeout;
        $(window).on('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(function() {
                checkMobileAndCloseAccordion();
            }, 250);
        });
        
        // Используем делегирование событий для надежности
        $(document).on('click', '.accordion-header', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const $header = $(this);
            const $item = $header.closest('.accordion-item');
            const $content = $item.find('.accordion-content');
            
            console.log('Accordion clicked', {
                header: $header.length,
                item: $item.length,
                content: $content.length,
                isActive: $header.hasClass('active')
            });
            
            // Проверяем что элементы найдены
            if ($content.length === 0) {
                console.warn('Accordion content not found');
                return;
            }
            
            if ($header.hasClass('active')) {
                // Закрываем
                console.log('Closing accordion');
                
                // Сначала удаляем классы
                $header.removeClass('active');
                $content.removeClass('active');
                
                // Используем requestAnimationFrame для правильного применения стилей
                requestAnimationFrame(function() {
                    // Принудительно устанавливаем стили для закрытия
                    $content.css({
                        'max-height': '0',
                        'overflow': 'hidden',
                        'visibility': 'hidden',
                        'display': 'block'
                    });
                });
            } else {
                // Закрываем все открытые аккордеоны в этом контейнере
                const $accordion = $header.closest('.categories-accordion');
                $accordion.find('.accordion-header').removeClass('active');
                $accordion.find('.accordion-content').each(function() {
                    const $item = $(this);
                    $item.removeClass('active');
                    requestAnimationFrame(function() {
                        $item.css({
                            'max-height': '0',
                            'overflow': 'hidden',
                            'visibility': 'hidden',
                            'display': 'block'
                        });
                    });
                });
                
                // Открываем текущий
                console.log('Opening accordion');
                
                // Сначала удаляем все inline стили
                $content.removeAttr('style');
                
                // Добавляем классы
                $header.addClass('active');
                $content.addClass('active');
                
                // Используем двойной requestAnimationFrame для правильного применения стилей
                requestAnimationFrame(function() {
                    requestAnimationFrame(function() {
                        // Проверяем что стили применились через CSS классы
                        const currentMaxHeight = $content.css('max-height');
                        console.log('Current max-height after opening:', currentMaxHeight);
                        
                        // Если стили не применились, устанавливаем принудительно
                        if (currentMaxHeight === '0px' || currentMaxHeight === 'none' || !currentMaxHeight) {
                            $content.css({
                                'max-height': '1000px',
                                'overflow': 'visible',
                                'visibility': 'visible',
                                'display': 'block'
                            });
                        }
                    });
                });
            }
        });
        
        // Проверяем наличие элементов при загрузке
        setTimeout(function() {
            const $headers = $('.accordion-header');
            if ($headers.length > 0) {
                console.log('Accordion initialized, found', $headers.length, 'headers');
                console.log('Headers:', $headers);
            } else {
                console.warn('No accordion headers found');
            }
        }, 500);
    }
    
    // Initialize products slider - используем Splide как на главной странице
    function initBlogProductsSlider() {
        const sliderEl = document.querySelector('#blogProductsSlider');
        const $dots = $('.blog-products-slider-dots.mobile-only');
        
        // Флаг для отслеживания, был ли обработчик уже зарегистрирован
        if (sliderEl && sliderEl._handlerRegistered) {
            console.log('Handler already registered, skipping');
            return;
        }
        
        if (sliderEl) {
            // Удаляем старый экземпляр слайдера если он существует
            if (sliderEl.splide) {
                try {
                    // Удаляем все обработчики событий перед уничтожением
                    if (sliderEl.splide.off) {
                        // Удаляем все обработчики события 'moved'
                        sliderEl.splide.off('moved');
                    }
                    if (sliderEl.splide.destroy) {
                        sliderEl.splide.destroy();
                    }
                } catch (e) {
                    console.warn('Error destroying old slider:', e);
                }
                // Сбрасываем флаг регистрации обработчика
                sliderEl._handlerRegistered = false;
            }
            
            // Также проверяем глобальный экземпляр если он существует
            if (window.blogProductsSliderInstance) {
                try {
                    if (window.blogProductsSliderInstance.off) {
                        // Удаляем все обработчики события 'moved'
                        window.blogProductsSliderInstance.off('moved');
                    }
                    if (window.blogProductsSliderInstance.destroy) {
                        window.blogProductsSliderInstance.destroy();
                    }
                } catch (e) {
                    console.warn('Error destroying global slider instance:', e);
                }
            }
            
            const isMobile = $(window).width() <= 768;
            const windowWidth = $(window).width();
            
            // Определяем perPage в зависимости от ширины экрана
            let initialPerPage = 4;
            if (windowWidth <= 768) {
                initialPerPage = 2; // На мобильных всегда 2 карточки
            } else if (windowWidth <= 1019) {
                initialPerPage = 2;
            } else if (windowWidth <= 1331) {
                initialPerPage = 3;
            }
            
            // Используем Splide с теми же настройками, что и на главной странице
            // Для мобильных используем тип 'slide' для упрощения логики точек навигации
            let productsSlider = new Splide('#blogProductsSlider', {
                pagination: false,
                perPage: initialPerPage,
                gap: isMobile ? '4px' : '5px',
                padding: isMobile ? { right: 0, left: 0 } : { right: '8%' },
                type: isMobile ? 'slide' : 'loop', // На мобильных используем 'slide' для упрощения
                autoplay: false,
                drag: true,   // Разрешаем drag/swipe по умолчанию
                swipe: true,  // Явно включаем свайпы
                classes: {
                    arrows: 'splide__arrows home-products-slide-buttons',
                    prev: 'splide__arrow--prev',
                    next: 'splide__arrow--next',
                },
                breakpoints: {
                    1331: {
                        perPage: 3,
                        padding: { right: '3%' },
                    },
                    1019: {
                        perPage: 2,
                        padding: { right: '15%' },
                    },
                    768: {
                        perPage: 2, // Явно указываем 2 карточки для мобильных
                        padding: { right: 0, left: 0 },
                        gap: '4px',
                        type: 'slide', // На мобильных используем 'slide' для упрощения логики точек
                        drag: true,    // На всякий случай явно включаем drag/swipe на мобилке
                        swipe: true,
                    },
                    350: {
                        perPage: 2, // Оставляем 2 карточки даже на маленьких экранах
                        padding: { right: 0, left: 0 },
                        gap: '4px',
                        type: 'slide',
                        drag: true,
                        swipe: true,
                    }
                }
            });
            
            productsSlider.mount();
            
            // Сохраняем ссылку на экземпляр для последующего удаления
            window.blogProductsSliderInstance = productsSlider;
            if (sliderEl) {
                sliderEl.splide = productsSlider;
            }
            
            // Проверяем текущие настройки после mount
            setTimeout(function() {
                if (productsSlider && productsSlider.options) {
                    console.log('Products slider mounted, isMobile:', isMobile);
                    console.log('Current perPage:', productsSlider.options.perPage);
                    console.log('Window width:', $(window).width());
                }
            }, 100);
            
            // Регистрируем базовый обработчик события moved СРАЗУ после монтирования
            // Это гарантирует, что обработчик будет доступен до того, как пользователь начнет скроллить
            // Позже он будет заменен более детальным обработчиком внутри setTimeout для мобильных
            // Регистрируем базовый обработчик события moved для отладки (будет удален при регистрации детального)
            const baseHandler = function(splide, newIndex, prevIndex) {
                // Базовый обработчик для отладки - будет удален при регистрации детального
            };
            productsSlider.on('moved', baseHandler);
            
            // Сохраняем ссылку на базовый обработчик для последующего удаления
            window._blogProductsBaseHandler = baseHandler;
            
            // Переменные для обработчика события moved (общие для всех устройств)
            let handleSliderMoved = null;
            let currentGroupIndex = 0;
            let previousGroupIndex = 0;
            let isFirstMove = true;
            let initTimeout = null;
            let updateTimeout = null;
            
            // На мобилке создаем кастомные палочки для навигации
            if (isMobile) {
                // Даем время Splide инициализироваться
                setTimeout(function() {
                    // Получаем реальное количество слайдов
                    // Для типа 'slide' на мобильных клонов быть не должно
                    let totalSlides = 0;
                    
                    // Пробуем использовать splide.length - это правильный способ получить количество слайдов
                    if (productsSlider && typeof productsSlider.length !== 'undefined') {
                        totalSlides = productsSlider.length;
                        console.log('Using splide.length:', totalSlides);
                    }
                    
                    // Если не получилось через length, используем DOM с фильтрацией
                    if (totalSlides === 0 || totalSlides > 50) {
                        const $slider = $('#blogProductsSlider');
                        // Фильтруем только реальные слайды (без клонов)
                        const $realSlides = $slider.find('.splide__slide').filter(function() {
                            const $slide = $(this);
                            // Исключаем клонированные слайды
                            if ($slide.hasClass('is-cloned')) return false;
                            const ariaLabel = $slide.attr('aria-label') || '';
                            if (ariaLabel.toLowerCase().includes('clone')) return false;
                            if ($slide.attr('data-clone')) return false;
                            return true;
                        });
                        totalSlides = $realSlides.length;
                        console.log('Using DOM filter:', totalSlides);
                    }
                    
                    // Если все еще слишком много (возможно, считаем клоны), пробуем через Splide API
                    if (totalSlides === 0 || totalSlides > 50) {
                        if (productsSlider && productsSlider.Components && productsSlider.Components.Slides) {
                            try {
                                const slides = productsSlider.Components.Slides.slides || [];
                                const realSlides = slides.filter(function(slide) {
                                    if (!slide || !slide.slide) return false;
                                    return !slide.slide.classList.contains('is-cloned');
                                });
                                if (realSlides.length > 0 && realSlides.length <= 50) {
                                    totalSlides = realSlides.length;
                                    console.log('Using Splide API:', totalSlides);
                                }
                            } catch (e) {
                                console.warn('Could not get slides from Splide API:', e);
                            }
                        }
                    }
                    
                    // Если все еще проблема, используем половину (предполагая что половина - клоны)
                    // Но только если четное число и больше 20
                    if (totalSlides > 20 && totalSlides % 2 === 0) {
                        const half = totalSlides / 2;
                        if (half <= 50) {
                            totalSlides = half;
                            console.log('Using half (assuming clones):', totalSlides);
                        }
                    }
                    
                    const groupSize = 2;
                    // Вычисляем количество групп правильно
                    const totalGroups = Math.ceil(totalSlides / groupSize);
                    
                    console.log('Products slider initialization:', {
                        totalSlides: totalSlides,
                        groupSize: groupSize,
                        totalGroups: totalGroups
                    });
                    
                    // Убеждаемся что точки видны
                    if ($dots.length === 0) {
                        console.error('Dots element not found!');
                        return;
                    }
                    
                    // Remove any existing click handlers
                    $(document).off('click', '.blog-products-slider-dots .dot');
                    
                    // Create dots (по количеству групп)
                    $dots.empty();
                    if (totalGroups > 1) {
                        for (let i = 0; i < totalGroups; i++) {
                            $dots.append('<li><button class="dot' + (i === 0 ? ' active' : '') + '" data-group="' + i + '"></button></li>');
                        }
                        $dots.css('display', 'flex');
                        
                        // Go to group function
                        function goToGroup(index) {
                            if (index < 0 || index >= totalGroups) {
                                console.error('Invalid group index:', index);
                                return;
                            }
                            
                            // Обновляем текущий индекс группы перед переходом
                            currentGroupIndex = index;
                            
                            // Вычисляем индекс слайда для группы
                            const slideIndex = index * groupSize;
                            
                            console.log('Going to group:', {
                                index: index,
                                slideIndex: slideIndex,
                                totalSlides: totalSlides
                            });
                            
                            // Используем go() для перехода к нужному слайду
                            try {
                                if (productsSlider && typeof productsSlider.go === 'function') {
                                    productsSlider.go(slideIndex);
                                    
                                    // Сразу обновляем точки
                                    $dots.find('.dot').removeClass('active');
                                    const $activeDot = $dots.find('.dot[data-group="' + index + '"]');
                                    if ($activeDot.length) {
                                        $activeDot.addClass('active');
                                    }
                                } else {
                                    console.error('ProductsSlider not initialized or go method not available');
                                }
                            } catch (e) {
                                console.error('Error going to slide:', e);
                            }
                        }
                        
                        // Dot click handler - используем делегирование событий на document
                        $(document).on('click', '.blog-products-slider-dots .dot', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            const $dot = $(this);
                            const groupIndex = parseInt($dot.attr('data-group') || $dot.data('group') || 0);
                            
                            console.log('Dot clicked:', {
                                groupIndex: groupIndex,
                                totalGroups: totalGroups
                            });
                            
                            if (!isNaN(groupIndex) && groupIndex >= 0 && groupIndex < totalGroups) {
                                goToGroup(groupIndex);
                            }
                        });
                        
                        // Переменная для отслеживания текущей группы
                        let currentGroupIndex = 0;
                        let previousGroupIndex = 0;
                        let isFirstMove = true; // Флаг для первого движения
                        let initTimeout = null; // Таймаут инициализации точек
                        
                        // Update dots on slide change - улучшенная логика
                        let updateTimeout;
                        
                        // Удаляем старые обработчики перед регистрацией нового
                        // Используем именованную функцию для возможности удаления
                        function handleSliderMoved(splide, newIndex, prevIndex) {
                            
                            // Сохраняем флаг первого движения для определения задержки
                            const isFirstMoveNow = isFirstMove;
                            
                            // При первом движении отменяем таймаут инициализации
                            if (isFirstMove) {
                                isFirstMove = false;
                                if (initTimeout) {
                                    clearTimeout(initTimeout);
                                    initTimeout = null;
                                    console.log('Cancelled init timeout');
                                }
                            }
                            
                            // Отменяем предыдущий таймаут если есть
                            if (updateTimeout) {
                                clearTimeout(updateTimeout);
                            }
                            
                            // Используем небольшую задержку для более надежного определения активной группы
                            // Для первого движения используем меньшую задержку для быстрой реакции
                            // Но также можно использовать 0 для немедленного обновления при первом движении
                            const delay = isFirstMoveNow ? 0 : 100;
                            updateTimeout = setTimeout(function() {
                                // Получаем реальный индекс первого видимого слайда
                                // Для типа 'loop' newIndex может быть индексом клонированного слайда
                                let realIndex = null;
                                
                                // Пробуем получить индекс из Splide API напрямую
                                let splideIndexValue = null;
                                try {
                                    // Пробуем разные способы получения индекса
                                    if (splide && splide.index !== undefined && splide.index !== null) {
                                        splideIndexValue = splide.index;
                                    } else if (splide && splide.Components && splide.Components.Controller) {
                                        splideIndexValue = splide.Components.Controller.getIndex();
                                    } else if (productsSlider && productsSlider.index !== undefined && productsSlider.index !== null) {
                                        splideIndexValue = productsSlider.index;
                                    } else if (productsSlider && productsSlider.Components && productsSlider.Components.Controller) {
                                        splideIndexValue = productsSlider.Components.Controller.getIndex();
                                    }
                                } catch (e) {
                                    console.warn('Could not get splide index:', e);
                                }
                                
                                // Проверяем newIndex - если он определен и валиден, используем его
                                // Для типа 'slide' на мобильных индексы идут последовательно, без нормализации
                                if (newIndex !== undefined && newIndex !== null && !isNaN(newIndex)) {
                                    realIndex = parseInt(newIndex);
                                    
                                    // Для типа 'slide' не нужно нормализовать - индексы идут последовательно
                                    // Для типа 'loop' (на десктопе) может потребоваться нормализация
                                    if (!isMobile) {
                                        // Только для десктопа (тип 'loop') нормализуем индекс
                                        let normalizedNewIndex = realIndex;
                                        
                                        if (normalizedNewIndex >= totalSlides) {
                                            normalizedNewIndex = normalizedNewIndex % totalSlides;
                                        }
                                        if (normalizedNewIndex < 0) {
                                            normalizedNewIndex = totalSlides + (normalizedNewIndex % totalSlides);
                                            if (normalizedNewIndex < 0) normalizedNewIndex = 0;
                                        }
                                        
                                        // Вычисляем группу ДО нормализации для определения перехода через границу
                                        const groupBeforeNormalize = Math.floor(realIndex / groupSize);
                                        const groupAfterNormalize = Math.floor(normalizedNewIndex / groupSize);
                                        
                                        // Если группа изменилась с большой на маленькую, это переход через границу
                                        if (groupBeforeNormalize >= totalGroups && groupAfterNormalize === 0) {
                                            realIndex = (totalGroups - 1) * groupSize;
                                        } else {
                                            realIndex = normalizedNewIndex;
                                        }
                                    }
                                    // Для мобильных (тип 'slide') используем newIndex напрямую, но ограничиваем максимальным значением
                                    if (isMobile && realIndex >= totalSlides) {
                                        realIndex = totalSlides - 1;
                                    }
                                    
                                    // Для мобильных не используем splide.index для коррекции - полагаемся на newIndex
                                    if (!isMobile && splideIndexValue !== null && splideIndexValue !== undefined && !isNaN(splideIndexValue)) {
                                        const splideGroup = Math.floor(splideIndexValue / groupSize);
                                        const newIndexGroup = Math.floor(realIndex / groupSize);
                                        
                                        // Если группы сильно отличаются, возможно splide.index более точный
                                        if (Math.abs(splideGroup - newIndexGroup) > 1) {
                                            realIndex = splideIndexValue;
                                        }
                                    }
                                } else if (splideIndexValue !== null && splideIndexValue !== undefined && !isNaN(splideIndexValue)) {
                                    // Если newIndex не определен, используем splide.index
                                    realIndex = splideIndexValue;
                                }
                                
                                // Дополнительная проверка через DOM - ТОЛЬКО если newIndex был undefined
                                // НЕ перезаписываем правильный индекс из события newIndex
                                let firstVisibleIndex = -1;
                                
                                // Выполняем DOM проверку ТОЛЬКО если realIndex еще не установлен
                                if (realIndex === null || realIndex === undefined) {
                                    const $allSlides = $('#blogProductsSlider .splide__slide:not(.is-cloned)');
                                    const $track = $('#blogProductsSlider .splide__track');
                                    const container = $track.length > 0 ? $track[0] : null;
                                    
                                    if (container && $allSlides.length > 0) {
                                        const containerRect = container.getBoundingClientRect();
                                        
                                        $allSlides.each(function() {
                                            const $slide = $(this);
                                            const rect = this.getBoundingClientRect();
                                            
                                            // Проверяем, виден ли слайд в контейнере
                                            // Слайд виден если его левая граница находится в пределах контейнера
                                            const slideLeft = rect.left - containerRect.left;
                                            if (slideLeft >= 0 && slideLeft < containerRect.width && rect.width > 0) {
                                                // Получаем индекс слайда среди не-клонированных
                                                const slideIndex = $allSlides.index($slide);
                                                if (slideIndex >= 0 && firstVisibleIndex === -1) {
                                                    firstVisibleIndex = slideIndex;
                                                }
                                            }
                                        });
                                        
                                        // Если нашли первый видимый слайд через DOM, используем его
                                        if (firstVisibleIndex >= 0 && firstVisibleIndex < totalSlides) {
                                            realIndex = firstVisibleIndex;
                                        }
                                    }
                                }
                                
                                // Если все еще не определили индекс, используем 0
                                if (realIndex === null || realIndex === undefined || isNaN(realIndex)) {
                                    realIndex = 0;
                                }
                                
                                // Нормализуем индекс
                                realIndex = parseInt(realIndex);
                                if (realIndex < 0) {
                                    realIndex = 0;
                                }
                                if (realIndex >= totalSlides) {
                                    realIndex = totalSlides - 1;
                                }
                                
                                // Вычисляем группу на основе realIndex
                                // Для типа 'slide' (мобильные) индексы идут последовательно, группа вычисляется просто
                                // Для типа 'loop' (десктоп) может потребоваться нормализация
                                let currentGroup = Math.floor(realIndex / groupSize);
                                
                                // Важно: ограничиваем группу максимальным значением
                                if (currentGroup >= totalGroups) {
                                    currentGroup = totalGroups - 1;
                                }
                                
                                // Для типа 'loop' (десктоп) проверяем переход через границу
                                if (!isMobile && newIndex !== undefined && newIndex !== null && !isNaN(newIndex)) {
                                    const originalNewIndex = parseInt(newIndex);
                                    const originalGroup = Math.floor(originalNewIndex / groupSize);
                                    
                                    // Если оригинальный индекс был >= totalSlides, это клонированный слайд
                                    if (originalNewIndex >= totalSlides) {
                                        // Это переход через границу - используем последнюю группу
                                        currentGroup = totalGroups - 1;
                                    } else if (originalGroup >= totalGroups) {
                                        // Если группа больше totalGroups, это переход с конца
                                        currentGroup = totalGroups - 1;
                                    }
                                }
                                
                                // Для типа 'slide' (мобильные) используем простую логику - просто вычисляем группу из индекса
                                // Дополнительная проверка через DOM только для валидации (опционально)
                                if (isMobile) {
                                    // Для мобильных просто используем вычисленную группу
                                    // Можно добавить DOM проверку для дополнительной валидации, но обычно не нужно
                                } else {
                                    // Для десктопа (тип 'loop') используем DOM для валидации
                                    const $allSlides = $('#blogProductsSlider .splide__slide:not(.is-cloned)');
                                    const container = $('#blogProductsSlider .splide__track')[0];
                                    
                                    if (container && $allSlides.length > 0) {
                                        const containerRect = container.getBoundingClientRect();
                                        let visibleIndices = [];
                                        
                                        // Находим все видимые слайды
                                        $allSlides.each(function(index) {
                                            const rect = this.getBoundingClientRect();
                                            if (rect.left >= containerRect.left && rect.left < containerRect.right) {
                                                visibleIndices.push(index);
                                            }
                                        });
                                        
                                        // Если нашли видимые слайды, используем для валидации
                                        if (visibleIndices.length > 0) {
                                            const firstVisibleIndex = visibleIndices[0];
                                            const domGroup = Math.floor(firstVisibleIndex / groupSize);
                                            
                                            // Используем DOM группу только если она близка к вычисленной (разница <= 1)
                                            const groupDiff = Math.abs(domGroup - currentGroup);
                                            if (groupDiff <= 1 && domGroup >= 0 && domGroup < totalGroups) {
                                                currentGroup = domGroup;
                                            }
                                        }
                                    }
                                }
                                
                                // Проверяем, находимся ли мы на последних слайдах - если да, активируем последнюю группу
                                // Это важно для правильного отображения точек навигации на последних слайдах
                                // Последняя группа начинается с индекса, который гарантирует что мы показываем последние groupSize слайдов
                                const lastGroupStartIndex = Math.max(0, totalSlides - groupSize);
                                
                                // Основная проверка: используем realIndex, который уже правильно вычислен
                                // Если мы на последних groupSize слайдах, активируем последнюю группу
                                if (realIndex >= lastGroupStartIndex) {
                                        currentGroup = totalGroups - 1;
                                    }
                                
                                // Резервная проверка: также проверяем newIndex (на случай если realIndex не обновился)
                                // Это особенно важно для типа 'loop' на десктопе, где индексы могут быть нормализованы
                                    if (newIndex !== undefined && newIndex !== null && !isNaN(newIndex)) {
                                        const newIndexValue = parseInt(newIndex);
                                    
                                    if (!isMobile) {
                                        // Для типа 'loop' на десктопе: нормализуем индекс и проверяем
                                        let normalizedNewIndex = newIndexValue;
                                        if (normalizedNewIndex >= totalSlides) {
                                            normalizedNewIndex = normalizedNewIndex % totalSlides;
                                        }
                                        // Если нормализованный индекс указывает на последние слайды
                                        if (normalizedNewIndex >= lastGroupStartIndex) {
                                            currentGroup = totalGroups - 1;
                                        }
                                    } else {
                                        // Для типа 'slide' на мобильных: просто проверяем индекс
                                        if (newIndexValue >= lastGroupStartIndex) {
                                            currentGroup = totalGroups - 1;
                                        }
                                    }
                                }
                                
                                // Нормализуем группу
                                if (currentGroup < 0) {
                                    currentGroup = 0;
                                }
                                if (currentGroup >= totalGroups) {
                                    // Если группа больше или равна totalGroups, используем последнюю группу
                                    currentGroup = totalGroups - 1;
                                }
                                
                                // Для типа 'slide' (мобильные) не нужна коррекция - группы идут последовательно
                                // Для типа 'loop' (десктоп) может потребоваться проверка последовательности
                                if (!isMobile) {
                                    // Только для десктопа проверяем последовательность
                                    // НО: не перезаписываем если мы на последних слайдах (это правильное значение)
                                    const isOnLastSlides = realIndex >= lastGroupStartIndex || 
                                                          (newIndex !== undefined && newIndex !== null && !isNaN(newIndex) && 
                                                           parseInt(newIndex) >= lastGroupStartIndex);
                                    
                                    if (!isOnLastSlides) {
                                    const groupDiff = currentGroup - previousGroupIndex;
                                    
                                    // Разрешаем только последовательные изменения (вперед, назад на 1, или переход через границу)
                                    if (Math.abs(groupDiff) > 2 && groupDiff !== (totalGroups - 1) && groupDiff !== -(totalGroups - 1)) {
                                        // Если очень большой скачок (больше 2), возможно это ошибка
                                        const isLoopTransition = (previousGroupIndex >= totalGroups - 2 && currentGroup <= 1) ||
                                                               (previousGroupIndex <= 1 && currentGroup >= totalGroups - 2);
                                        
                                        if (!isLoopTransition) {
                                            // Используем предыдущую группу + направление движения
                                            if (groupDiff > 0) {
                                                currentGroup = Math.min(totalGroups - 1, previousGroupIndex + 1);
                                            } else {
                                                currentGroup = Math.max(0, previousGroupIndex - 1);
                                            }
                                            console.log('Large group jump detected, correcting:', {
                                                calculatedGroup: Math.floor(realIndex / groupSize),
                                                previousGroup: previousGroupIndex,
                                                correctedGroup: currentGroup,
                                                groupDiff: groupDiff
                                            });
                                            }
                                        }
                                    }
                                }
                                
                                // Обновляем предыдущую группу
                                if (currentGroup >= 0 && currentGroup < totalGroups) {
                                    previousGroupIndex = currentGroup;
                                }
                                
                                const normalizedGroup = currentGroup;
                                
                                // Вычисляем firstVisibleIndex для логирования (даже если realIndex уже установлен)
                                let logFirstVisibleIndex = firstVisibleIndex;
                                if (logFirstVisibleIndex === -1 && realIndex !== null && realIndex !== undefined) {
                                    // Если firstVisibleIndex не был вычислен, используем realIndex
                                    logFirstVisibleIndex = realIndex;
                                }
                                
                                // Получаем актуальный индекс из Splide для логирования
                                let logSplideIndex = null;
                                try {
                                    if (splide && splide.index !== undefined && splide.index !== null) {
                                        logSplideIndex = splide.index;
                                    } else if (productsSlider && productsSlider.index !== undefined && productsSlider.index !== null) {
                                        logSplideIndex = productsSlider.index;
                                    }
                                } catch (e) {
                                    // Игнорируем ошибки при получении индекса для логирования
                                }
                                
                                console.log('Products slider moved:', {
                                    newIndex: newIndex,
                                    splideIndex: logSplideIndex,
                                    splideIndexValue: splideIndexValue,
                                    realIndex: realIndex,
                                    firstVisibleIndex: logFirstVisibleIndex,
                                    calculatedGroup: Math.floor(realIndex / groupSize),
                                    currentGroup: currentGroup,
                                    normalizedGroup: normalizedGroup,
                                    previousGroup: previousGroupIndex,
                                    totalGroups: totalGroups,
                                    groupSize: groupSize,
                                    totalSlides: totalSlides
                                });
                                
                                currentGroupIndex = normalizedGroup;
                                
                                // Обновляем точки
                                console.log('Updating dots to group:', normalizedGroup, 'realIndex:', realIndex, 'totalGroups:', totalGroups);
                                
                                // Проверяем, что точки существуют
                                if ($dots.length === 0) {
                                    console.warn('Dots container not found!');
                                    return;
                                }
                                
                                const $allDots = $dots.find('.dot');
                                if ($allDots.length === 0) {
                                    console.warn('No dots found!');
                                    return;
                                }
                                
                                console.log('Found', $allDots.length, 'dots, activating group', normalizedGroup);
                                
                                $dots.find('.dot').removeClass('active');
                                const $activeDot = $dots.find('.dot[data-group="' + normalizedGroup + '"]');
                                if ($activeDot.length) {
                                    $activeDot.addClass('active');
                                    console.log('Dot activated successfully:', normalizedGroup);
                                } else {
                                    // Если не нашли точку, активируем первую
                                    $dots.find('.dot').first().addClass('active');
                                    console.warn('Dot not found for group:', normalizedGroup, 'available groups:', 
                                        Array.from($allDots).map(d => $(d).attr('data-group')).join(', '));
                                }
                            }, delay);
                        }
                        
                        // Удаляем базовый обработчик перед регистрацией детального
                        // Это важно, чтобы избежать дублирования обработчиков
                        if (productsSlider.off) {
                            // Удаляем базовый обработчик если он существует
                            if (window._blogProductsBaseHandler) {
                                productsSlider.off('moved', window._blogProductsBaseHandler);
                                window._blogProductsBaseHandler = null;
                            }
                            // Удаляем ВСЕ обработчики события 'moved', чтобы они не мешали
                            // Для типа 'slide' используем только событие 'move', так как 'moved' срабатывает с неправильным индексом
                            productsSlider.off('moved');
                        }
                        
                        // Регистрируем обработчик для мобильных устройств
                        // ВАЖНО: для типа 'slide' используем ТОЛЬКО событие 'move', так как 'moved' срабатывает с неправильным индексом
                        if (handleSliderMoved && productsSlider && productsSlider.on) {
                            // Регистрируем обработчик ТОЛЬКО для события 'move' (срабатывает раньше и с правильным индексом)
                            productsSlider.on('move', function(newIndex, prevIndex, destIndex) {
                                // Вызываем детальный обработчик с правильными параметрами
                                handleSliderMoved(productsSlider, newIndex, prevIndex);
                            });
                            // НЕ регистрируем для события 'moved', так как оно срабатывает с неправильным индексом для типа 'slide'
                        } else {
                            console.error('Cannot register handler:', {
                                handleSliderMoved: !!handleSliderMoved,
                                productsSlider: !!productsSlider,
                                hasOn: productsSlider && !!productsSlider.on
                            });
                        }
                        
                        // Помечаем, что обработчик зарегистрирован
                        if (sliderEl) {
                            sliderEl._handlerRegistered = true;
                        }
                        
                        
                        // Инициализируем точки при загрузке - используем текущий индекс слайдера
                        // Но только если пользователь еще не проскроллил (isFirstMove === true)
                        initTimeout = setTimeout(function() {
                            // Если пользователь уже проскроллил, не перезаписываем активную точку
                            if (!isFirstMove) {
                                return;
                            }
                            
                            let initialIndex = 0;
                            
                            // Пытаемся получить текущий индекс из слайдера
                            try {
                                if (productsSlider && productsSlider.index !== undefined && productsSlider.index !== null) {
                                    initialIndex = productsSlider.index;
                                } else if (productsSlider && productsSlider.Components && productsSlider.Components.Controller) {
                                    initialIndex = productsSlider.Components.Controller.getIndex() || 0;
                                }
                            } catch (e) {
                                console.warn('Could not get initial index from slider:', e);
                            }
                            
                            // Нормализуем индекс для типа 'loop'
                            if (!isMobile && initialIndex >= totalSlides) {
                                initialIndex = initialIndex % totalSlides;
                            }
                            
                            // Вычисляем группу
                            let initialGroup = Math.floor(initialIndex / groupSize);
                            
                            // Проверяем, не находимся ли мы на последних слайдах
                            const lastGroupStartIndex = Math.max(0, totalSlides - groupSize);
                            if (initialIndex >= lastGroupStartIndex) {
                                initialGroup = totalGroups - 1;
                            }
                            
                            // Нормализуем группу
                            if (initialGroup < 0) initialGroup = 0;
                            if (initialGroup >= totalGroups) initialGroup = totalGroups - 1;
                            
                            currentGroupIndex = initialGroup;
                            previousGroupIndex = initialGroup;
                            
                            $dots.find('.dot').removeClass('active');
                            const $firstDot = $dots.find('.dot[data-group="' + initialGroup + '"]');
                            if ($firstDot.length) {
                                $firstDot.addClass('active');
                            } else {
                                $dots.find('.dot').first().addClass('active');
                            }
                        }, 300);
                    } else {
                        $dots.hide();
                    }
                }, 100); // Небольшая задержка для инициализации Splide
            }
            
            // Регистрируем обработчик события для десктопа (для мобильных он уже зарегистрирован внутри условия)
            // Это важно для обновления точек навигации при скролле на десктопе
            // НО: на десктопе точки обычно скрыты, поэтому обработчик не нужен
            // Удаляем этот код, чтобы избежать дублирования обработчиков
            
            if (!isMobile) {
                $dots.hide();
            }
        }
    }
    
    // Initialize similar articles slider - используем Splide как у продуктов
    function initBlogSimilarSlider() {
        const sliderEl = document.querySelector('#blogSimilarSlider');
        
        if (sliderEl) {
            // Используем Splide с настройками для 3 карточек на десктопе, 1 на мобильном
            const isMobile = window.innerWidth <= 768;
            let similarSlider = new Splide('#blogSimilarSlider', {
                pagination: false,
                perPage: isMobile ? 1 : 3,
                gap: '5px',
                padding: {right: 0, left: 0},
                type: 'loop', // loop чтобы стрелки работали даже когда все карточки видны
                autoplay: false,
                arrows: !isMobile, // На мобилке скрываем стрелки, на десктопе показываем
                drag: true, // Включаем drag/swipe для мобильных
                swipe: true, // Включаем swipe для мобильных
                classes: {
                    arrows: 'splide__arrows home-products-slide-buttons',
                    prev: 'splide__arrow--prev',
                    next: 'splide__arrow--next',
                },
                breakpoints: {
                    1331: {
                        perPage: 3,
                        padding: {right: 0, left: 0},
                        arrows: true,
                        drag: true,
                        swipe: true,
                    },
                    1019: {
                        perPage: 2,
                        padding: {right: 0, left: 0},
                        arrows: true,
                        drag: true,
                        swipe: true,
                    },
                    768: {
                        perPage: 1, // На мобильных показываем 1 карточку
                        padding: {right: '0%', left: '0%'}, // Убираем padding чтобы не было видно соседних карточек
                        gap: '0px', // Убираем gap на мобильных
                        arrows: false, // На мобилке скрываем стрелки
                        drag: true, // Включаем drag для swipe
                        swipe: true, // Включаем swipe
                        type: 'slide', // Меняем тип на slide чтобы не было loop эффекта
                        focus: 'center', // Центрируем карточку
                    },
                    400: {
                        perPage: 1,
                        padding: {right: '0%', left: '0%'},
                        gap: '0px', // Убираем gap на мобильных
                        arrows: false,
                        drag: true,
                        swipe: true,
                        type: 'slide', // Меняем тип на slide чтобы не было loop эффекта
                        focus: 'center', // Центрируем карточку
                    }
                }
            });
            
            similarSlider.mount();
            
            // На мобилке создаем точки навигации
            const $similarDots = $('.blog-similar-slider-dots');
            if (isMobile && $similarDots.length > 0) {
                setTimeout(function() {
                    const $cards = $('#blogSimilarSlider .splide__slide');
                    const totalSlides = $cards.length;
                    
                    // Создаем точки (по одной на каждую карточку, так как показываем по 1)
                    $similarDots.empty();
                    if (totalSlides > 1) {
                        for (let i = 0; i < totalSlides; i++) {
                            $similarDots.append('<li><button class="dot' + (i === 0 ? ' active' : '') + '" data-index="' + i + '"></button></li>');
                        }
                        $similarDots.css('display', 'flex');
                        
                        // Переменная для отслеживания текущего индекса
                        let currentSlideIndex = 0;
                        
                        // Go to slide function
                        function goToSlide(index) {
                            if (index < 0 || index >= totalSlides) {
                                return;
                            }
                            
                            // Обновляем текущий индекс перед переходом
                            currentSlideIndex = index;
                            
                            if (similarSlider && typeof similarSlider.go === 'function') {
                                similarSlider.go(index);
                                
                                // Сразу обновляем точки с правильным индексом
                                updateDots(index);
                            }
                        }
                        
                        // Dot click handler
                        $(document).on('click', '.blog-similar-slider-dots .dot', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            const $dot = $(this);
                            const slideIndex = parseInt($dot.attr('data-index') || $dot.data('index') || 0);
                            
                            console.log('Dot clicked:', slideIndex);
                            
                            if (!isNaN(slideIndex) && slideIndex >= 0 && slideIndex < totalSlides) {
                                goToSlide(slideIndex);
                            }
                        });
                        
                        // Update dots on slide change - используем только событие moved для избежания конфликтов
                        let updateTimeout;
                        similarSlider.on('moved', function(splide, newIndex, prevIndex) {
                            // Отменяем предыдущий таймаут если есть
                            if (updateTimeout) {
                                clearTimeout(updateTimeout);
                            }
                            
                            // Для типа 'slide' используем newIndex напрямую
                            updateTimeout = setTimeout(function() {
                                // Получаем реальный индекс слайда
                                // Приоритет: newIndex > DOM проверка > splide.index > 0
                                let realIndex = newIndex;
                                
                                // Если newIndex определен и валиден, используем его
                                if (realIndex !== undefined && realIndex !== null && !isNaN(realIndex)) {
                                    realIndex = parseInt(realIndex);
                                } else {
                                    // Пробуем получить активный слайд через DOM
                                    const $activeSlide = $('#blogSimilarSlider .splide__slide.is-active');
                                    if ($activeSlide.length > 0) {
                                        // Получаем индекс среди всех слайдов
                                        const slideIndex = $activeSlide.index();
                                        if (slideIndex >= 0 && slideIndex < totalSlides) {
                                            realIndex = slideIndex;
                                        } else {
                                            // Если индекс не подходит, пробуем через data-index или порядковый номер
                                            const $allSlides = $('#blogSimilarSlider .splide__slide');
                                            realIndex = $allSlides.index($activeSlide);
                                        }
                                    } else {
                                        // Если не нашли активный слайд, пробуем через splide.index
                                        if (splide.index !== undefined && splide.index !== null) {
                                            realIndex = splide.index;
                                        } else {
                                            realIndex = 0;
                                        }
                                    }
                                }
                                
                                // Проверяем валидность
                                if (realIndex === undefined || realIndex === null || isNaN(realIndex)) {
                                    realIndex = 0;
                                }
                                
                                // Нормализуем индекс
                                realIndex = parseInt(realIndex);
                                if (realIndex < 0) {
                                    realIndex = 0;
                                }
                                if (realIndex >= totalSlides) {
                                    realIndex = totalSlides - 1;
                                }
                                
                                // Дополнительная проверка через DOM
                                const $activeSlideCheck = $('#blogSimilarSlider .splide__slide.is-active');
                                const domIndex = $activeSlideCheck.length > 0 ? $activeSlideCheck.index() : -1;
                                
                                console.log('Slider moved:', {
                                    newIndex: newIndex,
                                    splideIndex: splide.index,
                                    realIndex: realIndex,
                                    domIndex: domIndex,
                                    totalSlides: totalSlides
                                });
                                
                                // Используем realIndex, но если DOM показывает другой индекс и он валиден, используем его
                                if (domIndex >= 0 && domIndex < totalSlides && domIndex !== realIndex) {
                                    console.log('DOM index differs, using DOM index:', domIndex);
                                    realIndex = domIndex;
                                }
                                
                                currentSlideIndex = realIndex;
                                updateDots(realIndex);
                            }, 200);
                        });
                        
                        function updateDots(index) {
                            // Используем переданный индекс или текущий сохраненный индекс
                            let normalizedIndex = index !== undefined && index !== null ? index : currentSlideIndex;
                            
                            // Проверяем валидность индекса
                            if (normalizedIndex === undefined || normalizedIndex === null || isNaN(normalizedIndex)) {
                                normalizedIndex = 0;
                            }
                            
                            // Нормализуем индекс в пределах от 0 до totalSlides - 1
                            normalizedIndex = parseInt(normalizedIndex);
                            if (normalizedIndex < 0) {
                                normalizedIndex = 0;
                            }
                            if (normalizedIndex >= totalSlides) {
                                normalizedIndex = totalSlides - 1;
                            }
                            
                            // Обновляем сохраненный индекс
                            currentSlideIndex = normalizedIndex;
                            
                            console.log('Updating dots:', {
                                receivedIndex: index,
                                normalizedIndex: normalizedIndex,
                                totalSlides: totalSlides
                            });
                            
                            // Обновляем точки
                            $similarDots.find('.dot').removeClass('active');
                            const $activeDot = $similarDots.find('.dot[data-index="' + normalizedIndex + '"]');
                            if ($activeDot.length) {
                                $activeDot.addClass('active');
                            } else {
                                // Если не нашли точку по data-index, используем индекс элемента
                                const $dotByIndex = $similarDots.find('.dot').eq(normalizedIndex);
                                if ($dotByIndex.length) {
                                    $dotByIndex.addClass('active');
                                } else {
                                    // В крайнем случае активируем первую
                                    $similarDots.find('.dot').first().addClass('active');
                                }
                            }
                        }
                        
                        // Инициализируем точки при загрузке
                        setTimeout(function() {
                            // Для типа 'slide' индекс начинается с 0
                            const initialIndex = 0;
                            currentSlideIndex = initialIndex;
                            updateDots(initialIndex);
                        }, 300);
                    } else {
                        $similarDots.hide();
                    }
                }, 100);
            } else {
                if ($similarDots.length > 0) {
                    $similarDots.hide();
                }
            }
            
            // Принудительно показываем стрелки на десктопе
            setTimeout(function() {
                if ($(window).width() > 768) {
                    const $arrows = $('#blogSimilarSlider .splide__arrows');
                    if ($arrows.length > 0) {
                        $arrows.css({
                            'display': 'flex !important',
                            'visibility': 'visible',
                            'opacity': '1'
                        });
                    }
                } else {
                    // На мобильных скрываем стрелки
                    const $arrows = $('#blogSimilarSlider .splide__arrows');
                    if ($arrows.length > 0) {
                        $arrows.css({
                            'display': 'none !important',
                            'visibility': 'hidden',
                            'opacity': '0'
                        });
                    }
                }
            }, 300);
        }
    }
    
    // Initialize image sliders in product cards
    function initBlogProductImages() {
        imageSliderInProduct('blog-product-card');
    }
    
    // Initialize sliders on load and resize
    initLatestSlider();
    initCategorySliders();
    trimArticleTitles();
    initCategoriesAccordion();
    initBlogProductsSlider();
    initBlogSimilarSlider();
    initBlogProductImages();
    
    $(window).on('resize', function() {
        initLatestSlider();
        initCategorySliders();
        initBlogProductsSlider();
        initBlogSimilarSlider();
    });
});
