$(document).ready(function () {

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
    } else {
      $('.fixed-header').removeClass('show');
      $('.popup-right').removeClass('fixed-header-active');
    }
  }

  $(".head-catalog-open, .head-catalog-bg").on("click", function () {
    $('.head-catalog').slideToggle();
    $(".head-catalog-open, .head-catalog-bg").toggleClass("active");
  });
  $(".mobil-catalog-open, .head-catalog-close").on("click", function () {
    $('.head-catalog').toggleClass("active");
  });

  function cartPopup() {
    $('#cart-popup').toggleClass('active');
    $('#simple-popup').removeClass('active');
    $('#search-popup').removeClass('active');
    $('#login-popup').removeClass('active');
  }

  function searchPopup() {
    $('#search-popup').toggleClass('active');
    $('#simple-popup').removeClass('active');
    $('#cart-popup').removeClass('active');
    $('#login-popup').removeClass('active');
  }

  function loginPopup(){
    $('#login-popup').toggleClass('active');
    $('#search-popup').removeClass('active');
    $('#simple-popup').removeClass('active');
    $('#cart-popup').removeClass('active');
  }

  $(".child-popup-open.open").on("click", function(){
    $(this).toggleClass("active").siblings(".child-popup").slideToggle();
  });

  $(".cart-open").on("click", function () {
    cartPopup()
  })

  $(".close-cart-popup").on("click", function () {
    cartPopup()
    $("html").removeClass('no-overflow');

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

  $(".login-show").click(function () {
    $(".registration-show").removeClass('active');
    $(".login-show").addClass('active');
    return open_pop_up("#popup-login");
  });

  $(".registration-show").click(function () {
    $(".login-show").removeClass('active');
    $(".registration-show").addClass('active')
    return open_pop_up("#popup-registration");
  });

  $(".forgot_password-show").click(function () {
    return open_pop_up("#popup-forgot_password");
  })

  function open_pop_up(e) {
    $("#credential_picker_container").toggle();
    $(".popup-window").fadeOut();
    $(".popup-window").removeClass('active');
    $(".popup-overlay").addClass('active');
    // $(".popup-overlay").fadeIn();
    $("html").addClass('no-overflow');
    $(e).fadeIn().addClass('active');
  }
})
