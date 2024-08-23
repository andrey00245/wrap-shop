$(".page-payment-nav .button").on("click", function(){
  var activTabe = $(this).attr("data-href");
  $(".page-payment-nav .button, .page-payment-tab .item").removeClass("active");
  $(this).addClass("active");
  $(activTabe).addClass("active");
});
