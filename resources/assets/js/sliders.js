import {
    productSliderInitialization,
    productSliderInitializationClass,
    customBlockSliderInitialization,
    popupSliderInitialization,
    imageSliderInProduct
} from "./sliderInitialization";
import { initHomeProductsSliderProgress, bindHomeProductsSplideProgress } from "./homeMobileSplideProgress";

$(document).ready(function () {
    productSliderInitialization('homeBestseller');
    productSliderInitialization('homeLatest');
    productSliderInitializationClass('customBlocks');

    // Инициализация каждого кастомного блока отдельно
    $('.customBlocks').each(function() {
        const blockId = $(this).attr('id');
        if (blockId) {
            customBlockSliderInitialization(blockId);
        }
    });

    initHomeProductsSliderProgress();
    window.addEventListener('resize', initHomeProductsSliderProgress);

    $('.js-home-block-categories-splide').each(function () {
        const el = this;
        if (el.querySelectorAll('.splide__slide').length < 2) {
            return;
        }

        new Splide(el, {
            type: 'slide',
            rewind: true,
            perPage: 1,
            gap: 0,
            arrows: true,
            pagination: false,
            speed: 500,
        }).mount();
    });

    const colorFilmsEl = document.querySelector('#homeColorFilms .home-color-films__list');
    if (colorFilmsEl && colorFilmsEl.querySelectorAll('.splide__slide').length > 1) {
        new Splide(colorFilmsEl, {
            pagination: false,
            perPage: 5,
            gap: '16px',
            type: 'slide',
            rewind: false,
            classes: {
                arrows: 'splide__arrows home-products-slide-buttons',
                prev: 'splide__arrow--prev',
                next: 'splide__arrow--next',
            },
            breakpoints: {
                1331: { perPage: 4, gap: '12px' },
                1019: { perPage: 3, gap: '10px' },
                767: {
                    perPage: 1,
                    gap: '0px',
                    padding: { left: 0, right: 0 },
                },
            }
        }).mount();
    }

    let homeReviewsDesktopSplide = null;
    let homeReviewsMobileSplide = null;

    const homeReviewsDesktop = document.querySelector('#homeReviews .splide.home-reviews__list--desktop');
    if (homeReviewsDesktop && homeReviewsDesktop.querySelectorAll('.splide__slide').length > 1) {
        homeReviewsDesktopSplide = new Splide(homeReviewsDesktop, {
            pagination: false,
            perPage: 1,
            gap: '0px',
            type: 'slide',
            rewind: false,
            classes: {
                arrows: 'splide__arrows home-products-slide-buttons',
                prev: 'splide__arrow--prev',
                next: 'splide__arrow--next',
            },
        }).mount();
    }

    const homeReviewsMobile = document.querySelector('#homeReviews .splide.home-reviews__list--mobile');
    if (homeReviewsMobile && homeReviewsMobile.querySelectorAll('.splide__slide').length > 1) {
        homeReviewsMobileSplide = new Splide(homeReviewsMobile, {
            pagination: false,
            perPage: 1,
            gap: '0px',
            type: 'slide',
            rewind: false,
            classes: {
                arrows: 'splide__arrows home-products-slide-buttons',
                prev: 'splide__arrow--prev',
                next: 'splide__arrow--next',
            },
        }).mount();
    }

    if (homeReviewsDesktopSplide || homeReviewsMobileSplide) {
        const refreshHomeReviewsSplides = () => {
            homeReviewsDesktopSplide?.refresh?.();
            homeReviewsMobileSplide?.refresh?.();
        };

        setTimeout(refreshHomeReviewsSplides, 0);

        let homeReviewsSplideResizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(homeReviewsSplideResizeTimer);
            homeReviewsSplideResizeTimer = setTimeout(refreshHomeReviewsSplides, 200);
        });
    }

    document.querySelectorAll('.js-home-block-kits-splide').forEach((el) => {
        if (el.querySelectorAll('.splide__slide').length < 2) {
            return;
        }

        new Splide(el, {
            pagination: false,
            fixedWidth: '308.75px',
            gap: '20px',
            perMove: 1,
            omitEnd: true,
            type: 'slide',
            rewind: false,
            classes: {
                arrows: 'splide__arrows home-products-slide-buttons',
                prev: 'splide__arrow--prev',
                next: 'splide__arrow--next',
            },
            breakpoints: {
                1019: { fixedWidth: '280px', gap: '16px' },
                767: { fixedWidth: '260px', gap: '12px' },
            },
        }).mount();
    });

    document.querySelectorAll('.js-home-block-products-splide').forEach((el) => {
        if (el.querySelectorAll('.splide__slide').length < 2) {
            return;
        }

        new Splide(el, {
            pagination: false,
            perPage: 4,
            fixedWidth: '326px',
            gap: '2px',
            type: 'slide',
            rewind: false,
            classes: {
                arrows: 'splide__arrows home-products-slide-buttons',
                prev: 'splide__arrow--prev',
                next: 'splide__arrow--next',
            },
            breakpoints: {
                1331: { fixedWidth: '300px', gap: '10px' },
                1019: { fixedWidth: '280px', gap: '10px' },
                767: { fixedWidth: '240px', gap: '10px' },
                520: {
                    fixedWidth: false,
                    perPage: 2,
                    gap: '10px',
                },
            }
        }).mount();
    });

    const homeNewsEl = document.querySelector('#homeNews .home-news__list');
    if (homeNewsEl && homeNewsEl.querySelectorAll('.splide__slide').length > 1) {
        new Splide(homeNewsEl, {
            pagination: false,
            perPage: 4,
            gap: '20px',
            type: 'slide',
            rewind: false,
            classes: {
                arrows: 'splide__arrows home-products-slide-buttons',
                prev: 'splide__arrow--prev',
                next: 'splide__arrow--next',
            },
            breakpoints: {
                1331: { perPage: 3, gap: '14px' },
                1019: { perPage: 2, gap: '12px' },
                520: { perPage: 1, gap: '10px' },
            }
        }).mount();
    }

    document.querySelectorAll('.js-home-block-banner-splide').forEach((el) => {
        if (el.classList.contains('is-initialized') || el.splide) {
            return;
        }

        if (el.querySelectorAll('.splide__slide').length < 2) {
            return;
        }

        const bannerSplide = new Splide(el, {
            type: 'slide',
            rewind: true,
            perPage: 1,
            gap: '7px',
            speed: 650,
            easing: 'ease-in-out',
            pagination: true,
            arrows: true,
            classes: {
                arrows: 'splide__arrows home-products-slide-buttons',
                prev: 'splide__arrow--prev',
                next: 'splide__arrow--next',
                pagination: 'splide__pagination home-block-banner__pagination',
                page: 'splide__pagination__page',
            },
            breakpoints: {
                767: {
                    arrows: false,
                },
            },
        });

        bannerSplide.mount();

        const pages = el.querySelectorAll('.home-block-banner__pagination .splide__pagination__page');
        if (pages.length > bannerSplide.length) {
            pages.forEach((pageEl, idx) => {
                if (idx >= bannerSplide.length) {
                    pageEl.parentElement?.remove();
                }
            });
        }
    });

    document.querySelectorAll('.js-home-youtube-cards-splide').forEach((el) => {
        if (el.classList.contains('is-initialized') || el.splide) {
            return;
        }

        if (el.querySelectorAll('.splide__slide').length < 2) {
            return;
        }

        const youtubeSplide = new Splide(el, {
            type: 'slide',
            rewind: false,
            perPage: 2,
            gap: '20px',
            speed: 500,
            pagination: false,
            arrows: true,
            classes: {
                arrows: 'splide__arrows home-products-slide-buttons',
                prev: 'splide__arrow--prev',
                next: 'splide__arrow--next',
            },
            breakpoints: {
                1019: { perPage: 1, gap: '14px' },
                767: {
                    perPage: 1,
                    gap: '12px',
                    padding: { left: 0, right: 0 },
                    type: 'slide',
                    rewind: false,
                },
            },
        }).mount();

        bindHomeProductsSplideProgress(el, youtubeSplide);
    });
});

