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

  $(".cart-open").on("click", function () {
    cartPopup()
  })

  $(".close-cart-popup").on("click", function () {
    cartPopup()
  })

  $(".search-popup-open").on("click", function () {
    searchPopup()
  })

  $(".search-popup-close").on("click", function () {
    searchPopup()
  })

  $(".login-popup-open").on("click", function () {
    if (!$('#login-popup').hasClass('active')){
      loginPopup()
    }
  })

  $(".login-popup-close").on("click", function () {
    loginPopup()
  })






})
