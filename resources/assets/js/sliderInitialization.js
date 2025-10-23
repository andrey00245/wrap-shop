export function productSliderInitialization(id) {
    const $block = $('#' + id);
    const $allItems = $block.find('.home-products-item');
    const totalItems = $allItems.length;

    // Определяем perPage в зависимости от ширины экрана
    let perPage = 4;
    if (window.innerWidth <= 400) {
        perPage = 1;
    } else if (window.innerWidth <= 767) {
        perPage = 2;
    } else if (window.innerWidth <= 1019) {
        perPage = 2;
    } else if (window.innerWidth <= 1331) {
        perPage = 3;
    }

    // Если товаров меньше или равно perPage * 2, используем 'slide' вместо 'loop' для предотвращения дублирования
    const sliderType = totalItems > (perPage * 2) ? 'loop' : 'slide';

    let productsSlider = new Splide('#' + id + ' .splide', {
        pagination: false,
        perPage: perPage,
        gap: '5px',
        padding: {right: '8%'},
        type: sliderType,
        autoplay: totalItems > perPage, // Автопрокрутка только если товаров больше чем видно
        interval: 4000,
        pauseOnHover: true,
        pauseOnFocus: true,
        classes: {
            arrows: 'splide__arrows home-products-slide-buttons',
            prev: 'splide__arrow--prev',
            next: 'splide__arrow--next',
        },
        breakpoints: {
            1331: {
                perPage: 3,
                padding: {right: '3%'},
            },
            1019: {
                perPage: 2,
                padding: {right: '15%'},
            },
            767: {
                perPage: 2,
                padding: {right: 0},
            },
            400: {
                perPage: 1,
                padding: {right: '25%'},
            }
        }
    }).mount();

    // Сохраняем ссылку на слайдер для доступа при фильтрации
    $block[0]._splideInstance = productsSlider;

    // Фильтрация товаров по категории для блока (например, homeBestseller)
    $('#' + id + ' .home-products-nav .item').on('click', function () {
        const activeItem = $(this).attr('data-cat');
        const $block = $('#' + id);
        const $allSlides = $block.find('.home-products-item');

        // Останавливаем автопрокрутку при фильтрации
        if (productsSlider.Components.Autoplay) {
            productsSlider.Components.Autoplay.pause();
        }

        if (activeItem !== 'all') {
            // Сбрасываем активные категории и скрываем все слайды
            $block.find('.home-products-nav .item').removeClass('active');
            $allSlides.removeClass('show').addClass('hide');
            $(this).addClass('active');

            // Показываем только товары выбранной категории
            const $visibleSlides = $block.find('.home-products-item[data-ids="' + activeItem + '"]')
                .removeClass('hide')
                .addClass('show');

            // Останавливаем автопрокрутку при фильтрации
            if (productsSlider.Components.Autoplay) {
                productsSlider.Components.Autoplay.pause();
            }

            // Обновляем тип слайдера в зависимости от количества видимых товаров
            const visibleCount = $visibleSlides.length;
            let currentPerPage = perPage;
            if (window.innerWidth <= 400) {
                currentPerPage = 1;
            } else if (window.innerWidth <= 767) {
                currentPerPage = 2;
            } else if (window.innerWidth <= 1019) {
                currentPerPage = 2;
            } else if (window.innerWidth <= 1331) {
                currentPerPage = 3;
            }

            // Если видимых товаров меньше или равно perPage, меняем тип на 'slide'
            // Используем 'slide' если товаров меньше чем perPage * 2, чтобы избежать дублирования
            const needsSlideType = visibleCount <= (currentPerPage * 2);
            const currentType = productsSlider.options.type;

            // Всегда пересоздаем слайдер при фильтрации для правильной работы
            productsSlider.destroy();

            productsSlider = new Splide('#' + id + ' .splide', {
                pagination: false,
                perPage: currentPerPage,
                gap: '5px',
                padding: {right: '8%'},
                type: needsSlideType ? 'slide' : 'loop',
                rewind: false, // Отключаем перемотку для предотвращения дублирования
                autoplay: false, // Не включаем автопрокрутку после фильтрации
                classes: {
                    arrows: 'splide__arrows home-products-slide-buttons',
                    prev: 'splide__arrow--prev',
                    next: 'splide__arrow--next',
                },
                breakpoints: {
                    1331: { perPage: 3, padding: {right: '3%'} },
                    1019: { perPage: 2, padding: {right: '15%'} },
                    767: { perPage: 2, padding: {right: 0} },
                    400: { perPage: 1, padding: {right: '25%'} }
                }
            }).mount();
            $block[0]._splideInstance = productsSlider;

            // Если для категории есть товары — прокручиваем слайдер к первому видимому товару
            if ($visibleSlides.length) {
                // Ждем, пока слайдер полностью инициализируется
                setTimeout(() => {
                    // Находим первый видимый товар в исходном массиве всех товаров
                    const firstVisibleElement = $visibleSlides.first()[0];
                    const $allItems = $block.find('.home-products-item');
                    let originalIndex = -1;

                    // Находим индекс первого видимого товара в исходном массиве
                    $allItems.each(function(index) {
                        if (this === firstVisibleElement) {
                            originalIndex = index;
                            return false; // break
                        }
                    });

                    // Если нашли индекс, используем его для прокрутки
                    if (originalIndex !== -1 && productsSlider) {
                        // Для типа 'slide' используем реальный индекс
                        if (productsSlider.options.type === 'slide') {
                            productsSlider.go(originalIndex);
                        } else {
                            // Для типа 'loop' ищем через Splide API
                            let targetIndex = -1;

                            if (productsSlider.Components.Slides) {
                                const allSlides = productsSlider.Components.Slides.get();

                                for (let i = 0; i < allSlides.length; i++) {
                                    const slideElement = allSlides[i].slide;
                                    const $slide = $(slideElement);
                                    const $itemInSlide = $slide.find('.home-products-item');

                                    if ($itemInSlide.length &&
                                        $itemInSlide[0] === firstVisibleElement) {
                                        targetIndex = i;
                                        break;
                                    }
                                }
                            }

                            if (targetIndex !== -1 && targetIndex >= 0) {
                                productsSlider.go(targetIndex);
                            } else {
                                productsSlider.go(originalIndex);
                            }
                        }
                    } else {
                        // Fallback: ищем через DOM
                        const $splideList = $block.find('.splide__list');
                        const $allSlides = $splideList.find('.splide__slide');
                        let targetIndex = -1;

                        $allSlides.each(function(index) {
                            const $slide = $(this);
                            const $itemInSlide = $slide.find('.home-products-item');

                            if ($itemInSlide.length &&
                                !$itemInSlide.hasClass('hide') &&
                                $itemInSlide.hasClass('show') &&
                                $itemInSlide.attr('data-ids') === activeItem) {
                                targetIndex = index;
                                return false;
                            }
                        });

                        if (targetIndex !== -1 && targetIndex >= 0) {
                            productsSlider.go(targetIndex);
                        } else {
                            productsSlider.go(0);
                        }
                    }
                }, 200);
            }
        } else {
            // Категория "Все" — показываем все товары
            $block.find('.home-products-nav .item').removeClass('active');
            $allSlides.removeClass('hide').addClass('show');
            $(this).addClass('active');

            // Восстанавливаем тип слайдера для всех товаров
            const totalCount = $allSlides.length;
            let currentPerPage = perPage;
            if (window.innerWidth <= 400) {
                currentPerPage = 1;
            } else if (window.innerWidth <= 767) {
                currentPerPage = 2;
            } else if (window.innerWidth <= 1019) {
                currentPerPage = 2;
            } else if (window.innerWidth <= 1331) {
                currentPerPage = 3;
            }
            const needsSlideType = totalCount <= (currentPerPage * 2);

            // Всегда пересоздаем слайдер при возврате к "Все"
            if (productsSlider) {
                productsSlider.destroy();
            }

            productsSlider = new Splide('#' + id + ' .splide', {
                pagination: false,
                perPage: currentPerPage,
                gap: '5px',
                padding: {right: '8%'},
                type: needsSlideType ? 'slide' : 'loop',
                rewind: false, // Отключаем перемотку для предотвращения дублирования
                autoplay: !needsSlideType, // Включаем автопрокрутку только если товаров больше чем видно
                interval: 4000,
                pauseOnHover: true,
                pauseOnFocus: true,
                classes: {
                    arrows: 'splide__arrows home-products-slide-buttons',
                    prev: 'splide__arrow--prev',
                    next: 'splide__arrow--next',
                },
                breakpoints: {
                    1331: { perPage: 3, padding: {right: '3%'} },
                    1019: { perPage: 2, padding: {right: '15%'} },
                    767: { perPage: 2, padding: {right: 0} },
                    400: { perPage: 1, padding: {right: '25%'} }
                }
            }).mount();
            $block[0]._splideInstance = productsSlider;

            // Возвращаемся к первому слайду
            setTimeout(() => {
                productsSlider.go(0);
            }, 100);
        }
    });
}

