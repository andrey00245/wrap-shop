import './wishlist'
import './subscription'


$(document).ready(function () {
  cartPopupSlider();
  showHideSubCategories();

  let scroll = $(window).scrollTop();
  slideHeader(scroll);

  $(window).scroll(function (event) {
    scroll = $(window).scrollTop();
    slideHeader(scroll);
  });

  function slideHeader(scroll) {
    if (scroll > 100) {
      $('.fixed-header').addClass('show');
      $('.popup-right').addClass('fixed-header-active');
      $('.head-top').addClass('fixed-header-active');

    } else {
      $('.fixed-header').removeClass('show');
      $('.popup-right').removeClass('fixed-header-active');
      $('.head-top').removeClass('fixed-header-active');

    }
  }

  $(".head-catalog-open, .head-catalog-bg").on("click", function () {
    $('.head-catalog').slideToggle();
    $(".head-catalog-open, .head-catalog-bg").toggleClass("active");
  });
  $(".mobil-catalog-open, .head-catalog-close").on("click", function () {
    $('.head-catalog').toggleClass("active");
  });

  $('.head-top-close, .head-top-open').on('click', function(){
    menuPopup()
  });

  function cartPopup(e) {
    e.preventDefault()
    $('#cart-popup').toggleClass('active');
    $('#simple-popup').removeClass('active');
    $('#search-popup').removeClass('active');
    $('#login-popup').removeClass('active');
    $('#fast-order-popup').removeClass('active');
    $('.head-top').removeClass('active');
    if($('#cart-popup').hasClass('active')){
      $("html").addClass('no-overflow');
    }
    else {
      $("html").removeClass('no-overflow');
    }
  }

  function searchPopup() {
    $('#search-popup').toggleClass('active');
    $('#simple-popup').removeClass('active');
    $('#cart-popup').removeClass('active');
    $('#login-popup').removeClass('active');
    $('.head-top').removeClass('active');
  }

  function loginPopup(){
    $('#login-popup').toggleClass('active');
    $('#search-popup').removeClass('active');
    $('#simple-popup').removeClass('active');
    $('#cart-popup').removeClass('active');
    $('.head-top').removeClass('active');
  }

    function consultPopup(){
        $('#consult-popup').toggleClass('active');
        $('#search-popup').removeClass('active');
        $('#simple-popup').removeClass('active');
        $('#cart-popup').removeClass('active');
        $('#fast-order-popup').removeClass('active');
        $('.head-top').removeClass('active');
    }

    function fastOrderPopup(){
        $('#fast-order-popup').toggleClass('active');
        $('#consult-popup').removeClass('active');
        $('#search-popup').removeClass('active');
        $('#simple-popup').removeClass('active');
        $('#cart-popup').removeClass('active');
        $('.head-top').removeClass('active');
    }

  function menuPopup(){
    $('.head-top').toggleClass('active');
    $('#login-popup').removeClass('active');
    $('#search-popup').removeClass('active');
    $('#simple-popup').removeClass('active');
    $('#cart-popup').removeClass('active');
      $('#fast-order-popup').removeClass('active');

      if($('.head-top').hasClass('active')){
      $("html").addClass('no-overflow');
    }
    else{
      $("html").removeClass('no-overflow');
    }
  }

  $(".child-popup-open.open").on("click", function(){
    $(this).toggleClass("active").siblings(".child-popup").slideToggle();
  });

  $(".cart-open").on("click", function (e) {
    cartPopup(e)
  })

  $(".close-cart-popup").on("click", function (e) {
    cartPopup(e)
  })

  $(".search-popup-open").on("click", function () {
    searchPopup()
    $("html").addClass('no-overflow');
  })

  $(".search-popup-close").on("click", function () {
    searchPopup()
    $("html").removeClass('no-overflow');
  })

  $(".login-popup-open").on("click", function () {
    if (!$('#login-popup').hasClass('active')){
      loginPopup()
    }
  })

    $(".login-popup-close").on("click", function () {
        loginPopup()
        $("html").removeClass('no-overflow');
    })

    $(".consult-popup-open").on("click", function () {
        if (!$('#consult-popup').hasClass('active')){
            consultPopup()
        }
    })

  $(".consult-popup-close").on("click", function () {
      consultPopup()
  });

    $(".fast-order-popup-open").on("click", function () {
        fastOrderPopup()
    })

    $(".fast-order-popup-close").on("click", function () {
        fastOrderPopup()
    })
  //
  // $("#button-verify-loginpopup").click(function (){
  //   $(".password-group").show()
  //   if($(".password-group").hasClass('active')){
  //     return open_pop_up("#popup-registration");
  //   }
  //   $(".password-group").addClass('active')
  // })

  // $(".login-show").click(function () {
  //   $(".registration-show").removeClass('active');
  //   $(".login-show").addClass('active');
  //   return open_pop_up("#popup-login");
  // });

  // $(".registration-show").click(function () {
  //   $(".login-show").removeClass('active');
  //   $(".registration-show").addClass('active')
  //   return open_pop_up("#popup-registration");
  // });

  $(".forgot_password-show").click(function () {
    return open_pop_up("#popup-forgot_password");
  })

  function open_pop_up(e) {
    $("#credential_picker_container").toggle();
    // $(".popup-window").fadeOut();
    $(".popup-window").hide()

    $(".popup-window").removeClass('active');
    $(".popup-overlay").addClass('active');
    // $(".popup-overlay").fadeIn();
    $("html").addClass('no-overflow');
    // $(e).fadeIn().addClass('active');
    $(e).show().addClass('active');

  }



  let seoContentHeight = 104;
  let seoContent = $('.seo-content');
  seoContent.css("height", "auto");
  let outerContentsHeight = seoContent.height();
  seoContent.css("height", seoContentHeight+"px");

  $(window).on('resize',function(){
    seoContent.css("height", "auto");
    outerContentsHeight = seoContent.height();
    if( seoContent.hasClass('open')){
      seoContent.css('height', outerContentsHeight+'px')
    }
    else {
      seoContent.css('height', seoContentHeight+'px')
    }
  })

  $('.seo-btn.show').click(function () {
    $('.seo-btn.show').hide();
    $('.seo-btn.collapse').show()
    seoContent.toggleClass('open');
    showCollapseSeoContent()
  })

  $('.seo-btn.collapse').click(function () {
    $('.seo-btn.show').show();
    $('.seo-btn.collapse').hide()
    seoContent.toggleClass('open');
    showCollapseSeoContent()
  })
  function showCollapseSeoContent(){
    if(seoContent.hasClass('open')){
      seoContent.css('height', outerContentsHeight+'px')
    }
    else {
      seoContent.css('height', seoContentHeight+'px')
    }
  }
})



