$(document).ready(function () {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  const wishlists = $('.wishlist button')

  wishlists.on('click', function (event) {
    event.preventDefault();
    const currentButton = $(this);
    const productId = currentButton.data('product-id')
    const data = {
      product_id: productId
    }

    $.ajax({
      url: '/add-product-to-wishlist',
      method: 'POST',
      data: data,
      success: function (response) {
        $('.wishlist-total').text(response['count'])
        if(response['status'] === 'add'){
          currentButton.addClass('fas')
          currentButton.addClass('in-wishlist')
          currentButton.removeClass('far')
        }
        else {
          currentButton.removeClass('fas')
          currentButton.removeClass('in-wishlist')
          currentButton.addClass('far')
        }
      },
      error: function (xhr) {

      }
    });
  });
});
