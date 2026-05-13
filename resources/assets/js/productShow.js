import {productSliderInitialization} from "./sliderInitialization";

$(document).ready(function () {
    var main = new Splide('#product-slider', {
        pagination: false,
        arrows: false,
    });

    var thumbnails = new Splide('#thumbnail-carousel', {
        perPage: 2,
        gap: 10,
        pagination: false,
        isNavigation: true,
    });

    main.sync(thumbnails);
    main.mount();
    thumbnails.mount();

    try {
        var productExampleImages = new Splide('#product-example-slider', {
            pagination: false,
            arrows: true,
            type: 'loop'
        });
        productExampleImages.mount()
    } catch (e) {
        // Галерея примеров может отсутствовать на некоторых страницах - это нормально
    }


    const quantityInput = $('#quantity-block .fast-order-quantity')
    const quantityPlus = $('#quantity-block #plus-btn')
    const quantityMinus = $('#quantity-block #minus-btn')
    const discounts = $('.item-price').data('discount')
    const price = $('.item-price .only-price')
    const totalPrice = $('.autocalc-product-price .total-price')

    const hiddenParams = $('.params .list .item.hide')
    const showAll = $('#show-all')
    const showLess = $('#show-less')

// discount text
    const textDiscount = $('.text-discount')
    const countForDiscont = $('.text-discount .count-for-discont')
    const savingVal = $('.text-discount .saving-val')

    recalcTotalPrice(quantityInput.val())
    initializationAllLessButtons()


    quantityPlus.on('click', function () {
        plusMinus(quantityInput, 'plus')
    })

    quantityMinus.on('click', function () {
        plusMinus(quantityInput, 'minus')
    })

    quantityInput.on('change', function () {
        plusMinus(quantityInput, 'change')
    })

    quantityInput.on('input', function () {
        let value = $(this).val();
        let newValue = value.replace(/[^0-9.]/g, '');
        let parts = newValue.split('.');

        if (parts.length > 2) {
            newValue = parts.shift() + '.' + parts.join('');
        }
        $(this).val(newValue);
    })

    function plusMinus(input, action) {
        let currentVal = parseFloat(input.val())
        const step = parseFloat(input.data('step'))
        const maxVal = parseFloat(input.data('max'))
        const minVal = parseFloat(input.data('min'))

        if (action === 'plus') {
            let newVal = currentVal + step
            if (maxVal > 0) {
                if (newVal >= maxVal) {
                    newVal = maxVal
                }
            } else {
                newVal = 0
            }

            recalcTotalPrice(newVal)
            input.val(decimalPoint(newVal))
        }

        if (action === 'minus') {
            let newVal = currentVal - step
            if (maxVal > 0) {
                if (newVal <= minVal) {
                    newVal = minVal
                }
            } else {
                newVal = 0
            }

            recalcTotalPrice(newVal)
            input.val(decimalPoint(newVal))
        }

        if (action === 'change') {
            if (maxVal > 0) {
                if (isNaN(currentVal)) {
                    currentVal = minVal
                }
                if (currentVal >= maxVal) {
                    currentVal = maxVal
                } else if (currentVal <= minVal) {
                    currentVal = minVal
                }
            } else {
                currentVal = 0
            }
            currentVal = integerNumber(currentVal, step)

            recalcTotalPrice(currentVal)
            input.val(decimalPoint(currentVal))
        }
        onOffSelectedQuantity()
    }

    function decimalPoint(val) {
        if (val % 1 === 0) {
            return val.toFixed(0)
        } else {
            return val.toFixed(1)
        }
    }

    function integerNumber(currentVal, step) {
        currentVal = Math.round(currentVal / step) * step
        if (isNaN(currentVal)) {
            currentVal = 0;
        }
        return currentVal
    }

    function recalcTotalPrice(newVal) {
        // Нормализуем в массив в правильном порядке по ключам 0,1,2...
        const tiers = Object.keys(discounts)
            .sort((a, b) => parseInt(a) - parseInt(b))
            .map(k => discounts[k]);

        if (!Array.isArray(tiers) || tiers.length === 0) {
            textDiscount.hide();
            return;
        }

        // Находим текущий активный уровень (максимальный discountFrom <= newVal)
        let currentIdx = 0;
        for (let i = 0; i < tiers.length; i++) {
            if (newVal >= parseFloat(tiers[i].discountFrom)) {
                currentIdx = i;
            }
        }
        const currentTier = tiers[currentIdx];
        const nextTier = tiers[currentIdx + 1];

        // Обновляем цену за ед. и сумму по текущему уровню
        const unitPrice = parseFloat(currentTier.price);
        price.text(unitPrice.toFixed(2));
        $('.autocalc-product-price .total-price').text((unitPrice * newVal).toFixed(2));
        restyleThreeLastChart('.price-wrap .only-price');
        restyleThreeLastChart('.autocalc-product-price .total-price-main');

        // Обновляем текст о следующей скидке, если она реально существует и даёт экономию
        if (nextTier) {
            const threshold = parseFloat(nextTier.discountFrom);
            const nextPrice = parseFloat(nextTier.price);
            const maxVal = parseFloat(quantityInput.data('max'));
            const left = threshold - newVal;
            
            // Если уже достигли/перешли порог — сразу скрываем сообщение
            if (left <= 1e-9) {
                textDiscount.hide();
                return;
            }
            
            // Если порог скидки недостижим (больше максимального количества) — не показываем
            if (maxVal > 0 && threshold > maxVal) {
                textDiscount.hide();
                return;
            }
            
            const potentialSaving = threshold * (unitPrice - nextPrice);

            if (potentialSaving > 0) {
                // показываем только если есть реальная экономия
                const leftDisplay = (left % 1 === 0) ? left.toFixed(0) : left.toFixed(1);
                countForDiscont.text(leftDisplay);
                savingVal.text(potentialSaving.toFixed(2));
                textDiscount.show();
            } else {
                textDiscount.hide();
            }
        } else {
            // Следующего уровня нет — скрываем
            textDiscount.hide();
        }
    }

    restyleThreeLastChart('.price-wrap .only-price')

    function restyleThreeLastChart(selector) {
        const element = $(selector);
        let text = element.text()
        if (text.length > 3) {
            let lastThree = text.slice(-3);
            element.html(text.slice(0, -3) + '<span class="highlight">' + lastThree + '</span>')
        }
    }

    function onOffSelectedQuantity() {
        choseQuantity.each(function (index) {
            $(this).removeClass('is-active')
            $(this).text($(this).data('choose'))

            if (choseQuantity[index + 1] !== undefined) {
                if (parseFloat(choseQuantity[index].getAttribute('data-quantity')) <= parseFloat(quantityInput.val())
                    && parseFloat(choseQuantity[index + 1].getAttribute('data-quantity')) > parseFloat(quantityInput.val())) {
                    $(this).addClass('is-active')
                    $(this).text($(this).data('chosen'))
                }
            } else {
                if (parseFloat(choseQuantity[index].getAttribute('data-quantity')) <= parseFloat(quantityInput.val())) {
                    $(this).addClass('is-active')
                    $(this).text($(this).data('chosen'))
                }
            }
        })
    }

//show hide params and buttons
    function initializationAllLessButtons() {
        if (hiddenParams.length !== 0) {
            showAll.show()
        }
    }

    function showAllParameters() {
        hiddenParams.each(function (index) {
            $(this).removeClass('hide')
        });
    }

    function hideAllParameters() {
        hiddenParams.each(function (index) {
            $(this).addClass('hide')
        });
    }

    showAll.on('click', function () {
        showAllParameters()
        $(this).hide()
        showLess.show()
    })

    showLess.on('click', function () {
        hideAllParameters()
        $(this).hide()
        showAll.show()
    })


// chose max value and click select button
    const choseMax = $('.max-value-group .select-max')
    const choseQuantity = $('.product-discounts .select-quantity')

    choseMax.on('click', function () {
        quantityInput.val($(this).data('quantity'))
        quantityInput.trigger('change');
    })


    choseQuantity.on('click', function () {
        quantityInput.val($(this).data('quantity'))
        quantityInput.trigger('change');
    })

    productSliderInitialization('homeLatest');
    
    // Инициализация слайдера YouTube видео
    initProductYoutubeVideoSlider();
    
    // Инициализация правильного источника видео в зависимости от устройства (для старых видео)
    initProductVideoSource();
    
    // Инициализация модального окна с видео для мобильных
    initProductVideoModal();
    
    // Убеждаемся, что модальное окно закрыто на десктопе при загрузке страницы
    const isMobile = window.innerWidth <= 767 || /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    if (!isMobile) {
        $('#product-video-modal').removeClass('active');
        $('body').removeClass('modal-open');
    }
});

