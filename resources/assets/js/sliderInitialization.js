export function productSliderInitialization(id){
  let homeBestseller = new Swiper("#"+id+" .home-products-list", {
    navigation: {
      nextEl: "#"+id+" .home-slide-button .swiper-button-next",
      prevEl: "#"+id+" .home-slide-button .swiper-button-prev",
    },
    spaceBetween: 10,
    slidesPerView: 1.3,
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




export function popupSliderInitialization(id){
  let cartSlider = new Swiper("#"+id+" .home-products-list", {
    navigation: {
      nextEl: "#"+id+" .home-slide-button .swiper-button-next",
      prevEl: "#"+id+" .home-slide-button .swiper-button-prev",
    },
    spaceBetween: 10,
    slidesPerView: 3,
    lazy: true,
    observeSlideChildren: true,
    observeParents: true,
    observer: true,
    breakpoints: {
      400: {
        slidesPerView: 3,
        spaceBetween: 10
      },
      480: {
        slidesPerView: 3,
        spaceBetween: 10
      },
      768: {
        slidesPerView: 3,
        spaceBetween: 10
      },
      1020: {
        slidesPerView: 2,
        spaceBetween: 10,
      }
    }
  });
  $('#'+id+' .home-products-nav .item').on('click', function(){
    var activeItem = $(this).attr('data-cat');
    if (activeItem != 'all') {
      $('#'+id+' .home-products-nav .item').removeClass('active');
      $('#'+id+' .home-products-item').removeClass('show').addClass('hide');
      $(this).addClass('active');
      $('#'+id+' .home-products-item[data-ids="'+activeItem+'"]').removeClass('hide').addClass('show');
      cartSlider.update();
    } else {
      $('#'+id+' .home-products-nav .item').removeClass('active');
      $('#'+id+' .home-products-item').removeClass('hide');
      $(this).addClass('active');
      cartSlider.update();
    }
  });

  $('#homeLatest .home-products-nav .item').on('click', function(){
    var activeItem = $(this).attr('data-cat');
    if (activeItem != 'all') {
      $('#'+id+' .home-products-nav .item').removeClass('active');
      $('#'+id+' .home-products-item').removeClass('show').addClass('hide');
      $(this).addClass('active');
      $('#homeLatest .home-products-item[data-ids="'+activeItem+'"]').removeClass('hide').addClass('show');
      cartSlider.update();
    } else {
      $('#'+id+' .home-products-nav .item').removeClass('active');
      $('#'+id+' .home-products-item').removeClass('hide');
      $(this).addClass('active');
      cartSlider.update();
    }
  });
}


export function imageSliderInProduct(item_class){
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
