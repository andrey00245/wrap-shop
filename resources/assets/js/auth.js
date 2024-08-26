$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#input-telephone').inputmask('+380 99 999 99 99', { "placeholder": " " });

    $('#form-registration').on('submit', function (event) {

        event.preventDefault(); // Предотвращаем стандартную отправку формы

        // Получаем данные формы
        var formData = new FormData(this);

        // Очистка старых ошибок
        $('.form-group').removeClass('error');
        $('.form-group .error-message').remove();

        // Флаг валидности формы
        var valid = true;

        // Простая валидация
        $('.form-field.required').each(function () {
            var $this = $(this);
            var fieldName = $this.attr('name');
            var fieldValue = $this.val().trim();

            // Проверка на пустое поле
            if (fieldValue === '') {
                valid = false;
                $this.closest('.form-group').addClass('error');
            }
        });

        // Проверка согласия с условиями
        if (!$('#agree').is(':checked')) {
            valid = false;
            $('#slagreep').addClass('error');
            $('#slagreep').append('<div class="error-message">Вы должны согласиться с условиями.</div>');
        }


        // Если форма валидна, отправляем данные
        if (valid) {
            $.ajax({
                url: $('#form-registration').attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    // Перенаправляем на URL, полученный в ответе
                    window.location.href = response.redirect_url;
                },
                error: function (xhr) {
                    // Обработка ошибок
                    const errors = xhr.responseJSON.errors;
                    for (var field in errors) {
                        if (errors.hasOwnProperty(field)) {
                            const errorMessages = errors[field];
                            const $field = $('input[name="' + field + '"]');
                            $field.closest('.form-group').addClass('error');
                            $field.closest('.form-group').append('<div class="error-message">' + errorMessages.join('<br>') + '</div>');
                        }
                    }
                }
            });
        }
    });

    $('#form-login').on('submit', function (event) {
        event.preventDefault(); // Предотвращаем стандартную отправку формы

        // Получаем данные формы
        var formData = $(this).serialize();
        console.log('111')
        // Очистка старых ошибок
        $('.form-group').removeClass('error');
        $('.form-group .error-message').remove();

        // Флаг валидности формы
        var valid = true;

        // Если форма валидна, отправляем данные
            $.ajax({
                url: $('#form-login').attr('action'),
                method: 'POST',
                data: formData,
                success: function (response) {
                    // Обработка успешного ответа
                    window.location.href = response.redirect_url; // Перенаправление после успешного входа
                },
                error: function (xhr) {
                    // Обработка ошибок от сервера
                    var errors = xhr.responseJSON.errors;
                    if (errors) {
                        $.each(errors, function (key, message) {
                            var $input = $(`[name=${key}]`);
                            $input.closest('.form-group').addClass('error');
                            $input.after(`<div class="error-message">${message}</div>`);
                        });
                    } else {
                        alert('Произошла ошибка при входе.');
                    }
                }
            });
    });

    $('.g_id_signout').on('click', function(event) {
        event.preventDefault(); // Предотвращаем стандартное действие ссылки

        // Отправка скрытой формы
        $('#logout-form').submit();
    });
});