/**
 * Инициализация оптимизированного видео с Intersection Observer
 * Видео загружается только при видимости и запускается автоматически
 * Использует постер для быстрой загрузки страницы
 */
function initProductVideoSource() {
    const isMobile = window.innerWidth <= 767 || /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    
    $('.product-page-video video.product-video-player').each(function() {
        const $video = $(this);
        const videoElement = this;
        const mobileVideo = $video.data('mobile-video');
        const desktopVideo = $video.data('desktop-video');
        const originalVideo = $video.data('original-video');
        
        let videoUrl = originalVideo;
        
        // Выбираем правильную версию видео в зависимости от устройства
        if (isMobile && mobileVideo && mobileVideo !== originalVideo) {
            videoUrl = mobileVideo;
        } else if (!isMobile && desktopVideo && desktopVideo !== originalVideo) {
            videoUrl = desktopVideo;
        }
        
        // Обновляем источник видео только если есть оптимизированная версия
        if (videoUrl && videoUrl !== originalVideo) {
            $video.find('source').attr('src', videoUrl);
            videoElement.load(); // Перезагружаем видео с новым источником
        }

        // Для мобильных: не пытаемся генерировать постер из видео,
        // оставляем статичную картинку товара, чтобы не грузить видео лишний раз
        if (!isMobile) {
            // Клиентское формирование постера из первого кадра видео (без FFmpeg)
            const generatePosterFromFrame = () => {
                try {
                    // Если у видео уже есть постер в base64 или из сервера, не перезаписываем его
                    const currentPoster = videoElement.getAttribute('poster');
                    if (currentPoster && currentPoster.startsWith('data:image')) {
                        return;
                    }

                    // Если размеры видео ещё не известны, пропускаем
                    if (!videoElement.videoWidth || !videoElement.videoHeight) {
                        return;
                    }

                    const canvas = document.createElement('canvas');
                    canvas.width = videoElement.videoWidth;
                    canvas.height = videoElement.videoHeight;
                    const ctx = canvas.getContext('2d');
                    if (!ctx) return;

                    ctx.drawImage(videoElement, 0, 0, canvas.width, canvas.height);
                    const dataUrl = canvas.toDataURL('image/jpeg', 0.8);
                    if (dataUrl) {
                        videoElement.setAttribute('poster', dataUrl);
                    }
                } catch (e) {
                    console.warn('Не удалось сгенерировать постер из кадра видео', e);
                }
            };

            // Генерируем постер один раз, когда данные видео загружены
            videoElement.addEventListener('loadeddata', function handleLoadedData() {
                generatePosterFromFrame();
                videoElement.removeEventListener('loadeddata', handleLoadedData);
            });
        }
        
        // Обработка ошибок - если оптимизированная версия не загрузилась, используем оригинал
        videoElement.addEventListener('error', function() {
            if (videoUrl !== originalVideo) {
                console.warn('Ошибка загрузки оптимизированного видео, используем оригинал');
                $video.find('source').attr('src', originalVideo);
                videoElement.load();
            }
        }, { once: true });
        
        // Обработчики событий и автоплей используем только на десктопе
        if (!isMobile) {
            const $playBtn = $('.product-video-play-btn');
            
            // На десктопе кнопка всегда видна, чтобы можно было открыть полноэкранный режим
            // При автоплее кнопка не скрывается
            videoElement.addEventListener('play', function() {
                // На десктопе не скрываем кнопку при автоплее
                // Кнопка нужна для открытия полноэкранного режима
            });
            
            videoElement.addEventListener('pause', function() {
                // Видео на паузе - показываем кнопку только если не в полноэкранном режиме
                if (!document.fullscreenElement && !document.webkitFullscreenElement && 
                    !document.mozFullScreenElement && !document.msFullscreenElement) {
                    $playBtn.fadeIn(200);
                }
            });
            
            videoElement.addEventListener('ended', function() {
                // Видео закончилось - показываем кнопку
                $playBtn.fadeIn(200);
                // Если не в полноэкранном режиме, скрываем controls
                if (!document.fullscreenElement && !document.webkitFullscreenElement && 
                    !document.mozFullScreenElement && !document.msFullscreenElement) {
                    videoElement.controls = false;
                }
            });
            
            // При выходе из полноэкранного режима показываем кнопку и скрываем controls
            document.addEventListener('fullscreenchange', function() {
                if (!document.fullscreenElement) {
                    $playBtn.fadeIn(200);
                    videoElement.controls = false;
                    videoElement.muted = true;
                }
            });
            document.addEventListener('webkitfullscreenchange', function() {
                if (!document.webkitFullscreenElement) {
                    $playBtn.fadeIn(200);
                    videoElement.controls = false;
                    videoElement.muted = true;
                }
            });
            document.addEventListener('mozfullscreenchange', function() {
                if (!document.mozFullScreenElement) {
                    $playBtn.fadeIn(200);
                    videoElement.controls = false;
                    videoElement.muted = true;
                }
            });
            document.addEventListener('MSFullscreenChange', function() {
                if (!document.msFullscreenElement) {
                    $playBtn.fadeIn(200);
                    videoElement.controls = false;
                    videoElement.muted = true;
                }
            });
            
            // При клике на само видео открываем модальное окно (как при клике на кнопку)
            videoElement.addEventListener('click', function(e) {
                // Не открываем модальное окно, если уже открыто
                if ($('#product-video-modal-desktop').hasClass('active')) {
                    return;
                }
                
                // Триггерим клик на кнопку воспроизведения
                $playBtn.trigger('click');
            });
        }
        
        // Intersection Observer для автоплея при видимости видео
        // На мобильных НЕ используем автоплей, чтобы не грузить видео на слабом интернете
        if (!isMobile && 'IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        // Видео видимо - запускаем автоплей (и на десктопе, и на мобильных)
                        // На десктопе видео будет без звука и без controls (как было раньше)
                        if (!isMobile) {
                            videoElement.muted = true;
                            videoElement.controls = false;
                        }
                        const playPromise = videoElement.play();
                        if (playPromise !== undefined) {
                            playPromise.catch(error => {
                                // Автоплей заблокирован браузером
                                console.log('Автоплей заблокирован браузером');
                            });
                        }
                    } else {
                        // Видео не видимо - останавливаем
                        videoElement.pause();
                    }
                });
            }, {
                threshold: 0.5,
                rootMargin: '0px'
            });
            
            observer.observe(videoElement);
        } else if (!isMobile) {
            // Fallback для старых браузеров - просто запускаем автоплей
            const playPromise = videoElement.play();
            if (playPromise !== undefined) {
                playPromise.catch(error => {
                    console.log('Автоплей заблокирован браузером');
                });
            }
        }
    });
}

