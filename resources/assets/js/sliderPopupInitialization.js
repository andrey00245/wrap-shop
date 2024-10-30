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
