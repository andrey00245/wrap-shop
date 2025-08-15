document.addEventListener('DOMContentLoaded', function () {
    // Убедитесь, что все элементы существуют
    let shippingMethod = document.querySelector('#simplecheckout_shipping');
    let shippingMethodAddress = document.querySelector('#simplecheckout_shipping_address');
    let novaPoshtaDesc = document.querySelector('#novaposhta_desc');
    let cityField = document.querySelector('#city');  // Поле города
    let shippingAddressField = document.querySelector('#shipping_address');  // Поле адреса доставки
    let citySelectWrapper = document.querySelector('#city-select-wrapper');  // Обертка для select
    let cityInputWrapper = document.querySelector('#city-input-wrapper');  // Обертка для обычного поля города
    let myAddressesOption = document.querySelector('#my_addresses');  // Опция "Мої адреси"

    // Если элементы существуют, можно продолжить выполнение
    if (novaPoshtaDesc) {
        novaPoshtaDesc.remove();
    }

    if (!shippingMethod.querySelector('input:checked')) {
        shippingMethod.querySelector('#pickup').checked = true;
    }

    function updateAddressFields() {
        const selectedMethod = shippingMethod.querySelector('input:checked');
        const addressSuggestionsBox = document.querySelector("#address-suggestions"); // Добавляем эту строку

        if (selectedMethod && selectedMethod.id === 'pickup') {
            shippingMethodAddress.style.display = 'none';
            shippingAddressField.required = false;
        } else {
            shippingMethodAddress.style.display = '';
            shippingAddressField.required = true;
        }

        // Для методов "novaposhta" или "novaposhta_doors" отображаем описание Новой Почти
        if (selectedMethod && (selectedMethod.id === 'novaposhta' || selectedMethod.id === 'novaposhta_doors')) {
            if (novaPoshtaDesc && selectedMethod) {
                selectedMethod.parentNode.parentNode.insertAdjacentElement('afterend', novaPoshtaDesc);
            }
        } else {
            if (novaPoshtaDesc) {
                novaPoshtaDesc.remove();
            }
        }

        // Если выбран метод "my_addresses", показываем select для города и делаем поле адреса неактивным
        if (selectedMethod && selectedMethod.id === 'my_addresses' && myAddressesOption) {
            // Очищаем значение поля для адреса
            if (shippingAddressField) {
                shippingAddressField.value = '';
                shippingAddressField.disabled = true; // Делаем поле адреса неактивным
            }

            // Прячем список предложений для адреса
            if (addressSuggestionsBox) {
                addressSuggestionsBox.style.display = 'none'; // скрываем предложения
            }

            // Прячем обычное поле ввода города и показываем select для выбора города
            if (cityInputWrapper) {
                cityInputWrapper.style.display = 'none';
            }
            if (citySelectWrapper) {
                citySelectWrapper.style.display = 'block';
            }
        } else {
            // Очистка значений полей (как для адреса, так и для города)
            if (shippingAddressField) {
                shippingAddressField.value = ''; // Очищаем поле для адреса
                shippingAddressField.disabled = false; // Разблокируем поле для ввода адреса
            }
            if (cityField) {
                cityField.value = ''; // Очищаем поле для города
            }

            // Прячем select города и показываем обычный input
            if (citySelectWrapper) {
                citySelectWrapper.style.display = 'none';
            }
            if (cityInputWrapper) {
                cityInputWrapper.style.display = 'block';
            }
        }
    }



    // Слушаем изменение метода доставки
    if (shippingMethod) {
        shippingMethod.addEventListener('change', function () {
            updateAddressFields();
        });
    }

    // Слушаем изменение города в select
    if (citySelectWrapper) {
        const citySelect = citySelectWrapper.querySelector('select');
        if (citySelect) {
            citySelect.addEventListener('change', function () {
                const selectedAddressId = citySelect.value; // Получаем ID выбранного города (address)
                const selectedAddressOption = citySelect.querySelector(`option[value="${selectedAddressId}"]`);

                if (selectedAddressOption && shippingAddressField) {
                    // Заполняем поле адреса вручную, если выбрали город
                    shippingAddressField.value = selectedAddressOption.dataset.address || '';
                }
            });
        }
    }

    // Инициализация слайдера товаров в корзине



    let checkoutSlider = new Splide('#checkoutProductSlider .splide', {
        pagination: false,
        perPage: 2,
        gap: '5px',
        padding: {right: '3%'},
        arrows: false,
        classes: {
            arrows: 'splide__arrows home-products-slide-buttons">',
            prev: 'splide__arrow--prev',
            next: 'splide__arrow--next',
        },
        breakpoints: {
            1331: {
                perPage: 1,
                padding: {right: '35%'},
            },
            1019: {
                perPage: 2,
                padding: {right: '5%'},
            },
            767: {
                perPage: 1,
                padding: {right: '35%'},
            },
            400: {
                perPage: 1,
                padding: {right: 0},
            }
        }
    });


    const prevBtn = document.querySelector('.custom_arrows .custom_arrow.custom__prev-arrow');
    const nextBtn = document.querySelector('.custom_arrows .custom_arrow.custom__next-arrow');

    function updateArrows() {
        const index = checkoutSlider.index;
        const lastIndex = checkoutSlider.Components.Controller.getEnd();

        prevBtn.classList.toggle('is-disabled', index === 0);
        nextBtn.classList.toggle('is-disabled', index === lastIndex);
    }

    checkoutSlider.on('mounted move', updateArrows);

    prevBtn.addEventListener('click', () => {
        checkoutSlider.go('<');
    });

    nextBtn.addEventListener('click', () => {
        checkoutSlider.go('>');
    });

    checkoutSlider.mount();
    updateAddressFields();  // Инициализация состояния

    let paymentMethods = document.querySelectorAll('input[name="payment_method"]');

    function savePaymentMethod() {
        const selectedPaymentMethod = document.querySelector('input[name="payment_method"]:checked');
        if (selectedPaymentMethod) {
            localStorage.setItem('selectedPaymentMethod', selectedPaymentMethod.value);
        }
    }

    function restorePaymentMethod() {
        const savedPaymentMethod = localStorage.getItem('selectedPaymentMethod');
        if (savedPaymentMethod) {
            const savedPaymentMethodInput = document.querySelector(`input[name="payment_method"][value="${savedPaymentMethod}"]`);
            if (savedPaymentMethodInput) {
                savedPaymentMethodInput.checked = true;
            }
        }
    }

    paymentMethods.forEach(function (paymentMethod) {
        paymentMethod.addEventListener('change', function () {
            savePaymentMethod();
        });
    });

    restorePaymentMethod();

    document.querySelector('#checkoutForm').addEventListener('submit', function (e) {
        const cityInput = document.querySelector('input[name="city"]');
        const citySelect = document.querySelector('select[name="city_select"]');

        if (citySelect && citySelect.style.display === 'block') {
            // Если выбран метод "Мої адреси" и отображается select, передаем значение select в input
            cityInput.value = citySelect.options[citySelect.selectedIndex].value; // Записываем в поле "city" значение выбранного города
        }
    });
});