/**
 * Инициализация слайдера YouTube видео
 */
function initProductYoutubeVideoSlider() {
    const $slider = $('#product-video-slider');
    if ($slider.length === 0) {
        return; // Нет YouTube видео
    }
    
    // Проверяем, не инициализируется ли слайдер уже
    if ($slider[0].isInitializing) {
        console.log('Product video slider is already initializing, skipping');
        return;
    }
    
    // Проверяем, инициализирован ли слайдер уже
    let existingSlider = $slider[0].sliderInstance || $slider[0].splide;
    
    // Если слайдер уже инициализирован, уничтожаем его перед переинициализацией
    if (existingSlider && existingSlider.destroy) {
        console.log('Destroying existing product video slider instance');
        try {
            existingSlider.destroy();
        } catch (e) {
            console.warn('Error destroying slider:', e);
        }
        $slider[0].sliderInstance = null;
        $slider[0].splide = null;
    }
    
    // Принудительно удаляем все элементы Splide (пагинация, стрелки), которые могли остаться после destroy
    // Это важно для предотвращения дублирования точек пагинации
    $slider.find('.splide__pagination').remove();
    $slider.find('.splide__arrows').remove();
    $slider.find('.splide__pagination__page').remove();
    
    // Помечаем, что начинаем инициализацию
    $slider[0].isInitializing = true;
    
    const isMobile = window.innerWidth <= 767 || /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    
    // Проверяем реальное количество слайдов перед инициализацией
    const actualSlideCount = $slider.find('.splide__slide').length;
    console.log('Initializing product video slider with', actualSlideCount, 'slides');
    
    if (actualSlideCount === 0) {
        console.error('No slides found in product video slider');
        $slider[0].isInitializing = false;
        return;
    }
    
    // Небольшая задержка для гарантии, что DOM обновился после удаления старых элементов
    setTimeout(() => {
        // Дополнительная проверка и очистка перед созданием нового слайдера
        const remainingPagination = $slider.find('.splide__pagination').length;
        const remainingArrows = $slider.find('.splide__arrows').length;
        if (remainingPagination > 0 || remainingArrows > 0) {
            console.log('Found remaining Splide elements before init, removing:', remainingPagination, 'pagination,', remainingArrows, 'arrows');
            $slider.find('.splide__pagination').remove();
            $slider.find('.splide__arrows').remove();
        }
        
        // Инициализируем Splide слайдер для основного блока
        const slider = new Splide('#product-video-slider', {
            type: 'loop',
            perPage: 1,
            perMove: 1,
            pagination: true,
            arrows: true,
            autoplay: false,
            pauseOnHover: true,
            resetProgress: false,
            speed: 400,
            rewind: true,
        });
        
        // Сохраняем ссылку на экземпляр слайдера
        $slider[0].sliderInstance = slider;
        
        // При смене слайда останавливаем предыдущее видео (если было запущено)
        slider.on('moved', function(newIndex, prevIndex) {
            // Останавливаем предыдущие видео (YouTube iframe и HTML5 video)
            const slides = slider.Components.Slides.slides;
            let slidesArray = slides;
            if (!Array.isArray(slides)) {
                slidesArray = Array.from(slides || []);
            }
            
            slidesArray.forEach((slide, index) => {
                if (index !== newIndex && slide) {
                    // Останавливаем YouTube iframe
                    const iframe = slide.querySelector('iframe');
                    if (iframe && iframe.src) {
                        // Просто очищаем src вместо postMessage - это надежнее и не вызывает ошибок
                        if (index !== newIndex) {
                            iframe.src = '';
                        }
                    }
                    
                    // Останавливаем HTML5 video элементы
                    const videoElement = slide.querySelector('video');
                    if (videoElement) {
                        videoElement.pause();
                        videoElement.currentTime = 0;
                    }
                }
            });
            
            // На десктопе автозапускаем новое видео через Intersection Observer
            if (!isMobile) {
                const currentSlide = slider.Components.Slides.slides[newIndex];
                if (currentSlide) {
                    const youtubeId = currentSlide.querySelector('.product-video-slide')?.dataset.youtubeId;
                    if (youtubeId && 'IntersectionObserver' in window) {
                        const observer = new IntersectionObserver((entries) => {
                            entries.forEach(entry => {
                                if (entry.isIntersecting) {
                                    const slide = entry.target.closest('.splide__slide');
                                    const slideYoutubeId = slide?.querySelector('.product-video-slide')?.dataset.youtubeId;
                                    if (slideYoutubeId) {
                                        // Создаем iframe для автоплея
                                        const container = slide.querySelector('.product-video-youtube-container');
                                        if (container && !container.querySelector('iframe')) {
                                            const iframe = document.createElement('iframe');
                                            iframe.className = 'product-video-youtube-iframe';
                                            iframe.src = `https://www.youtube.com/embed/${slideYoutubeId}?autoplay=1&rel=0&modestbranding=1&mute=1`;
                                            iframe.frameBorder = '0';
                                            iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
                                            iframe.allowFullscreen = true;
                                            container.innerHTML = '';
                                            container.appendChild(iframe);
                                        }
                                    }
                                    observer.unobserve(entry.target);
                                }
                            });
                        }, { threshold: 0.5 });
                        
                        observer.observe(currentSlide);
                    }
                }
            }
        });
        
        slider.on('mounted', function() {
            // Сбрасываем флаг инициализации после успешного монтирования
            setTimeout(() => {
                if ($slider[0]) {
                    $slider[0].isInitializing = false;
                }
            }, 500);
        });
        
        slider.mount();
        
        // Проверяем количество точек пагинации после монтирования
        setTimeout(() => {
            const paginationPages = $slider.find('.splide__pagination__page').length;
            const slidesInDOM = $slider.find('.splide__slide').length;
            console.log('Product video slider - Pagination pages count after mount:', paginationPages, 'slides in DOM:', slidesInDOM, 'expected:', actualSlideCount);
            
            // Если точек больше, чем слайдов, это проблема - логируем предупреждение
            if (paginationPages > actualSlideCount) {
                console.warn('WARNING: Found more pagination pages than slides in product video slider! This indicates a cleanup issue.');
                console.warn('Pagination pages:', paginationPages, 'Actual slides:', actualSlideCount);
            }
        }, 300);
    }, 50); // Завершаем setTimeout для очистки и инициализации
}

/**
 * Глобальная функция для загрузки iframe в слайд модального слайдера
 */
