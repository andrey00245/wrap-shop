import {
    productSliderInitialization,
    productSliderInitializationClass,
    customBlockSliderInitialization,
    popupSliderInitialization,
    imageSliderInProduct
} from "./sliderInitialization";

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
});

const topBannersSplide = new Splide('#topBannersSlider', {
    autoplay: true,
    interval: 3000,
    type: 'loop',
    speed: 600,
    pauseOnHover: false,
    pauseOnFocus: false,
    classes: {
        arrows: 'splide__arrows home-slide-buttons',
        prev  : 'splide__arrow--prev home-banner-prev',
        next  : 'splide__arrow--next home-banner-next',
    },
});

topBannersSplide.mount();

// Защита от дублирования точек пагинации (если слайдер по каким‑то причинам инициализируется дважды)
(function fixTopBannersPagination() {
    const realCount = topBannersSplide.length; // реальное количество слайдов
    const $dots = $('#topBannersSlider .splide__pagination li');

    if ($dots.length > realCount) {
        $dots.slice(realCount).remove();
    }
})();


new Splide('#slideCategory', {
    autoplay: true,
    interval: 5000,
    type: 'loop',
    pagination: false,
    classes: {
        arrows: 'splide__arrows home-category-buttons',
        prev  : 'splide__arrow--prev home-banner-prev',
        next  : 'splide__arrow--next home-banner-next',
    },
    perPage: 3,
    gap: '5px',
    padding: {right: '8%'},
    breakpoints: {
        768: {
            perPage: 2,
            padding: {right: 0},

        },
    }
}).mount();