$(document).ready(function() {
    let debounceTimer;
    let cityRefSelected = null;
    let branchesData = []; // Сохраняем данные по отделениях
    // Флаг: содержит ли корзина пленки (м.п.) от 1 м
    const hasLongFilm = document.getElementById('checkoutForm')?.dataset?.hasLongFilm === '1';

    // Обработчик изменения радиокнопки
    $("input[name='shipping_method']").on("change", function() {
        $("#city").val('');
        $("#shipping_address").val('');
        // Проверяем, выбран ли метод "Відділення Нової Пошти"
        if ($("#novaposhta").is(":checked")) {
            // Включаем автозаполнение для поля города и адреса
            $("#city").prop("disabled", false); // Разрешаем ввод в поле города
            $("#shipping_address").prop("disabled", false); // Разрешаем ввод в поле адреса
            // $("#city-suggestions").show(); // Показываем предложения для города
            // $("#address-suggestions").show(); // Показываем предложения для адреса
        } else {
            // Отключаем автозаполнение для других методов
            $("#city").prop("disabled", false); // Поле города остается активным для ручного ввода
            $("#shipping_address").prop("disabled", false); // Поле для адреса остается активным для ручного ввода
            $("#city-suggestions").hide(); // Скрываем предложения для города
            $("#address-suggestions").hide(); // Скрываем предложения для адреса
        }
    });

    // Обработка ввода в поле города
    $("#city").on("input", function() {
        if (!$("#novaposhta").is(":checked")) {
           return;
        }
        clearTimeout(debounceTimer);
        let query = $(this).val().trim();
        let $suggestionsBox = $(this).siblings("#city-suggestions");

        // Если введено менее 2 символов — скрываем подсказки
        if (query.length < 2) {
            $suggestionsBox.hide();
            return;
        }

        let locale = $("meta[name='locale']").attr("content");

        debounceTimer = setTimeout(function() {
            $.ajax({
                url: '/'+locale+'/api/get-cities/', // API для получения городов
                type: "GET",
                data: { cityName: query },
                success: function(response) {
                    let suggestions = response.data;
                    let suggestionsList = "";

                    if (suggestions.length > 0) {
                        suggestions.forEach(city => {
                            suggestionsList += `<li data-id="${city.ref}" data-value="${city.name}">
                                                    ${city.name} (${city.region})
                                                </li>`;
                        });
                    } else {
                        suggestionsList = "<li class='no-results'>Місто не знайдено</li>";
                    }

                    $suggestionsBox.html(suggestionsList).show();
                },
                error: function() {
                    $suggestionsBox.html("<li class='no-results'>Помилка завантаження</li>").show();
                }
            });
        }, 300);
    });

    // Обработка клика по городу из предложений
    $(document).on("click", "#city-suggestions li", function(e) {
        e.preventDefault();
        $("#address-suggestions").empty();
        $("#shipping_address").val('');
        let selectedCity = $(this).data("value");
        let cityRef = $(this).data("id"); // Сохраняем идентификатор города

        // Заполняем поле города
        $("#city").val(selectedCity);

        // Запоминаем выбранный cityRef
        cityRefSelected = cityRef;

        // Теперь запрашиваем отделения для выбранного города
        if (cityRefSelected) {
            fetchWarehouses(cityRefSelected);
        }

        // Прячем предложения
        $("#city-suggestions").hide();
    });

    // Функция для получения отделений по выбранному городу
    function fetchWarehouses(cityRef) {
        let locale = $("meta[name='locale']").attr("content");
        $.ajax({
            url: '/'+locale+"/api/get-branches", // API для получения отделений
            type: "GET",
            data: { cityRef: cityRef },
            success: function(response) {
                let $shippingAddressInput = $("#shipping_address");
                let $addressSuggestionsBox = $("#address-suggestions");

                // Сохраняем данные о полученных отделениях
                branchesData = (response.data || []).filter(function(wh){
                    if (!hasLongFilm) return true;
                    // Простая эвристика: пропускаем только грузовые отделения
                    // (часто содержат "Вантаж" / "Груз" / Cargo / цифры 200/1100)
                    const n = (wh.name || '').toLowerCase();
                    return n.includes('вантаж') || n.includes('груз') || n.includes('cargo') || n.includes('200') || n.includes('1100');
                });

                // Показываем поле для ввода отделения
                $shippingAddressInput.prop("disabled", false);

                // Заполняем список предложений
                updateAddressSuggestions("");
            },
            error: function() {
                console.log('Помилка при отриманні відділень.');
            }
        });
    }

    // Функция для обновления списка предложений по введенному тексту
    function updateAddressSuggestions(query) {
        let $addressSuggestionsBox = $("#address-suggestions");
        let suggestionsList = "";

        // Фильтруем предложения по введенному запросу
        branchesData.forEach(function(branch) {
            if (branch.name.toLowerCase().indexOf(query.toLowerCase()) !== -1) {
                suggestionsList += `<li data-id="${branch.id}" data-value="${branch.name}">
                    ${branch.name}
                </li>`;
            }
        });

        if (suggestionsList === "") {
            suggestionsList = "<li class='no-results'>Відділення не знайдено</li>";
        }

        // Показываем предложения
        $addressSuggestionsBox.html(suggestionsList).show();
    }

    // Обработка ввода в поле shipping_address (автозаполнение)
    $("#shipping_address").on("input", function() {
        if (!$("#novaposhta").is(":checked")) {
            return;
        }
        let query = $(this).val().trim();
        updateAddressSuggestions(query); // Обновляем предложения в зависимости от введенного текста
    });

    $(document).on("click", "#address-suggestions li", function(e) {
        e.preventDefault();
        let selectedAddress = $(this).data("value");
        let ref = $(this).data("id"); // <--- ВАЖНО

        // Заполняем поле с адресом
        $("#shipping_address").val(selectedAddress);

        // Записываем ref в скрытое поле
        $("#novaposhta_warehouse_ref").val(ref); // <--- ВОТ ЭТО

        console.log(ref);
        // Прячем список предложений
        $("#address-suggestions").hide();
    });

    // Скрываем список предложений при клике вне поля
    $(document).on("click", function(e) {
        if (!$(e.target).closest("#city-input-wrapper").length) {
            $("#city-suggestions").hide();
        }

        if (!$(e.target).closest("#shipping_address").length) {
            $("#address-suggestions").hide();
        }
    });

    // Показывать предложения при фокусе на поле shipping_address
    $("#shipping_address").on("focus", function() {
        if ($(this).val().length >= 2) {
            $("#address-suggestions").show();
        }
    });
});