function loadIframeForModalSlide(modalSliderInstance, slideIndex) {
    // Проверяем, что слайдер инициализирован и есть слайды
    if (!modalSliderInstance || !modalSliderInstance.Components || !modalSliderInstance.Components.Slides || !modalSliderInstance.Components.Slides.slides) {
        console.error('Modal slider not ready, cannot load iframe for slide', slideIndex);
        return;
    }
    
    let slides = modalSliderInstance.Components.Slides.slides;
    
    // Если slides - это NodeList или другой объект, конвертируем в массив
    if (!Array.isArray(slides)) {
        slides = Array.from(slides || []);
    }
    
    if (!slides || slides.length === 0) {
        console.error('No slides available in modal slider');
        return;
    }
    
    if (slideIndex < 0 || slideIndex >= slides.length) {
        console.error('Invalid slide index:', slideIndex, 'available slides:', slides.length);
        return;
    }
    
    const slide = slides[slideIndex];
    if (!slide) {
        console.error('Slide not found at index', slideIndex);
        return;
    }
    
    console.log('Processing slide', slideIndex, 'slide element:', slide);
    const iframeContainer = slide.querySelector('.product-video-youtube-iframe-container');
    
    if (!iframeContainer) {
        console.error('iframeContainer not found in slide', slideIndex);
        return;
    }
    
    let youtubeId = iframeContainer.dataset.youtubeId;
    const youtubeUrl = iframeContainer.dataset.youtubeUrl;
    
    console.log('Slide', slideIndex, 'youtubeId:', youtubeId, 'youtubeUrl:', youtubeUrl);
    
    // Если youtube_id пустой, пытаемся извлечь из URL
    if ((!youtubeId || youtubeId === 'null' || youtubeId === '') && youtubeUrl) {
        const urlMatch = youtubeUrl.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/);
        if (urlMatch && urlMatch[1]) {
            youtubeId = urlMatch[1];
            console.log('Extracted youtubeId from URL:', youtubeId);
        }
    }
    
    let iframe = slide.querySelector('iframe');
    
    if (youtubeId && youtubeId !== 'null' && youtubeId !== '' && youtubeId !== 'undefined') {
        console.log('Loading iframe for slide', slideIndex, 'youtubeId:', youtubeId);
        
        // Если iframe не существует, создаем его
        if (!iframe) {
            console.log('Creating new iframe for slide', slideIndex);
            iframe = document.createElement('iframe');
            iframe.className = 'product-video-youtube-iframe';
            iframe.frameBorder = '0';
            iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
            iframe.allowFullscreen = true;
            iframeContainer.appendChild(iframe);
        }
        
        // Загружаем видео только если iframe еще не загружен
        // Добавляем controls=1 для возможности управления видео (пауза, перемотка)
        const embedUrl = `https://www.youtube.com/embed/${youtubeId}?autoplay=1&rel=0&modestbranding=1&playsinline=1&controls=1`;
        if (!iframe.src || iframe.src !== embedUrl) {
            console.log('Setting iframe src to:', embedUrl);
            iframe.src = embedUrl;
        } else {
            console.log('Iframe already has correct src');
        }
    } else {
        console.error('Invalid youtubeId for slide', slideIndex, 'youtubeId:', youtubeId, 'youtubeUrl:', youtubeUrl);
    }
}

/**
 * Прямая загрузка iframe для слайда через DOM элемент
 */
function loadIframeForModalSlideDirect(slideElement, slideIndex) {
    if (!slideElement) {
        console.error('Slide element not provided');
        return;
    }
    
    console.log('Loading iframe directly for slide element', slideIndex);
    const iframeContainer = slideElement.querySelector('.product-video-youtube-iframe-container');
    
    if (!iframeContainer) {
        console.error('iframeContainer not found in slide', slideIndex);
        return;
    }
    
    let youtubeId = iframeContainer.dataset.youtubeId;
    const youtubeUrl = iframeContainer.dataset.youtubeUrl;
    
    console.log('Slide', slideIndex, 'youtubeId:', youtubeId, 'youtubeUrl:', youtubeUrl);
    
    // Если youtube_id пустой, пытаемся извлечь из URL
    if ((!youtubeId || youtubeId === 'null' || youtubeId === '') && youtubeUrl) {
        const urlMatch = youtubeUrl.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/);
        if (urlMatch && urlMatch[1]) {
            youtubeId = urlMatch[1];
            console.log('Extracted youtubeId from URL:', youtubeId);
        }
    }
    
    // Показываем индикатор загрузки
    let loadingSpinner = iframeContainer.querySelector('.product-video-loading-spinner');
    if (!loadingSpinner) {
        loadingSpinner = document.createElement('div');
        loadingSpinner.className = 'product-video-loading-spinner';
        const loadingText = document.documentElement.lang === 'ru' ? 'Загрузка видео...' : 
                           document.documentElement.lang === 'en' ? 'Loading video...' : 'Завантаження відео...';
        loadingSpinner.innerHTML = '<div class="spinner"></div><p>' + loadingText + '</p>';
        iframeContainer.insertBefore(loadingSpinner, iframeContainer.firstChild);
    }
    loadingSpinner.classList.remove('hidden');
    
    let iframe = slideElement.querySelector('iframe');
    
    if (youtubeId && youtubeId !== 'null' && youtubeId !== '' && youtubeId !== 'undefined') {
        console.log('Loading iframe for slide', slideIndex, 'youtubeId:', youtubeId);
        
        // Если iframe не существует, создаем его
        if (!iframe) {
            console.log('Creating new iframe for slide', slideIndex);
            iframe = document.createElement('iframe');
            iframe.className = 'product-video-youtube-iframe';
            iframe.frameBorder = '0';
            iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
            iframe.allowFullscreen = true;
            iframeContainer.appendChild(iframe);
        }
        
        // Обработчик загрузки iframe - скрываем индикатор загрузки
        iframe.onload = function() {
            console.log('Iframe loaded for slide', slideIndex);
            if (loadingSpinner) {
                setTimeout(() => {
                    loadingSpinner.classList.add('hidden');
                }, 300);
            }
        };
        
        // Загружаем видео только если iframe еще не загружен
        // Добавляем controls=1 для возможности управления видео (пауза, перемотка)
        const embedUrl = `https://www.youtube.com/embed/${youtubeId}?autoplay=1&rel=0&modestbranding=1&playsinline=1&controls=1`;
        
        // Принудительно сбрасываем src перед установкой нового значения (для повторного открытия)
        // Это гарантирует, что видео загрузится даже если iframe.src был очищен при закрытии модалки
        if (!iframe.src || iframe.src === '' || iframe.src === 'about:blank' || iframe.src !== embedUrl) {
            console.log('Setting iframe src to:', embedUrl, 'previous src:', iframe.src);
            // Сначала очищаем src, чтобы гарантировать перезагрузку
            iframe.src = '';
            // Небольшая задержка перед установкой нового src для надежности
            setTimeout(() => {
                iframe.src = embedUrl;
            }, 50);
        } else {
            console.log('Iframe already has correct src');
            // Если iframe уже загружен, скрываем индикатор через небольшую задержку
            if (loadingSpinner && iframe.src) {
                setTimeout(() => {
                    loadingSpinner.classList.add('hidden');
                }, 500);
            }
        }
    } else {
        console.error('Invalid youtubeId for slide', slideIndex, 'youtubeId:', youtubeId, 'youtubeUrl:', youtubeUrl);
        // Скрываем индикатор при ошибке
        if (loadingSpinner) {
            loadingSpinner.classList.add('hidden');
        }
    }
}

/**
 * Инициализация модального слайдера при открытии модального окна
 */