const topBannersEl = document.querySelector('#topBannersSlider');

if (topBannersEl) {
    const topBannersSplide = new Splide(topBannersEl, {
        autoplay: true,
        interval: 3000,
        type: 'loop',
        speed: 600,
        pauseOnHover: true,
        pauseOnFocus: true,
        arrows: false,
        pagination: true,
    });

    topBannersSplide.mount();

    // При любом взаимодействии пользователя — останавливаем автопрокрутку,
    // чтобы можно было спокойно читать баннер.
    const stopAutoplay = () => {
        const autoplay = topBannersSplide?.Components?.Autoplay;
        if (autoplay && typeof autoplay.stop === 'function') {
            autoplay.stop();
        }
    };

    // hover / touch / drag / scroll-wheel
    topBannersEl.addEventListener('mouseenter', stopAutoplay, { passive: true });
    topBannersEl.addEventListener('touchstart', stopAutoplay, { passive: true });
    topBannersEl.addEventListener('pointerdown', stopAutoplay, { passive: true });
    topBannersEl.addEventListener('wheel', stopAutoplay, { passive: true });
    topBannersSplide.on('drag', stopAutoplay);
    topBannersSplide.on('move', stopAutoplay);

    // Защита от дублирования точек пагинации (если слайдер по каким‑то причинам инициализируется дважды)
    (function fixTopBannersPagination() {
        const realCount = topBannersSplide.length;
        const $dots = $('#topBannersSlider .splide__pagination li');

        if ($dots.length > realCount) {
            $dots.slice(realCount).remove();
        }
    })();
}