export function productSliderInitializationClass(classEl) {

    let elms = document.querySelectorAll('.' + classEl);
    let customBlockSplides = [];
    for (var i = 0; i < elms.length; i++) {
        const blockId = elms[i].id;
        const $block = $(elms[i]);
        const $allItems = $block.find('.home-products-item');
        const totalItems = $allItems.length;

        // Определяем perPage в зависимости от ширины экрана
        let perPage = 4;
        if (window.innerWidth <= 400) {
            perPage = 1;
        } else if (window.innerWidth <= 767) {
            perPage = 2;
        } else if (window.innerWidth <= 1019) {
            perPage = 2;
        } else if (window.innerWidth <= 1331) {
            perPage = 3;
        }

        // Если товаров меньше или равно perPage * 2, используем 'slide' вместо 'loop' для предотвращения дублирования
        const sliderType = totalItems > (perPage * 2) ? 'loop' : 'slide';

        customBlockSplides[i] = new Splide(elms[i].querySelector('.home-products-list'), {
            pagination: false,
            perPage: perPage,
            gap: '5px',
            padding: {right: '8%'},
            type: sliderType,
            rewind: false, // Отключаем перемотку для предотвращения дублирования
            autoplay: false,
            classes: {
                arrows: 'splide__arrows home-products-slide-buttons',
                prev: 'splide__arrow--prev',
                next: 'splide__arrow--next',
            },
            breakpoints: {
                1331: {
                    perPage: 3,
                    padding: {right: '3%'},
                },
                1019: {
                    perPage: 2,
                    padding: {right: '15%'},
                },
                767: {
                    perPage: 2,
                    padding: {right: 0},
                },
                400: {
                    perPage: 1,
                    padding: {right: '25%'},
                }
            }
        }).mount();

        // Сохраняем ссылку на слайдер в элементе блока для доступа при фильтрации
        elms[i]._splideInstance = customBlockSplides[i];
    }

    // Обработчик клика по категории - ограничиваем только конкретным блоком
    $('.' + classEl + ' .home-products-nav .item').on('click', function () {
        const $block = $(this).closest('.' + classEl);
        const blockId = $block.attr('id');
        const activeItem = $(this).attr('data-cat');
        const productsSlider = $block[0]._splideInstance;
        const $allSlides = $block.find('.home-products-item');

        if (activeItem !== 'all') {
            // Сбрасываем активные категории и скрываем все слайды в ЭТОМ блоке
            $block.find('.home-products-nav .item').removeClass('active');
            $allSlides.removeClass('show').addClass('hide');
            $(this).addClass('active');

            // Показываем только товары выбранной категории в ЭТОМ блоке
            const $visibleSlides = $block.find('.home-products-item[data-ids="' + activeItem + '"]')
                .removeClass('hide')
                .addClass('show');

            // Останавливаем автопрокрутку при фильтрации
            if (productsSlider && productsSlider.Components.Autoplay) {
                productsSlider.Components.Autoplay.pause();
            }

            // Обновляем тип слайдера в зависимости от количества видимых товаров
            const visibleCount = $visibleSlides.length;
            let currentPerPage = 4;
            if (window.innerWidth <= 400) {
                currentPerPage = 1;
            } else if (window.innerWidth <= 767) {
                currentPerPage = 2;
            } else if (window.innerWidth <= 1019) {
                currentPerPage = 2;
            } else if (window.innerWidth <= 1331) {
                currentPerPage = 3;
            }

            // Используем 'slide' если товаров меньше чем perPage * 2, чтобы избежать дублирования
            const needsSlideType = visibleCount <= (currentPerPage * 2);

            // Всегда пересоздаем слайдер при фильтрации для правильной работы
            if (productsSlider) {
                productsSlider.destroy();
            }

            productsSlider = new Splide($block.find('.home-products-list')[0], {
                pagination: false,
                perPage: currentPerPage,
                gap: '5px',
                padding: {right: '8%'},
                type: needsSlideType ? 'slide' : 'loop',
                rewind: false, // Отключаем перемотку для предотвращения дублирования
                autoplay: false, // Не включаем автопрокрутку после фильтрации
                classes: {
                    arrows: 'splide__arrows home-products-slide-buttons',
                    prev: 'splide__arrow--prev',
                    next: 'splide__arrow--next',
                },
                breakpoints: {
                    1331: { perPage: 3, padding: {right: '3%'} },
                    1019: { perPage: 2, padding: {right: '15%'} },
                    767: { perPage: 2, padding: {right: 0} },
                    400: { perPage: 1, padding: {right: '25%'} }
                }
            }).mount();
            $block[0]._splideInstance = productsSlider;

            // Прокручиваем слайдер к первому видимому товару
            if ($visibleSlides.length) {
                // Ждем, пока слайдер полностью инициализируется
                setTimeout(() => {
                    // Находим первый видимый товар в исходном массиве всех товаров
                    const firstVisibleElement = $visibleSlides.first()[0];
                    const $allItems = $block.find('.home-products-item');
                    let originalIndex = -1;

                    // Находим индекс первого видимого товара в исходном массиве
                    $allItems.each(function(index) {
                        if (this === firstVisibleElement) {
                            originalIndex = index;
                            return false; // break
                        }
                    });

                    // Если нашли индекс, используем его для прокрутки
                    if (originalIndex !== -1 && productsSlider) {
                        // Для типа 'slide' используем реальный индекс
                        if (productsSlider.options.type === 'slide') {
                            productsSlider.go(originalIndex);
                        } else {
                            // Для типа 'loop' ищем через Splide API
                            let targetIndex = -1;

                            if (productsSlider.Components.Slides) {
                                const allSlides = productsSlider.Components.Slides.get();

                                for (let i = 0; i < allSlides.length; i++) {
                                    const slideElement = allSlides[i].slide;
                                    const $slide = $(slideElement);
                                    const $itemInSlide = $slide.find('.home-products-item');

                                    if ($itemInSlide.length &&
                                        $itemInSlide[0] === firstVisibleElement) {
                                        targetIndex = i;
                                        break;
                                    }
                                }
                            }

                            if (targetIndex !== -1 && targetIndex >= 0) {
                                productsSlider.go(targetIndex);
                            } else {
                                productsSlider.go(originalIndex);
                            }
                        }
                    } else {
                        // Fallback: ищем через DOM
                        const $splideList = $block.find('.splide__list');
                        const $allSlides = $splideList.find('.splide__slide');
                        let targetIndex = -1;

                        $allSlides.each(function(index) {
                            const $slide = $(this);
                            const $itemInSlide = $slide.find('.home-products-item');

                            if ($itemInSlide.length &&
                                !$itemInSlide.hasClass('hide') &&
                                $itemInSlide.hasClass('show') &&
                                $itemInSlide.attr('data-ids') === activeItem) {
                                targetIndex = index;
                                return false;
                            }
                        });

                        if (targetIndex !== -1 && targetIndex >= 0) {
                            productsSlider.go(targetIndex);
                        } else {
                            productsSlider.go(0);
                        }
                    }
                }, 200);
            }
        } else {
            // Категория "Все" — показываем все товары в ЭТОМ блоке
            $block.find('.home-products-nav .item').removeClass('active');
            $allSlides.removeClass('hide').addClass('show');
            $(this).addClass('active');

            // Восстанавливаем тип слайдера для всех товаров
            const totalCount = $allSlides.length;
            let currentPerPage = 4;
            if (window.innerWidth <= 400) {
                currentPerPage = 1;
            } else if (window.innerWidth <= 767) {
                currentPerPage = 2;
            } else if (window.innerWidth <= 1019) {
                currentPerPage = 2;
            } else if (window.innerWidth <= 1331) {
                currentPerPage = 3;
            }
            const needsSlideType = totalCount <= (currentPerPage * 2);

            // Всегда пересоздаем слайдер при возврате к "Все"
            if (productsSlider) {
                productsSlider.destroy();
            }

            productsSlider = new Splide($block.find('.home-products-list')[0], {
                pagination: false,
                perPage: currentPerPage,
                gap: '5px',
                padding: {right: '8%'},
                type: needsSlideType ? 'slide' : 'loop',
                rewind: false, // Отключаем перемотку для предотвращения дублирования
                autoplay: false,
                classes: {
                    arrows: 'splide__arrows home-products-slide-buttons',
                    prev: 'splide__arrow--prev',
                    next: 'splide__arrow--next',
                },
                breakpoints: {
                    1331: { perPage: 3, padding: {right: '3%'} },
                    1019: { perPage: 2, padding: {right: '15%'} },
                    767: { perPage: 2, padding: {right: 0} },
                    400: { perPage: 1, padding: {right: '25%'} }
                }
            }).mount();
            $block[0]._splideInstance = productsSlider;

            // Возвращаемся к первому слайду
            setTimeout(() => {
                productsSlider.go(0);
            }, 100);
        }
    });
}

