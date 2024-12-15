$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

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
                url: 'auth/check-user',
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
});