function initModalSlider() {
    const $modalSlider = $('#product-video-modal-slider');
    if ($modalSlider.length === 0) {
        console.error('Modal slider element not found');
        return;
    }
    
    // Проверяем, не инициализируется ли слайдер уже
    if ($modalSlider[0].isInitializing) {
        console.log('Modal slider is already initializing, skipping');
        return;
    }
    
    const slideCount = $modalSlider.find('.splide__slide').length;
    console.log('Initializing modal slider with', slideCount, 'slides');
    
    if (slideCount === 0) {
        console.error('No slides found in modal slider');
        return;
    }
    
    // Помечаем, что начинаем инициализацию
    $modalSlider[0].isInitializing = true;
    
    // Проверяем, инициализирован ли слайдер
    let modalSliderInstance = $modalSlider[0].modalSliderInstance || $modalSlider[0].splide;
    
    // Если слайдер уже инициализирован, уничтожаем его перед переинициализацией
    if (modalSliderInstance && modalSliderInstance.destroy) {
        console.log('Destroying existing modal slider instance');
        try {
            modalSliderInstance.destroy();
        } catch (e) {
            console.warn('Error destroying slider:', e);
        }
        $modalSlider[0].modalSliderInstance = null;
        $modalSlider[0].splide = null;
    }
    
    // Принудительно удаляем все элементы Splide (пагинация, стрелки), которые могли остаться после destroy
    // Это важно для предотвращения дублирования точек пагинации
    $modalSlider.find('.splide__pagination').remove();
    $modalSlider.find('.splide__arrows').remove();
    $modalSlider.find('.splide__pagination__page').remove();
    
    // Определяем, нужно ли показывать пагинацию (только на мобильных)
    const isMobile = window.innerWidth <= 767 || /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    
    // Проверяем реальное количество слайдов перед инициализацией
    const actualSlideCount = $modalSlider.find('.splide__slide').length;
    console.log('Actual slide count in modal:', actualSlideCount);
    
    if (actualSlideCount === 0) {
        console.error('No slides found in modal slider');
        $modalSlider[0].isInitializing = false;
        return;
    }
    
    // Небольшая задержка для гарантии, что DOM обновился после удаления старых элементов
    // Это важно для предотвращения дублирования пагинации
    setTimeout(() => {
        // Дополнительная проверка и очистка перед созданием нового слайдера
        const remainingPagination = $modalSlider.find('.splide__pagination').length;
        const remainingArrows = $modalSlider.find('.splide__arrows').length;
        if (remainingPagination > 0 || remainingArrows > 0) {
            console.log('Found remaining Splide elements before init, removing:', remainingPagination, 'pagination,', remainingArrows, 'arrows');
            $modalSlider.find('.splide__pagination').remove();
            $modalSlider.find('.splide__arrows').remove();
        }
        
        // Создаем новый экземпляр слайдера после очистки
        const newModalSliderInstance = new Splide('#product-video-modal-slider', {
            type: 'slide',
            perPage: 1,
            perMove: 1,
            pagination: isMobile, // Пагинация только на мобильных устройствах
            arrows: true,
            autoplay: false,
            speed: 400,
        });
        
        // Сохраняем ссылку на экземпляр слайдера
        $modalSlider[0].modalSliderInstance = newModalSliderInstance;
        modalSliderInstance = newModalSliderInstance;
        
        // Добавляем обработчики событий
    // Функция для загрузки iframe после готовности слайдера
    const loadIframeAfterMount = function() {
        let attempts = 0;
        const maxAttempts = 10;
        
        const tryLoad = function() {
            attempts++;
            console.log('Attempt', attempts, 'to load iframe');
            
            // Пробуем получить слайды через DOM напрямую
            const slideElements = $modalSlider.find('.splide__slide');
            console.log('Found', slideElements.length, 'slide elements in DOM');
            
            if (slideElements.length > 0) {
                // Проверяем, нужно ли переключиться на конкретный слайд
                const targetYoutubeId = $modalSlider[0].targetYoutubeId;
                let targetIndex = 0;
                
                if (targetYoutubeId) {
                    console.log('Looking for slide with youtubeId:', targetYoutubeId);
                    // Ищем слайд с нужным YouTube ID
                    slideElements.each(function(index) {
                        const slide = this;
                        const iframeContainer = slide.querySelector('.product-video-youtube-iframe-container');
                        if (iframeContainer) {
                            let slideYoutubeId = iframeContainer.dataset.youtubeId;
                            const slideYoutubeUrl = iframeContainer.dataset.youtubeUrl;
                            
                            if ((!slideYoutubeId || slideYoutubeId === 'null' || slideYoutubeId === '') && slideYoutubeUrl) {
                                const urlMatch = slideYoutubeUrl.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/);
                                if (urlMatch && urlMatch[1]) {
                                    slideYoutubeId = urlMatch[1];
                                }
                            }
                            
                            if (slideYoutubeId === targetYoutubeId) {
                                targetIndex = index;
                                console.log('Found target slide at index', index);
                            }
                        }
                    });
                    
                    // Переключаемся на нужный слайд
                    if (targetIndex > 0) {
                        modalSliderInstance.go(targetIndex);
                        // После переключения загружаем iframe для нового слайда
                        setTimeout(() => {
                            const slideArray = slideElements.toArray();
                            if (slideArray[targetIndex]) {
                                loadIframeForModalSlideDirect(slideArray[targetIndex], targetIndex);
                            }
                        }, 300);
                        return;
                    }
                }
                
                // Загружаем iframe для текущего слайда
                const currentIndex = modalSliderInstance.index || targetIndex || 0;
                console.log('Loading iframe for slide', currentIndex, 'of', slideElements.length);
                
                const slideArray = slideElements.toArray();
                if (slideArray[currentIndex]) {
                    loadIframeForModalSlideDirect(slideArray[currentIndex], currentIndex);
                } else {
                    console.error('Slide element not found at index', currentIndex);
                }
            } else {
                // Если слайды не найдены в DOM, пробуем через Components
                if (modalSliderInstance.Components && modalSliderInstance.Components.Slides && modalSliderInstance.Components.Slides.slides) {
                    let slides = modalSliderInstance.Components.Slides.slides;
                    if (!Array.isArray(slides)) {
                        slides = Array.from(slides || []);
                    }
                    
                    if (slides && slides.length > 0) {
                        const currentIndex = modalSliderInstance.index || 0;
                        console.log('Loading iframe via Components for slide', currentIndex);
                        loadIframeForModalSlide(modalSliderInstance, currentIndex);
                    } else {
                        if (attempts < maxAttempts) {
                            setTimeout(tryLoad, 200);
                        } else {
                            console.error('Failed to load slides after', maxAttempts, 'attempts');
                        }
                    }
                } else {
                    if (attempts < maxAttempts) {
                        setTimeout(tryLoad, 200);
                    } else {
                        console.error('Slides not ready after', maxAttempts, 'attempts');
                    }
                }
            }
        };
        
        // Начинаем попытки через небольшую задержку
        setTimeout(tryLoad, 300);
    };
    
    modalSliderInstance.on('mounted', function() {
        console.log('Modal slider mounted successfully - calling loadIframeAfterMount');
        loadIframeAfterMount();
        
        // Сбрасываем флаг инициализации после успешного монтирования
        setTimeout(() => {
            if ($modalSlider[0]) {
                $modalSlider[0].isInitializing = false;
            }
        }, 1000);
    });
    
    // Также добавляем обработчик ready на случай, если mounted срабатывает слишком рано
    modalSliderInstance.on('ready', function() {
        console.log('Modal slider ready event fired');
        // Небольшая задержка для гарантии, что слайды готовы
        setTimeout(() => {
            if (!modalSliderInstance.Components || !modalSliderInstance.Components.Slides || !modalSliderInstance.Components.Slides.slides) {
                console.log('Slides still not ready in ready event, will retry');
                loadIframeAfterMount();
            } else {
                // Сбрасываем флаг инициализации после готовности
                if ($modalSlider[0]) {
                    $modalSlider[0].isInitializing = false;
                }
            }
        }, 100);
    });
    
    modalSliderInstance.on('moved', function(newIndex) {
        console.log('Modal slider moved to slide', newIndex);
        
        // Загружаем iframe для нового слайда через DOM
        const slideElements = $modalSlider.find('.splide__slide').toArray();
        if (slideElements[newIndex]) {
            loadIframeForModalSlideDirect(slideElements[newIndex], newIndex);
        }
        
        // Останавливаем предыдущие видео через DOM (YouTube iframe и HTML5 video)
        slideElements.forEach((slide, index) => {
            if (index !== newIndex && slide) {
                // Останавливаем YouTube iframe
                const iframe = slide.querySelector('iframe');
                if (iframe && iframe.src) {
                    // Просто очищаем src вместо postMessage - это надежнее и не вызывает ошибок
                    if (index !== newIndex) {
                        iframe.src = '';
                    }
                }
                
                // Останавливаем HTML5 video элементы
                const videoElement = slide.querySelector('video');
                if (videoElement) {
                    videoElement.pause();
                    videoElement.currentTime = 0;
                }
            }
        });
        });
        
        // Монтируем слайдер
        newModalSliderInstance.mount();
        
        // Проверяем количество точек пагинации после монтирования (только на мобильных)
        // Это для отладки - если точек больше, чем слайдов, значит есть проблема с очисткой
        if (isMobile) {
            setTimeout(() => {
                const paginationPages = $modalSlider.find('.splide__pagination__page').length;
                const slidesInDOM = $modalSlider.find('.splide__slide').length;
                console.log('Pagination pages count after mount:', paginationPages, 'slides in DOM:', slidesInDOM, 'expected:', actualSlideCount);
                
                // Если точек больше, чем слайдов, это проблема - логируем предупреждение
                if (paginationPages > actualSlideCount) {
                    console.warn('WARNING: Found more pagination pages than slides! This indicates a cleanup issue.');
                    console.warn('Pagination pages:', paginationPages, 'Actual slides:', actualSlideCount);
                }
            }, 200);
        }
        
        // Добавляем обработчики для кнопок навигации, чтобы предотвратить перехват кликов YouTube iframe
        const setupArrowHandlers = function() {
        const $arrows = $modalSlider.find('.splide__arrow');
        if ($arrows.length === 0) {
            setTimeout(setupArrowHandlers, 100);
            return;
        }
        
        // Удаляем старые обработчики, если они есть
        $arrows.off('click.arrow-handler mousedown.arrow-handler touchstart.arrow-handler pointerdown.arrow-handler');
        
        // Блокируем pointer-events для всех iframe перед кликом (на ранней стадии события)
        $arrows.on('pointerdown.arrow-handler mousedown.arrow-handler touchstart.arrow-handler', function(e) {
            e.stopPropagation();
            e.stopImmediatePropagation();
            
            // Блокируем все iframe в слайдере немедленно
            $modalSlider.find('iframe').each(function() {
                this.style.pointerEvents = 'none';
                this.style.zIndex = '1';
            });
            
            // Убеждаемся, что кнопка имеет максимальный z-index
            $(this).css({
                'z-index': '10000',
                'pointer-events': 'auto'
            });
        });
        
        // Обрабатываем клик
        $arrows.on('click.arrow-handler', function(e) {
            // Останавливаем всплытие события
            e.stopPropagation();
            e.stopImmediatePropagation();
            e.preventDefault();
            
            // Блокируем все iframe в слайдере
            $modalSlider.find('iframe').each(function() {
                this.style.pointerEvents = 'none';
                this.style.zIndex = '1';
            });
            
            // Восстанавливаем pointer-events для текущего видео через задержку
            setTimeout(() => {
                const currentIndex = modalSliderInstance.index || 0;
                const slideElements = $modalSlider.find('.splide__slide').toArray();
                if (slideElements[currentIndex]) {
                    const currentIframe = slideElements[currentIndex].querySelector('iframe');
                    if (currentIframe) {
                        currentIframe.style.pointerEvents = 'auto';
                        currentIframe.style.zIndex = '1';
                    }
                }
            }, 300);
        });
        };
        
        // Устанавливаем обработчики после монтирования слайдера
        newModalSliderInstance.on('mounted', function() {
            setTimeout(setupArrowHandlers, 100);
        });
        
        // Также устанавливаем обработчики сразу на случай, если mounted уже сработал
        setTimeout(setupArrowHandlers, 500);
    }, 50); // Завершаем setTimeout для очистки и инициализации
}

