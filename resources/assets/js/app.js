let scroll = $(window).scrollTop();
slideHeader(scroll);

$(window).scroll(function (event) {
  scroll = $(window).scrollTop();
  slideHeader(scroll);
});

function slideHeader(scroll){
  if (scroll > 100) {
    $('.fixed-header').addClass('show');
    $('.popup-right').addClass('fixed-header-active');
  } else {
    $('.fixed-header').removeClass('show');
    $('.popup-right').removeClass('fixed-header-active');
  }
}
