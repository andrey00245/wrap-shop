$(document).ready(function () {
  topBannerSlider('topBannersSlider');
  categoriesSlider('slideCategory');
  productSlider('homeBestseller');
  productSlider('homeLatest');
  imageSliderInProduct('home-products-item');
});

function topBannerSlider(id){
  let topBannersSlider = new Swiper("#"+id+" .swiper", {
    navigation: {
      nextEl: "#"+id+" .swiper-button-next",
      prevEl: "#"+id+" .swiper-button-prev",
    },
    pagination: {
      el: "#"+id+" .swiper-pagination",
      clickable: true,
    },
    lazy: true,
    autoplay: {
      delay: 3000,
    },
  });

  let nextButton = document.querySelector('#'+id+' .swiper-button-next');
  let prevButton = document.querySelector('#'+id+' .swiper-button-prev');

  nextButton.addEventListener('click', function() {
    topBannersSlider.autoplay.stop();
  });

  prevButton.addEventListener('click', function() {
    topBannersSlider.autoplay.stop();
  });
}


function categoriesSlider(id) {
  $('.home-category .swiper-slide').css('display', 'flex');
  let categoriesSlider = new Swiper("#"+id+" .swiper", {
    navigation: {
      nextEl: "#"+id+" .swiper-button-next",
      prevEl: "#"+id+" .swiper-button-prev",
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

function productSlider(id){
  // $('#homeBestseller .swiper-slide').css('display', 'flex');
  let homeBestseller = new Swiper("#"+id+" .home-products-list", {
    navigation: {
        nextEl: "#"+id+" .home-slide-button .swiper-button-next",
        prevEl: "#"+id+" .home-slide-button .swiper-button-prev",
    },
    spaceBetween: 10,
    slidesPerView: 1.3,
    // grid: {
    //   rows: 2,
    //   fill: "row",
    // },
    lazy: true,
    observeSlideChildren: true,
    observeParents: true,
    observer: true,
    breakpoints: {
      400: {
        slidesPerView: 2,
        spaceBetween: 10
      },
      480: {
        slidesPerView: 2,
        spaceBetween: 10
      },
      768: {
        slidesPerView: 2.5,
        spaceBetween: 10
      },
      1020: {
        slidesPerView: 3.1,
        spaceBetween: 10,
      },
      1332: {
        slidesPerView: 4.1,
        spaceBetween: 10,
      }
    }
  });
  $('#homeBestseller .home-products-nav .item').on('click', function(){
    var activeItem = $(this).attr('data-cat');
    if (activeItem != 'all') {
      $('#homeBestseller .home-products-nav .item').removeClass('active');
      $('#homeBestseller .home-products-item').removeClass('show').addClass('hide');
      $(this).addClass('active');
      $('#homeBestseller .home-products-item[data-ids="'+activeItem+'"]').removeClass('hide').addClass('show');
      homeBestseller.update();
    } else {
      $('#homeBestseller .home-products-nav .item').removeClass('active');
      $('#homeBestseller .home-products-item').removeClass('hide');
      $(this).addClass('active');
      homeBestseller.update();
    }
  });

  $('#homeLatest .home-products-nav .item').on('click', function(){
    var activeItem = $(this).attr('data-cat');
    if (activeItem != 'all') {
      $('#homeLatest .home-products-nav .item').removeClass('active');
      $('#homeLatest .home-products-item').removeClass('show').addClass('hide');
      $(this).addClass('active');
      $('#homeLatest .home-products-item[data-ids="'+activeItem+'"]').removeClass('hide').addClass('show');
      homeBestseller.update();
    } else {
      $('#homeLatest .home-products-nav .item').removeClass('active');
      $('#homeLatest .home-products-item').removeClass('hide');
      $(this).addClass('active');
      homeBestseller.update();
    }
  });
}

function imageSliderInProduct(item_class){
  let homeProductsItem = new Swiper("."+item_class+" .image", {
    navigation: {
      nextEl: "."+item_class+" .image .swiper-button-next",
      prevEl: "."+item_class+" .image .swiper-button-prev",
    },
    lazy: true,
  });

  let items = $('div.'+item_class);
  items.each(function (index){
    $(this).hover(function (event){
      if(event.type==='mouseenter'){
        homeProductsItem[index].slideNext();
      }
      else if(event.type==='mouseleave'){
        homeProductsItem[index].slideTo(0);
      }
    });
  });
}