/**
 * Инициализация модального окна с видео и попапом товара для мобильных
 */
function initProductVideoModal() {
    // Открытие модального окна при клике на кнопку воспроизведения (только на мобильных устройствах)
    $(document).on('click', '.product-video-play-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // Проверяем, что это мобильное устройство
        const isMobile = window.innerWidth <= 767 || /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        
        // Проверяем, это YouTube видео или старое видео
        const $playBtn = $(this);
        const youtubeId = $playBtn.data('youtube-id');
        const youtubeUrl = $playBtn.data('youtube-url');
        
        // Если это YouTube видео на десктопе - открываем модальное окно с YouTube
        if (!isMobile && youtubeId) {
            const modalHtml = `
                <div id="product-video-modal-desktop-youtube" class="product-video-modal-desktop">
                    <div class="product-video-modal-desktop-overlay"></div>
                    <div class="product-video-modal-desktop-container">
                        <button class="product-video-modal-desktop-close" aria-label="Close">
                            <i class="fal fa-times"></i>
                        </button>
                        <div class="product-video-youtube-iframe-container">
                            <iframe 
                                class="product-video-youtube-iframe"
                                src="https://www.youtube.com/embed/${youtubeId}?autoplay=1&rel=0&modestbranding=1"
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen>
                            </iframe>
                        </div>
                    </div>
                </div>
            `;
            
            $('#product-video-modal-desktop-youtube').remove();
            $('body').append(modalHtml);
            
            const $desktopModal = $('#product-video-modal-desktop-youtube');
            $desktopModal.addClass('active');
            $('body').addClass('modal-open');
            
            // Закрытие модального окна
            $desktopModal.find('.product-video-modal-desktop-close, .product-video-modal-desktop-overlay').on('click', function() {
                const iframe = $desktopModal.find('iframe')[0];
                if (iframe) {
                    // Просто очищаем src вместо postMessage - это надежнее и не вызывает ошибок
                    iframe.src = '';
                }
                $desktopModal.removeClass('active');
                $('body').removeClass('modal-open');
            });
            
            // Закрытие по ESC
            $(document).on('keydown.product-video-desktop-youtube', function(e) {
                if (e.key === 'Escape' && $desktopModal.hasClass('active')) {
                    const iframe = $desktopModal.find('iframe')[0];
                    if (iframe) {
                    // Просто очищаем src вместо postMessage - это надежнее и не вызывает ошибок
                    iframe.src = '';
                    }
                    $desktopModal.removeClass('active');
                    $('body').removeClass('modal-open');
                    $(document).off('keydown.product-video-desktop-youtube');
                }
            });
            return;
        }
        
        // На десктопе открываем модальное окно с видео и controls (не полноэкранный режим) - для старых видео
        if (!isMobile) {
            const $mainVideo = $('.product-page-video video.product-video-player');
            const videoElement = $mainVideo[0];
            
            if (videoElement) {
                // Получаем данные для модального окна
                const originalVideoUrl = $playBtn.data('src');
                const desktopVideo = $mainVideo.data('desktop-video');
                const videoUrl = desktopVideo && desktopVideo !== originalVideoUrl ? desktopVideo : originalVideoUrl;
                
                // Создаем модальное окно для десктопа (отдельное от мобильного)
                const modalHtml = `
                    <div id="product-video-modal-desktop" class="product-video-modal-desktop">
                        <div class="product-video-modal-desktop-overlay"></div>
                        <div class="product-video-modal-desktop-container">
                            <button class="product-video-modal-desktop-close" aria-label="Close">
                                <i class="fal fa-times"></i>
                            </button>
                            <video 
                                id="product-video-modal-desktop-player"
                                class="product-video-modal-desktop-player"
                                controls
                                autoplay
                                playsinline
                                preload="auto">
                                <source src="${videoUrl}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    </div>
                `;
                
                // Удаляем старое модальное окно если есть
                $('#product-video-modal-desktop').remove();
                
                // Добавляем модальное окно в body
                $('body').append(modalHtml);
                
                const $desktopModal = $('#product-video-modal-desktop');
                const $desktopModalPlayer = $('#product-video-modal-desktop-player');
                const desktopPlayerElement = $desktopModalPlayer[0];
                
                // Устанавливаем источник видео
                if (desktopPlayerElement) {
                    desktopPlayerElement.src = videoUrl;
                    desktopPlayerElement.load();
                }
                
                // Открываем модальное окно
                $desktopModal.addClass('active');
                $('body').addClass('modal-open');
                
                // Автозапуск видео
                if (desktopPlayerElement) {
                    const playPromise = desktopPlayerElement.play();
                    if (playPromise !== undefined) {
                        playPromise.catch(error => {
                            console.log('Автоплей заблокирован браузером');
                        });
                    }
                }
                
                // Закрытие модального окна
                $desktopModal.find('.product-video-modal-desktop-close, .product-video-modal-desktop-overlay').on('click', function() {
                    if (desktopPlayerElement) {
                        desktopPlayerElement.pause();
                        desktopPlayerElement.currentTime = 0;
                    }
                    $desktopModal.removeClass('active');
                    $('body').removeClass('modal-open');
                });
                
                // Закрытие по ESC
                $(document).on('keydown.product-video-desktop', function(e) {
                    if (e.key === 'Escape' && $desktopModal.hasClass('active')) {
                        if (desktopPlayerElement) {
                            desktopPlayerElement.pause();
                            desktopPlayerElement.currentTime = 0;
                        }
                        $desktopModal.removeClass('active');
                        $('body').removeClass('modal-open');
                        $(document).off('keydown.product-video-desktop');
                    }
                });
            }
            return;
        }
        
        // На мобильных устройствах открываем модальное окно с видео и попапом товара
        const $modal = $('#product-video-modal');
        if ($modal.length === 0) {
            return;
        }
        
        const $btn = $(this);
        const productName = $btn.data('product-name');
        const productPrice = $btn.data('product-price');
        const productDescription = $btn.data('product-description');
        const productId = $btn.data('product-id');
        const productStock = $btn.data('product-stock');
        
        // Заполняем попап информацией о товаре
        $modal.find('.product-video-modal-title').text(productName || '');
        $modal.find('.product-video-modal-price').html(productPrice ? `<span class="price-value">${productPrice}</span> <span class="currency">грн</span>` : '');
        $modal.find('.product-video-modal-description').html(productDescription || '');
        
        // Обновляем кнопку "Добавить в корзину" с правильным количеством
        const quantityInput = $('.product-quantity-input');
        const currentQuantity = quantityInput.length > 0 ? parseInt(quantityInput.val()) || 1 : 1;
        const $cartBtn = $('#product-video-modal-cart-btn');
        $cartBtn.attr('data-product-id', productId);
        $cartBtn.attr('data-product-quantity', currentQuantity);
        
        // Если это YouTube видео, инициализируем слайдер (он сам загрузит iframe через обработчик mounted)
        // Переключение на нужный слайд произойдет после инициализации
        if (youtubeId && youtubeId !== 'null' && youtubeId !== '') {
            // Сохраняем youtubeId для переключения после инициализации
            const $modalSlider = $('#product-video-modal-slider');
            if ($modalSlider.length > 0) {
                $modalSlider[0].targetYoutubeId = youtubeId;
            }
        } else {
            // Для старых видео получаем URL видео
            const originalVideoUrl = $btn.data('src');
            const $mainVideo = $('.product-page-video video.product-video-player');
            const mobileVideo = $mainVideo.data('mobile-video');
            const videoUrl = mobileVideo && mobileVideo !== originalVideoUrl ? mobileVideo : originalVideoUrl;
            const $modalVideo = $('#product-video-modal-player');
            const modalVideoElement = $modalVideo[0];
            
            if (modalVideoElement && videoUrl) {
                // Устанавливаем источник видео
                modalVideoElement.src = videoUrl;
                modalVideoElement.load();
            }
        }
        
        // Показываем/скрываем кнопку добавления в корзину в зависимости от наличия товара
        if (productStock > 0) {
            $cartBtn.show();
        } else {
            $cartBtn.hide();
        }
        
        // Открываем модальное окно
        $modal.addClass('active');
        $('body').addClass('modal-open');
        
        // Если это YouTube видео, инициализируем слайдер при открытии модального окна
        if (youtubeId && youtubeId !== 'null' && youtubeId !== '') {
            // Инициализируем модальный слайдер при открытии модального окна
            initModalSlider();
        }
    });
    
    // Блокируем iframe при наведении на кнопку закрытия
    $(document).on('mouseenter touchstart', '.product-video-modal-close', function(e) {
        // Блокируем все iframe при наведении
        $('#product-video-modal').find('iframe').each(function() {
            this.style.pointerEvents = 'none';
            this.style.zIndex = '1';
        });
        
        // Блокируем все контейнеры iframe
        $('#product-video-modal').find('.product-video-youtube-iframe-container').each(function() {
            $(this).css('pointer-events', 'none');
        });
        
        // Активируем overlay блокировку
        $('#product-video-modal .product-video-modal-video').addClass('blocking-iframe');
    });
    
    // Разблокируем iframe когда убираем курсор с кнопки закрытия
    $(document).on('mouseleave touchend', '.product-video-modal-close', function(e) {
        // Разблокируем iframe только если модальное окно все еще открыто
        if ($('#product-video-modal').hasClass('active')) {
            $('#product-video-modal').find('iframe').each(function() {
                this.style.pointerEvents = 'auto';
            });
            
            $('#product-video-modal').find('.product-video-youtube-iframe-container').each(function() {
                $(this).css('pointer-events', 'auto');
            });
            
            // Убираем overlay блокировку
            $('#product-video-modal .product-video-modal-video').removeClass('blocking-iframe');
        }
    });
    
    // Блокируем iframe при взаимодействии с кнопкой закрытия (на ранней стадии)
    // Используем нативный addEventListener с capture фазой для перехвата событий до iframe
    ['pointerdown', 'mousedown', 'touchstart'].forEach(function(eventType) {
        document.addEventListener(eventType, function(e) {
            // Проверяем, что событие было на кнопке закрытия или её дочерних элементах
            var closeButton = e.target.closest('.product-video-modal-close');
            if (!closeButton) return;
            
            console.log('Close button ' + eventType + ' (capture phase)', e.target);
            
            // КРИТИЧНО: Блокируем все iframe на самой ранней стадии события
            $('#product-video-modal').find('iframe').each(function() {
                this.style.pointerEvents = 'none';
                this.style.zIndex = '1';
            });
            
            // Блокируем все контейнеры iframe
            $('#product-video-modal').find('.product-video-youtube-iframe-container').each(function() {
                $(this).css({
                    'pointer-events': 'none'
                });
            });
            
            // Активируем overlay блокировку
            $('#product-video-modal .product-video-modal-video').addClass('blocking-iframe');
            
            // Убеждаемся, что кнопка закрытия имеет правильный z-index
            var isMobile = window.innerWidth <= 767;
            $(closeButton).css({
                'z-index': '99999',
                'pointer-events': 'auto',
                'position': isMobile ? 'absolute' : 'fixed',
                'top': isMobile ? '60px' : '25px',
                'right': isMobile ? '15px' : '25px'
            });
            
            e.stopPropagation();
            e.stopImmediatePropagation();
            e.preventDefault();
            
            return false;
        }, true); // Используем capture фазу (true)
    });
    
    // Закрытие модального окна - используем нативный addEventListener с capture фазой
    // Это гарантирует, что мы перехватим клик ДО того, как YouTube iframe его получит
    if (!window.productVideoModalCloseHandler) {
        window.productVideoModalCloseHandler = function(e) {
            // Проверяем, что клик был на кнопке закрытия или внутри неё (иконка, текст и т.д.)
            var closeButton = e.target.closest('.product-video-modal-close');
            
            // Если клик был на иконке внутри кнопки
            if (!closeButton && e.target.closest('button')) {
                var button = e.target.closest('button');
                if (button && $(button).hasClass('product-video-modal-close')) {
                    closeButton = button;
                }
            }
            
            if (!closeButton) return;
            
            console.log('Close button clicked (capture phase)', e.target, closeButton);
            
            // КРИТИЧНО: Останавливаем события на capture фазе, ДО того как они дойдут до iframe
            e.stopPropagation();
            e.stopImmediatePropagation();
            e.preventDefault();
            
            // Блокируем все iframe МГНОВЕННО (БЕЗ отправки postMessage)
            var $modal = $('#product-video-modal');
            $modal.find('iframe').each(function() {
                this.style.pointerEvents = 'none';
                this.style.zIndex = '1';
                this.style.opacity = '0.99';
                // НЕ отправляем postMessage здесь - это вызывает ошибки
            });
            
            // Блокируем overlay контейнеры
            $modal.find('.product-video-youtube-iframe-container').each(function() {
                $(this).css({
                    'pointer-events': 'none',
                    'z-index': '1'
                });
            });
            
            // Активируем overlay блокировку через CSS класс
            $modal.find('.product-video-modal-video').addClass('blocking-iframe');
            
            // Закрываем модальное окно
            closeProductVideoModal();
            
            return false;
        };
        
        // Регистрируем обработчик на capture фазе (третий параметр true)
        document.addEventListener('click', window.productVideoModalCloseHandler, true);
    } // Используем capture фазу (true)
    
    // Закрытие по клику на overlay
    $(document).on('click', '.product-video-modal-overlay', function(e) {
        console.log('Overlay clicked');
        e.stopPropagation();
        closeProductVideoModal();
        return false;
    });
    
    // Закрытие по ESC
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $('#product-video-modal').hasClass('active')) {
            closeProductVideoModal();
        }
    });
    
    // Дополнительная проверка: закрываем модальное окно на десктопе при изменении размера окна
    $(window).on('resize', function() {
        const isMobile = window.innerWidth <= 767 || /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        if (!isMobile && $('#product-video-modal').hasClass('active')) {
            closeProductVideoModal();
        }
    });
    
    // Обработчик кнопки "Назад" в модалке
    $(document).on('click', '#product-video-modal-back-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log('Back button clicked');
        closeProductVideoModal();
    });
    
    // Обработчик кнопки добавления в корзину в модалке
    $(document).on('click', '#product-video-modal-cart-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const $btn = $(this);
        const productId = $btn.data('product-id');
        const quantity = $btn.data('product-quantity') || $('#input-quantity-' + productId).val() || 1;
        
        if (parseFloat(quantity) > 0) {
            $.ajax({
                url: '/cart/add',
                method: 'POST',
                data: {
                    product_id: productId,
                    quantity: quantity
                },
                success: function (response) {
                    // Обновляем счетчик корзины
                    if ($('#cart-total').length) {
                        $('#cart-total').html(response.cartItemsCount);
                    }
                    
                    // Обновляем содержимое корзины
                    if ($('.cart-shopping-items').length) {
                        $('.cart-shopping-items').html(response.cartItems);
                    }
                    
                    // Обновляем мини-корзину
                    if ($('.cart-mini-bott .total .value').length && typeof decimalAndIntParts === 'function') {
                        const numberParts = decimalAndIntParts(response.sum);
                        $('.cart-mini-bott .total .value').html(numberParts.integer + '<span class="coins">' + numberParts.decimal + '</span>' + ' ₴');
                    }
                    
                    // Генерируем событие обновления корзины
                    window.dispatchEvent(new CustomEvent('cartUpdated', {
                        detail: {
                            cartItemsCount: response.cartItemsCount,
                            sum: response.sum,
                            cartItems: response.cartItems
                        }
                    }));
                    
                    // Проверяем, мобильное ли это устройство
                    const isMobile = window.innerWidth <= 767 || /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
                    
                    // Закрываем модальное окно с видео
                    closeProductVideoModal();
                    
                    if (isMobile) {
                        // На мобильных открываем модальное окно "Швидка покупка"
                        // Триггерим клик на кнопку быстрой покупки
                        setTimeout(() => {
                            // Устанавливаем product_id в форму быстрой покупки
                            $('#fast-order-product-id').val(productId);
                            
                            // Устанавливаем количество в форму быстрой покупки
                            const quantityValue = quantity;
                            $('#input-quantity').val(quantityValue);
                            
                            // Обновляем цену в форме быстрой покупки (если есть функция пересчета)
                            if (typeof recalcTotalPrice === 'function') {
                                recalcTotalPrice(quantityValue);
                            }
                            
                            // Триггерим клик на кнопку быстрой покупки
                            const $fastOrderBtn = $('.btn-quick-order, [data-popup="fast-order-popup"]');
                            if ($fastOrderBtn.length) {
                                $fastOrderBtn.trigger('click');
                            } else {
                                // Если кнопка не найдена, открываем попап напрямую
                                const fastOrderPopup = document.getElementById('fast-order-popup');
                                if (fastOrderPopup) {
                                    // Закрываем все другие попапы
                                    document.querySelectorAll('.general-popup').forEach(popup => {
                                        popup.classList.remove('active');
                                    });
                                    // Открываем попап быстрой покупки
                                    fastOrderPopup.classList.add('active');
                                }
                            }
                        }, 300);
                    } else {
                        // На десктопе открываем попап корзины
                        if (typeof $.fancybox !== 'undefined') {
                            $.fancybox.open({
                                src: '#cart-popup',
                                type: 'inline'
                            });
                        } else if ($('#cart-popup').length) {
                            $('#cart-popup').addClass('active');
                        }
                    }
                },
                error: function (xhr, status, error) {
                    alert('Ошибка при добавлении в корзину: ' + error);
                }
            });
        } else {
            alert('Пожалуйста, выберите корректное количество товара.');
        }
    });
}

