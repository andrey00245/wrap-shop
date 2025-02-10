$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click', '.remove-cart-button', function() {
        const itemId = $(this).data('id');
        $.ajax({
            url: '/cart/remove/' + itemId,
            method: 'POST',
            success: function (response) {
                $('#cart-total').html(response.cartItemsCount)
                $('.cart-shopping-items').html(response.cartItems);
            },
            error: function () {
                alert('Произошла ошибка при удалении товара.');
            }
        });
    });

    $('#button-cart').on('click', function(e) {
        var productId = $(this).data('product-id');
        var quantity = $('#input-quantity-' + productId).val();
        if (parseFloat(quantity) > 0) {
            $.ajax({
                url: '/cart/add',
                method: 'POST',
                data: {
                    product_id: productId,
                    quantity: quantity
                },
                success: function(response) {
                    $('#cart-total').html(response.cartItemsCount)
                    $('.cart-shopping-items').html(response.cartItems);
                    cartPopup(e)
                },
                error: function(xhr, status, error) {
                    alert('Ошибка при добавлении в корзину: ' + error);
                }
            });
        } else {
            alert('Пожалуйста, выберите корректное количество товара.');
        }
    });

    $('.button-cart-product').on('click', function(e) {
        var productId = $(this).data('product-id');
        var quantity = $(this).data('product-quantity');
        if (parseFloat(quantity) > 0) {
            $.ajax({
                url: '/cart/add',
                method: 'POST',
                data: {
                    product_id: productId,
                    quantity: quantity
                },
                success: function(response) {
                    $('#cart-total').html(response.cartItemsCount)
                    $('.cart-shopping-items').html(response.cartItems);
                    cartPopup(e)
                },
                error: function(xhr, status, error) {
                    alert('Ошибка при добавлении в корзину: ' + error);
                }
            });
        } else {
            alert('Пожалуйста, выберите корректное количество товара.');
        }
    });

    $(document).on('click', '#plus-btn-cart, #minus-btn-cart', function() {
        var quantityInput = $(this).closest('.cart-quantity-input-wrappper').find('#input-quantity-1');
        var currentQuantity = parseFloat(quantityInput.val());

        console.log(quantityInput)
        console.log(currentQuantity)
        currentQuantity = parseFloat(currentQuantity.toFixed(2));

        console.log(currentQuantity);

        var step = parseFloat(quantityInput.data('step'));
        var newQuantity = currentQuantity;

        if ($(this).attr('id') === 'plus-btn-cart') {
            newQuantity = currentQuantity + step;
        } else if ($(this).attr('id') === 'minus-btn-cart') {
            newQuantity = currentQuantity - step;
        }

        newQuantity = parseFloat(newQuantity.toFixed(1));

        var minQuantity = parseFloat(quantityInput.data('min'));
        var maxQuantity = parseFloat(quantityInput.data('max'));

        if (newQuantity < minQuantity) newQuantity = minQuantity;
        if (newQuantity > maxQuantity) newQuantity = maxQuantity;

        quantityInput.val(newQuantity);

        var productId = $(this).closest('tr').data('id');
        updateCarts(productId, newQuantity);
    });


    function updateCarts(productId, quantity) {
        $.ajax({
            url: '/cart/update',
            method: 'POST',
            data: {
                product_id: productId,
                quantity: quantity,
            },
            success: function(response) {
                $('#cart-total').html(response.cartItemsCount);
                $('.cart-shopping-items').html(response.cartItems);

                $('tr[data-id="' + productId + '"] .price-all').html(response.updatedPrice + ' ₴');
                $('tr[data-id="' + productId + '"] .total .value').html(response.cartTotal + ' ₴');
            },
            error: function() {
                alert('Произошла ошибка при обновлении товара.');
            }
        });
    }

    $('#input-telephone').inputmask('+380 99 999 99 99', { "placeholder": " " });

    $('#registration-form').on('submit', function(e) {
        e.preventDefault();

        $('.input-error').removeClass('input-error');
        $('.error-message').remove();

        var isValid = true;
        var formData = {
            email: $('#register_name_email').val(),
            password: $('#register_password').val(),
            name: $('#input-name').val(),
            last_name: $('#input-last_name').val(),
            phone: $('#input-telephone').val(),
        };

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            success: function(response) {
                window.location.href = response.redirect_url;
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;

                    var errorMessages = '<ul>';
                    for (var field in errors) {
                        errorMessages += '<li>' + errors[field].join('<br>') + '</li>';
                    }
                    errorMessages += '</ul>';
                    $('.error-message-login').html(errorMessages).show();
                }
            }
        });
    });


    $('#button-verify-loginpopup').on('click', function() {
        var phoneEmail = $('#name_email').val().trim();
        var password = $('#login_password').val().trim();

        $('.input-error').removeClass('input-error');

        if (phoneEmail.length === 0) {
            $('#name_email').addClass('input-error')
            return;
        }

        if (phoneEmail && !password) {
            $.ajax({
                url: '/auth/check-user',
                method: 'POST',
                data: {
                    phone_email: phoneEmail,
                },
                success: function(response) {
                    if (response.exists) {
                        $(".password-group").show();
                    } else {
                        $(".password-group").hide();
                        return open_pop_up("#popup-registration");
                    }
                },
                error: function() {
                    alert('Error occurred while checking user');
                }
            });
        }

        if (phoneEmail  && password) {
            $.ajax({
                url: '/auth/login',
                method: 'POST',
                data:  {
                    phone_email: phoneEmail,
                    password: password,
                },
                success: function (response) {
                    window.location.href = response.redirect_url;
                },
                error: function (xhr) {
                    var errors = xhr.responseJSON.errors;
                    if (errors) {
                        var errorMessages = '';
                        $.each(errors, function (key, message) {
                            errorMessages += `<span style="color:red">${message}</span>`;
                        });
                        $('.error-message-login').html(errorMessages).show();
                    } else {
                        $('.error-message-login').html('Произошла ошибка при входе.').show();
                    }
                }
            });
        }
    });

    $('.g_id_signout').on('click', function(event) {
        event.preventDefault();
        $('#logout-form').submit();
    });

    function open_pop_up(e) {
        $("#credential_picker_container").toggle();
        $(".popup-window").hide()

        $(".popup-window").removeClass('active');
        $(".popup-overlay").addClass('active');
        // $(".popup-overlay").fadeIn();
        $("html").addClass('no-overflow');
        // $(e).fadeIn().addClass('active');
        $(e).show().addClass('active');

    }
    function cartPopup(e) {
        e.preventDefault()

        $('#simple-popup').removeClass('active');
        $('#search-popup').removeClass('active');
        $('#login-popup').removeClass('active');
        $('.head-top').removeClass('active');
        if($('#cart-popup').hasClass('active')){
            $("html").addClass('no-overflow');
        }
        else {
            $('#cart-popup').toggleClass('active');
            $("html").removeClass('no-overflow');
        }
    }
});
