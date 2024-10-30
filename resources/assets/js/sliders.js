import {
  productSliderInitialization,
  imageSliderInProduct} from "./sliderInitialization";

$(document).ready(function () {
  topBannerSlider('topBannersSlider');
  categoriesSlider('slideCategory');
  productSliderInitialization('homeBestseller');
  productSliderInitialization('homeLatest');
  imageSliderInProduct('home-products-item');
});

function topBannerSlider(id) {
  let topBannersSlider = new Swiper("#" + id + " .swiper", {
    navigation: {
      nextEl: "#" + id + " .swiper-button-next",
      prevEl: "#" + id + " .swiper-button-prev",
    },
    pagination: {
      el: "#" + id + " .swiper-pagination",
      clickable: true,
    },
    lazy: true,
    autoplay: {
      delay: 3000,
    },
  });

  let nextButton = document.querySelector('#' + id + ' .swiper-button-next');
  let prevButton = document.querySelector('#' + id + ' .swiper-button-prev');

  nextButton.addEventListener('click', function () {
    topBannersSlider.autoplay.stop();
  });

  prevButton.addEventListener('click', function () {
    topBannersSlider.autoplay.stop();
  });
}


function categoriesSlider(id) {
  $('.home-category .swiper-slide').css('display', 'flex');
  let categoriesSlider = new Swiper("#" + id + " .swiper", {
    navigation: {
      nextEl: "#" + id + " .swiper-button-next",
      prevEl: "#" + id + " .swiper-button-prev",
    },
    watchSlidesProgress: true,
    autoplay: {
      delay: 5000,
    },
    lazy: true,
    speed: 400,
    slidesPerView: 2,
    spaceBetween: 10,
    breakpoints: {
      768: {
        spaceBetween: 5,
        slidesPerView: 3.3
      },
    }
  });
}

