import {
    productSliderInitialization,
    productSliderInitializationClass,
    popupSliderInitialization,
    imageSliderInProduct
} from "./sliderInitialization";

$(document).ready(function () {
    productSliderInitialization('homeBestseller');
    productSliderInitialization('homeLatest');
    productSliderInitializationClass('customBlocks');
});

new Splide('#topBannersSlider', {
    autoplay: true,
    interval: 3000,
    classes: {
        arrows: 'splide__arrows home-slide-buttons',
        prev  : 'splide__arrow--prev home-banner-prev',
        next  : 'splide__arrow--next home-banner-next',
    },
}).mount();


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