/**
 * Закрытие модального окна с видео
 */
function closeProductVideoModal() {
    // Останавливаем все HTML5 видео
    const videoElement = document.getElementById('product-video-modal-player');
    if (videoElement) {
        videoElement.pause();
        videoElement.currentTime = 0;
    }
    
    // Останавливаем все YouTube iframe
    $('#product-video-modal').find('iframe').each(function() {
        // Блокируем pointer-events перед остановкой
        this.style.pointerEvents = 'none';
        this.style.zIndex = '1';
        
        // Просто очищаем src - это остановит видео без ошибок postMessage
        // Не отправляем postMessage, чтобы избежать ошибок "Could not establish connection"
        if (this.src) {
            this.src = '';
        }
    });
    
    // Останавливаем все видео элементы в слайдере
    $('#product-video-modal').find('video').each(function() {
        this.pause();
        this.currentTime = 0;
    });
    
    // Сбрасываем флаг инициализации слайдера при закрытии модалки
    const $modalSlider = $('#product-video-modal-slider');
    if ($modalSlider.length > 0 && $modalSlider[0]) {
        $modalSlider[0].isInitializing = false;
        // Также очищаем targetYoutubeId
        $modalSlider[0].targetYoutubeId = null;
    }
    
    console.log('Removing active class from modal');
    $('#product-video-modal').removeClass('active');
    $('body').removeClass('modal-open');
    console.log('Modal closed successfully');
}