export function customBlockSliderInitialization(id) {
    const $block = $('#' + id);
    const $allItems = $block.find('.home-products-item');
    const totalItems = $allItems.length;

    // Определяем perPage в зависимости от ширины экрана
    let perPage = 4;
    if (window.innerWidth <= 400) {
        perPage = 1;
    } else if (window.innerWidth <= 767) {
        perPage = 2;
    } else if (window.innerWidth <= 1019) {
        perPage = 2;
    } else if (window.innerWidth <= 1331) {
        perPage = 3;
    }

    // Если товаров меньше или равно perPage * 2, используем 'slide' вместо 'loop' для предотвращения дублирования
    const sliderType = totalItems > (perPage * 2) ? 'loop' : 'slide';

    let productsSlider = new Splide('#' + id + ' .splide', {
        pagination: false,
        perPage: perPage,
        gap: '5px',
        padding: {right: '8%'},
        type: sliderType,
        rewind: false, // Отключаем перемотку для предотвращения дублирования
        autoplay: false,
        classes: {
            arrows: 'splide__arrows home-products-slide-buttons',
            prev: 'splide__arrow--prev',
            next: 'splide__arrow--next',
        },
        breakpoints: {
            1331: {
                perPage: 3,
                padding: {right: '3%'},
            },
            1019: {
                perPage: 2,
                padding: {right: '15%'},
            },
            767: {
                perPage: 2,
                padding: {right: 0},
            },
            400: {
                perPage: 1,
                padding: {right: '25%'},
            }
        }
    }).mount();

    $('#' + id + ' .home-products-nav .item').on('click', function () {
        const activeItem = $(this).attr('data-cat');
        const $allSlides = $block.find('.home-products-item');

        // Останавливаем автопрокрутку при фильтрации
        if (productsSlider.Components.Autoplay) {
            productsSlider.Components.Autoplay.pause();
        }

        if (activeItem !== 'all') {
            $block.find('.home-products-nav .item').removeClass('active');
            $allSlides.removeClass('show').addClass('hide');
            $(this).addClass('active');

            // Показываем только товары выбранной категории
            const $visibleSlides = $block.find('.home-products-item[data-ids="' + activeItem + '"]')
                .removeClass('hide')
                .addClass('show');

            // Обновляем тип слайдера в зависимости от количества видимых товаров
            const visibleCount = $visibleSlides.length;
            let currentPerPage = perPage;
            if (window.innerWidth <= 400) {
                currentPerPage = 1;
            } else if (window.innerWidth <= 767) {
                currentPerPage = 2;
            } else if (window.innerWidth <= 1019) {
                currentPerPage = 2;
            } else if (window.innerWidth <= 1331) {
                currentPerPage = 3;
            }

            const needsSlideType = visibleCount <= currentPerPage;

            // Всегда пересоздаем слайдер при фильтрации для правильной работы
            if (productsSlider) {
                productsSlider.destroy();
            }

            productsSlider = new Splide('#' + id + ' .splide', {
                pagination: false,
                perPage: currentPerPage,
                gap: '5px',
                padding: {right: '8%'},
                type: needsSlideType ? 'slide' : 'loop',
                autoplay: false,
                classes: {
                    arrows: 'splide__arrows home-products-slide-buttons',
                    prev: 'splide__arrow--prev',
                    next: 'splide__arrow--next',
                },
                breakpoints: {
                    1331: { perPage: 3, padding: {right: '3%'} },
                    1019: { perPage: 2, padding: {right: '15%'} },
                    767: { perPage: 2, padding: {right: 0} },
                    400: { perPage: 1, padding: {right: '25%'} }
                }
            }).mount();

            // Прокручиваем слайдер к первому видимому товару
            if ($visibleSlides.length) {
                // Ждем, пока слайдер полностью инициализируется
                setTimeout(() => {
                    // Находим первый видимый товар в исходном массиве всех товаров
                    const firstVisibleElement = $visibleSlides.first()[0];
                    const $allItems = $block.find('.home-products-item');
                    let originalIndex = -1;

                    // Находим индекс первого видимого товара в исходном массиве
                    $allItems.each(function(index) {
                        if (this === firstVisibleElement) {
                            originalIndex = index;
                            return false; // break
                        }
                    });

                    // Если нашли индекс, используем его для прокрутки
                    if (originalIndex !== -1 && productsSlider) {
                        // Для типа 'slide' используем реальный индекс
                        if (productsSlider.options.type === 'slide') {
                            productsSlider.go(originalIndex);
                        } else {
                            // Для типа 'loop' ищем через Splide API
                            let targetIndex = -1;

                            if (productsSlider.Components.Slides) {
                                const allSlides = productsSlider.Components.Slides.get();

                                for (let i = 0; i < allSlides.length; i++) {
                                    const slideElement = allSlides[i].slide;
                                    const $slide = $(slideElement);
                                    const $itemInSlide = $slide.find('.home-products-item');

                                    if ($itemInSlide.length &&
                                        $itemInSlide[0] === firstVisibleElement) {
                                        targetIndex = i;
                                        break;
                                    }
                                }
                            }

                            if (targetIndex !== -1 && targetIndex >= 0) {
                                productsSlider.go(targetIndex);
                            } else {
                                productsSlider.go(originalIndex);
                            }
                        }
                    } else {
                        // Fallback: ищем через DOM
                        const $splideList = $block.find('.splide__list');
                        const $allSlides = $splideList.find('.splide__slide');
                        let targetIndex = -1;

                        $allSlides.each(function(index) {
                            const $slide = $(this);
                            const $itemInSlide = $slide.find('.home-products-item');

                            if ($itemInSlide.length &&
                                !$itemInSlide.hasClass('hide') &&
                                $itemInSlide.hasClass('show') &&
                                $itemInSlide.attr('data-ids') === activeItem) {
                                targetIndex = index;
                                return false;
                            }
                        });

                        if (targetIndex !== -1 && targetIndex >= 0) {
                            productsSlider.go(targetIndex);
                        } else {
                            productsSlider.go(0);
                        }
                    }
                }, 200);
            }
        } else {
            $block.find('.home-products-nav .item').removeClass('active');
            $allSlides.removeClass('hide').addClass('show');
            $(this).addClass('active');

            // Восстанавливаем тип слайдера для всех товаров
            const totalCount = $allSlides.length;
            let currentPerPage = perPage;
            if (window.innerWidth <= 400) {
                currentPerPage = 1;
            } else if (window.innerWidth <= 767) {
                currentPerPage = 2;
            } else if (window.innerWidth <= 1019) {
                currentPerPage = 2;
            } else if (window.innerWidth <= 1331) {
                currentPerPage = 3;
            }
            const needsSlideType = totalCount <= (currentPerPage * 2);

            // Всегда пересоздаем слайдер при возврате к "Все"
            if (productsSlider) {
                productsSlider.destroy();
            }

            productsSlider = new Splide('#' + id + ' .splide', {
                pagination: false,
                perPage: currentPerPage,
                gap: '5px',
                padding: {right: '8%'},
                type: needsSlideType ? 'slide' : 'loop',
                rewind: false, // Отключаем перемотку для предотвращения дублирования
                autoplay: false,
                classes: {
                    arrows: 'splide__arrows home-products-slide-buttons',
                    prev: 'splide__arrow--prev',
                    next: 'splide__arrow--next',
                },
                breakpoints: {
                    1331: { perPage: 3, padding: {right: '3%'} },
                    1019: { perPage: 2, padding: {right: '15%'} },
                    767: { perPage: 2, padding: {right: 0} },
                    400: { perPage: 1, padding: {right: '25%'} }
                }
            }).mount();

            // Возвращаемся к первому слайду
            setTimeout(() => {
                productsSlider.go(0);
            }, 100);
        }
    });
}