function cartPopupSlider(){
  let cartSlider = new Swiper("#cartProductSlider .popup-list", {
    navigation: {
      nextEl: "#cartProductSlider .home-slide-button .swiper-button-next",
      prevEl: "#cartProductSlider .home-slide-button .swiper-button-prev",
    },
    spaceBetween: 10,
    slidesPerView: 2,
    lazy: true,
    observeSlideChildren: true,
    observeParents: true,
    observer: true,
    breakpoints: {
      250: {
        slidesPerView: 1,
      },
      450: {
        slidesPerView: 2,
      }
    }
  });
  $('#cartProductSlider .home-products-nav .item').on('click', function(){
    var activeItem = $(this).attr('data-cat');
    if (activeItem != 'all') {
      $('#cartProductSlider .home-products-nav .item').removeClass('active');
      $('#cartProductSlider .home-products-item').removeClass('show').addClass('hide');
      $(this).addClass('active');
      $('#cartProductSlider .home-products-item[data-ids="'+activeItem+'"]').removeClass('hide').addClass('show');
      cartSlider.update();
    } else {
      $('#cartProductSlider .home-products-nav .item').removeClass('active');
      $('#cartProductSlider .home-products-item').removeClass('hide');
      $(this).addClass('active');
      cartSlider.update();
    }
  });

    $(document).ready(function() {
        $('#button-submit-consultation').on('click', function() {
            var name = $('#name').val().trim();
            var phone = $('#phone').val().trim();
            var email = $('#email').val().trim();
            var comment = $('#comment').val().trim();
            var productId = $('#consult-popup-product-id').val();

            var formData = {
                'name': name,
                'phone': phone,
                'email': email,
                'comment': comment,
                'product_id': productId
            };

            $.ajax({
                url: '/consultation',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        $('#popup-consultation').hide();
                        $('.popup-consult-thanks').show();
                    } else {
                        $('.error-message-consultation').text(response.message).show();
                    }
                },
                error: function(xhr, status, error) {
                    $('.error-message-consultation').empty().show();

                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        for (var field in errors) {
                            var errorMessage = errors[field].join('<br>');
                            var errorElement = '<div class="error-item"><span>' + errorMessage + '</span></div>';
                            $('.error-message-consultation').append(errorElement);
                        }
                    } else {
                        $('.error-message-consultation').text('Произошла ошибка. Попробуйте снова.').show();
                    }
                }
            });
        });

        $('#button-submit-fast-order').on('click', function(e) {
            e.preventDefault();

            let formData = {
                product_id: $('#fast-order-product-id').val(),
                quantity: $('#input-quantity').val(),
                name: $('#fast_order_name').val(),
                phone: $('#fast_order_phone').val(),
                email: $('#fast_order_email').val(),
                comment: $('#fast_order_comment').val(),
                total_price: $('#total-price').text(),
            };

            console.log(formData);

            $.ajax({
                url: '/fast-order',
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.status === 'success') {
                        $('#order-popup').hide();
                        $('#fast-order-form').hide();
                        $('.consult-title').hide();
                        $('.fast-order-success').show();
                    }
                },
                error: function(xhr, status, error) {
                    $('.error-message-fast-order').empty().show();

                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        for (var field in errors) {
                            var errorMessage = errors[field].join('<br>');
                            var errorElement = '<div class="error-item"><span>' + errorMessage + '</span></div>';
                            $('.error-message-fast-order').append(errorElement);
                        }
                    }
                }
            });
        });
    });
}

function showHideSubCategories(){
  let openSubMenu = $('.openSubMenu')
  let mainMenu = $('.head-catalog-container.mainMenu')
  let subMenu = $('.head-catalog-container.subMenu')
  let backToMainCategory = $('.head-catalog-container .back_to_main_category')

  backToMainCategory.on('click', function (){
    let currentCutegoryId = $(this).data('category-id')
    subMenu.each(function (){
      if($(this).data('modal-category') === currentCutegoryId){
        $(this).removeClass('show')
      }
    })
    mainMenu.addClass('show')
  })

  openSubMenu.on('click', function (){
    let currentCutegoryId = $(this).data('category-id')
    mainMenu.removeClass('show')
    subMenu.each(function (){
      if($(this).data('modal-category') === currentCutegoryId){
        $(this).addClass('show')
      }
    })
  })
}