export function customBlockSliderInitialization(id) {
    let productsSlider = new Splide('#' + id + ' .splide', {
        pagination: false,
        perPage: 4,
        gap: '5px',
        padding: {right: '8%'},
        type: 'loop',
        autoplay: false,
        classes: {
            arrows: 'splide__arrows home-products-slide-buttons',
            prev: 'splide__arrow--prev',
            next: 'splide__arrow--next',
        },
        breakpoints: {
            1331: {
                perPage: 3,
                padding: {right: '3%'},
            },
            1019: {
                perPage: 2,
                padding: {right: '15%'},
            },
            767: {
                perPage: 2,
                padding: {right: 0},
            },
            400: {
                perPage: 1,
                padding: {right: '25%'},
            }
        }
    }).mount();

    $('#' + id + ' .home-products-nav .item').on('click', function () {
        var activeItem = $(this).attr('data-cat');
        if (activeItem != 'all') {
            $('#' + id + ' .home-products-nav .item').removeClass('active');
            $('#' + id + ' .home-products-item').removeClass('show').addClass('hide');
            $(this).addClass('active');
            $('#' + id + ' .home-products-item[data-ids="' + activeItem + '"]').removeClass('hide').addClass('show');
        } else {
            $('#' + id + ' .home-products-nav .item').removeClass('active');
            $('#' + id + ' .home-products-item').removeClass('hide');
            $(this).addClass('active');
        }
    });
}


export function popupSliderInitialization(id) {
    let productsSlider = new Splide('#' + id + ' .splide', {
        pagination: false,
        perPage: 2,
        gap: '5px',
        padding: {right: '8%'},
        classes: {
            arrows: 'splide__arrows home-products-slide-buttons">',
            prev: 'splide__arrow--prev',
            next: 'splide__arrow--next',
        },
        breakpoints: {
            1331: {
                perPage: 3,
                padding: {right: '3%'},
            },
            1019: {
                perPage: 2,
                padding: {right: '5%'},
            },
            767: {
                perPage: 1,
                padding: {right: '45%'},
            },
            400: {
                perPage: 1,
                padding: {right: '25%'},
            }
        }
    }).mount();

    $('#' + id + ' .home-products-nav .item').on('click', function () {
        var activeItem = $(this).attr('data-cat');
        if (activeItem != 'all') {
            $('#' + id + ' .home-products-nav .item').removeClass('active');
            $('#' + id + ' .home-products-item').removeClass('show').addClass('hide');
            $(this).addClass('active');
            $('#' + id + ' .home-products-item[data-ids="' + activeItem + '"]').removeClass('hide').addClass('show');
        } else {
            $('#' + id + ' .home-products-nav .item').removeClass('active');
            $('#' + id + ' .home-products-item').removeClass('hide');
            $(this).addClass('active');
        }
    });
}

export function imageSliderInProduct(item_class) {
    let elms = document.querySelectorAll('.' + item_class + ':not(.banner) .products-images');
    let hoverItems = document.querySelectorAll('.' + item_class + ':not(.banner)');
    let image_splides = []

    for (var i = 0; i < elms.length; i++) {
        image_splides[i] = new Splide(elms[i], {
            pagination: false,
            perPage: 1,
            arrows:false
        }).mount();
    }

    hoverItems.forEach(function (el, i) {
        el.addEventListener('mouseenter', function () {
            image_splides[i].go(1);
        })
        el.addEventListener('mouseleave', function () {
            image_splides[i].go(0);
        })
    })
}
