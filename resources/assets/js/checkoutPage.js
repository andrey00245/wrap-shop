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
        const novaPoshtaOptions = document.querySelector('#nova-poshta-options');

        if (selectedMethod && selectedMethod.id === 'pickup') {
            shippingMethodAddress.style.display = 'none';
            shippingAddressField.required = false;
        } else if (selectedMethod && selectedMethod.id === 'flat') {
            // Для доставки по Киеву показываем поле адреса
            shippingMethodAddress.style.display = '';
            const kyivFields = document.querySelector('#kyiv-fields');
            if (kyivFields) {
                kyivFields.style.display = 'block';
                const kyivAddressField = document.querySelector('#kyiv_address');
                if (kyivAddressField) {
                    kyivAddressField.disabled = false;
                    kyivAddressField.required = true;
                }
            }
            // Скрываем поля Nova Poshta
            const novaPoshtaOptions = document.querySelector('#nova-poshta-options');
            if (novaPoshtaOptions) {
                novaPoshtaOptions.style.display = 'none';
            }
            shippingAddressField.required = false;
        } else {
            shippingMethodAddress.style.display = '';
            shippingAddressField.required = true;
        }

        // Показываем/скрываем опции Nova Poshta
        if (selectedMethod && selectedMethod.id === 'novaposhta') {
            if (novaPoshtaOptions) {
                novaPoshtaOptions.style.display = 'block';
            }
            if (novaPoshtaDesc && selectedMethod) {
                selectedMethod.closest('.nova-poshta-group').querySelector('.nova-poshta-main').insertAdjacentElement('afterend', novaPoshtaDesc);
            }
            
            // Скрываем/показываем радио кнопку почтомата в зависимости от длинной пленки
            const hasLongFilm = document.getElementById('checkoutForm')?.dataset?.hasLongFilm === '1';
            const lockerRadio = document.querySelector('#locker-radio');
            if (lockerRadio) {
                lockerRadio.style.display = hasLongFilm ? 'none' : 'block';
            }
            
            // Выбираем первое радио (отделение) по умолчанию
            const firstRadio = document.querySelector('input[name="nova_poshta_type"][value="branch"]');
            if (firstRadio && !document.querySelector('input[name="nova_poshta_type"]:checked')) {
                firstRadio.checked = true;
            }
            // Обновляем поля для Nova Poshta
            updateNovaPoshtaFields();
        } else {
            if (novaPoshtaOptions) {
                novaPoshtaOptions.style.display = 'none';
            }
            if (novaPoshtaDesc) {
                novaPoshtaDesc.remove();
            }
            // Скрываем все поля Nova Poshta
            document.querySelectorAll('#branch-fields, #locker-fields, #courier-fields, #courier-house-fields').forEach(field => {
                if (field) field.style.display = 'none';
            });
        }

        // Если выбран метод "my_addresses", показываем select для города и поле адреса
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

            // Показываем поле адреса для "Мои адреса"
            const myAddressFields = document.querySelector('#my-address-fields');
            console.log('My address fields element:', myAddressFields);
            if (myAddressFields) {
                myAddressFields.style.display = 'block';
                console.log('My address fields shown');
            } else {
                console.log('My address fields not found!');
            }
        } else {
            // Скрываем поле адреса для "Мои адреса" если выбран другой метод
            const myAddressFields = document.querySelector('#my-address-fields');
            if (myAddressFields) {
                myAddressFields.style.display = 'none';
            }

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

    // Слушаем изменение типа Nova Poshta
    const novaPoshtaTypeInputs = document.querySelectorAll('input[name="nova_poshta_type"]');
    novaPoshtaTypeInputs.forEach(input => {
        input.addEventListener('change', function() {
            updateNovaPoshtaFields();
        });
    });

    // Функция для обновления полей в зависимости от типа Nova Poshta
    window.updateNovaPoshtaFields = function() {
        const selectedType = document.querySelector('input[name="nova_poshta_type"]:checked');
        const cityField = document.querySelector('#city');
        const branchFields = document.querySelector('#branch-fields');
        const lockerFields = document.querySelector('#locker-fields');
        const courierFields = document.querySelector('#courier-fields');
        const courierHouseFields = document.querySelector('#courier-house-fields');
        
        // Получаем флаг длинной пленки
        const hasLongFilm = document.getElementById('checkoutForm')?.dataset?.hasLongFilm === '1';

        // Скрываем все поля
        if (branchFields) branchFields.style.display = 'none';
        if (lockerFields) lockerFields.style.display = 'none';
        if (courierFields) courierFields.style.display = 'none';
        if (courierHouseFields) courierHouseFields.style.display = 'none';
        
        // Скрываем поля для Киева
        const kyivFields = document.querySelector('#kyiv-fields');
        if (kyivFields) {
            kyivFields.style.display = 'none';
            const kyivAddressField = document.querySelector('#kyiv_address');
            if (kyivAddressField) {
                kyivAddressField.disabled = true;
                kyivAddressField.required = false;
            }
        }

        // Отключаем все поля и убираем required
        if (cityField) cityField.disabled = true;
        document.querySelectorAll('#shipping_address, #locker_address, #courier_street, #courier_house, #kyiv_address').forEach(field => {
            if (field) {
                field.disabled = true;
                field.required = false;
            }
        });

        // Скрываем все сообщения об ошибках
        document.querySelectorAll('.simplecheckout-error-text').forEach(errorMsg => {
            errorMsg.style.display = 'none';
        });

        if (selectedType) {
            switch (selectedType.value) {
                case 'branch':
                    // Для отделения - показываем город и отделение
                    if (cityField) cityField.disabled = false;
                    if (branchFields) {
                        branchFields.style.display = 'block';
                        const shippingAddressField = document.querySelector('#shipping_address');
                        if (shippingAddressField) {
                            shippingAddressField.disabled = false;
                            shippingAddressField.required = true;
                        }
                        // Помилка відділення — лише в #branch-fields (не під містом); показує валідація форми, не одразу
                        const branchErrorMsg = branchFields.querySelector('[data-for="shipping_address"]');
                        if (branchErrorMsg) {
                            branchErrorMsg.style.display = 'none';
                        }

                        // Сбрасываем заголовок для отделения
                        const subtitle = document.querySelector('#nova-poshta-subtitle');
                        if (subtitle) {
                            subtitle.textContent = 'Адреса доставки';
                        }
                    }
                    break;
                case 'locker':
                    // Для почтомата - показываем город и почтомат (только если нет длинной пленки)
                    if (!hasLongFilm) {
                        if (cityField) cityField.disabled = false;
                        if (lockerFields) {
                            lockerFields.style.display = 'block';
                            const lockerAddressField = document.querySelector('#locker_address');
                            if (lockerAddressField) {
                                lockerAddressField.disabled = false;
                                lockerAddressField.required = true;
                            }
                            const lockerErrorMsg = lockerFields.querySelector('[data-for="locker_address"]');
                            if (lockerErrorMsg) {
                                lockerErrorMsg.style.display = 'none';
                            }
                        }
                        // Убираем required с поля отделения и скрываем его сообщение об ошибке
                        const shippingAddressField = document.querySelector('#shipping_address');
                        if (shippingAddressField) {
                            shippingAddressField.required = false;
                        }
                        const branchErrorMsg = document.querySelector('#branch-fields [data-for="shipping_address"]');
                        if (branchErrorMsg) {
                            branchErrorMsg.style.display = 'none';
                        }

                        // Сбрасываем заголовок для почтомата
                        const subtitle = document.querySelector('#nova-poshta-subtitle');
                        if (subtitle) {
                            subtitle.textContent = 'Адреса доставки';
                        }
                    } else {
                        // Если есть длинная пленка, переключаемся на отделение
                        const branchRadio = document.querySelector('input[name="nova_poshta_type"][value="branch"]');
                        if (branchRadio) {
                            branchRadio.checked = true;
                            updateNovaPoshtaFields(); // Рекурсивно вызываем для обновления
                        }
                    }
                    break;
                case 'courier':
                    // Для курьера - показываем город и 2 текстовых поля
                    if (cityField) cityField.disabled = false;
                    if (courierFields) {
                        courierFields.style.display = 'block';
                        const courierStreetField = document.querySelector('#courier_street');
                        if (courierStreetField) {
                            courierStreetField.disabled = false;
                            courierStreetField.required = true;
                        }
                    }
                    if (courierHouseFields) {
                        courierHouseFields.style.display = 'block';
                        const courierHouseField = document.querySelector('#courier_house');
                        if (courierHouseField) {
                            courierHouseField.disabled = false;
                            courierHouseField.required = true;
                        }
                    }
                    // Убираем required с других полей
                    const shippingAddressField = document.querySelector('#shipping_address');
                    const lockerAddressField = document.querySelector('#locker_address');
                    if (shippingAddressField) shippingAddressField.required = false;
                    if (lockerAddressField) lockerAddressField.required = false;
                    
                    // Обновляем заголовок для курьера
                    const subtitle = document.querySelector('#nova-poshta-subtitle');
                    if (subtitle) {
                        subtitle.textContent = 'Адреса доставки (Кур\'єром)';
                    }
                    break;
            }
        }
    };

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
    
    // Отладка элементов "Мои адреса"
    console.log('City select element:', $("select[name='city_select']").length);
    console.log('My address field:', $('#my_address').length);
    console.log('My address fields wrapper:', $('#my-address-fields').length);

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
    let postMachinesData = []; // Сохраняем данные по почтоматам

    /** Читаємо з DOM щоразу: після cartUpdated (AJAX) оновлюється data-has-long-film */
    function checkoutFormHasLongFilm() {
        return document.getElementById('checkoutForm')?.dataset?.hasLongFilm === '1';
    }

    // Функция для разделения числа на целую и дробную части
    function decimalAndIntParts(number) {
        const num = parseFloat(number);
        const integer = Math.floor(num);
        const decimal = Math.round((num - integer) * 100).toString().padStart(2, '0');
        return { integer, decimal };
    }

    // Обработчик изменения радиокнопки
    $("input[name='shipping_method']").on("change", function() {
        $("#city").val('');
        $("#shipping_address").val('');
        // Проверяем, выбран ли метод "Нова Пошта"
        if ($("#novaposhta").is(":checked")) {
            // Включаем автозаполнение для поля города и адреса
            $("#city").prop("disabled", false); // Разрешаем ввод в поле города
            $("#shipping_address").prop("disabled", false); // Разрешаем ввод в поле адреса
            // $("#city-suggestions).show(); // Показываем предложения для города
            // $("#address-suggestions").show(); // Показываем предложения для адреса
        } else {
            // Отключаем автозаполнение для других методов
            $("#city").prop("disabled", false); // Поле города остается активным для ручного ввода
            $("#shipping_address").prop("disabled", false); // Поле для адреса остается активным для ручного ввода
            $("#city-suggestions").hide(); // Скрываем предложения для города
            $("#address-suggestions").hide(); // Скрываем предложения для адреса
        }
    });

    // Обработчик изменения select для "Мои адреса"
    $(document).on("change", "select[name='city_select']", function() {
        console.log('Select change event triggered');
        const selectedOption = $(this).find('option:selected');
        const city = selectedOption.val();
        const address = selectedOption.data('address');
        
        console.log('Selected option:', selectedOption);
        console.log('City value:', city);
        console.log('Address data:', address);
        console.log('My address field exists:', $('#my_address').length > 0);
        
        if (city && address) {
            // Заполняем поля города и адреса
            $("#city").val(city);
            $("#my_address").val(address);
            
            console.log('Мои адреса - выбран город:', city, 'адрес:', address);
            console.log('City field value after setting:', $("#city").val());
            console.log('My address field value after setting:', $("#my_address").val());
        } else {
            // Очищаем поля если ничего не выбрано
            $("#city").val('');
            $("#my_address").val('');
            console.log('Cleared fields - no city or address selected');
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
        if ($(this).hasClass('no-results')) {
            return;
        }
        $("#address-suggestions").empty();
        $("#shipping_address").val('');
        $("#locker_address").val('');
        const branchErrOnCityPick = document.querySelector('#branch-fields [data-for="shipping_address"]');
        if (branchErrOnCityPick) {
            branchErrOnCityPick.style.display = 'none';
        }
        const lockerErrOnCityPick = document.querySelector('#locker-fields [data-for="locker_address"]');
        if (lockerErrOnCityPick) {
            lockerErrOnCityPick.style.display = 'none';
        }
        let selectedCity = $(this).data("value");
        let cityRef = $(this).data("id"); // Сохраняем идентификатор города

        // Заполняем поле города
        $("#city").val(selectedCity);

        // Запоминаем выбранный cityRef
        cityRefSelected = cityRef;

        // Відділення; поштомати — лише без рулонної плівки (один великий запит до НП на ~150 поштоматів)
        if (cityRefSelected) {
            fetchWarehouses(cityRefSelected);
            if (!checkoutFormHasLongFilm()) {
                fetchPostMachines(cityRefSelected);
            } else {
                postMachinesData = [];
                $('#locker_address').val('');
                $('#locker-suggestions').empty().hide();
            }
        }

        // Прячем предложения
        $("#city-suggestions").hide();
    });

    // Функция для получения отделений по выбранному городу
    function fetchWarehouses(cityRef) {
        let locale = $("meta[name='locale']").attr("content");
        $.ajax({
            url: '/'+locale+"/api/get-branches", // API: cargo_only=1 — вантажні відділення (бэкенд)
            type: "GET",
            data: {
                cityRef: cityRef,
                cargo_only: checkoutFormHasLongFilm() ? 1 : 0,
            },
            success: function(response) {
                let $shippingAddressInput = $("#shipping_address");
                let $addressSuggestionsBox = $("#address-suggestions");

                branchesData = response.data || [];

                // Показываем поле для ввода отделения
                $shippingAddressInput.prop("disabled", false);

                // Заполняем список предложений
                updateAddressSuggestions("");
                const branchErr = document.querySelector('#branch-fields [data-for="shipping_address"]');
                if (branchErr) {
                    branchErr.style.display = 'none';
                }
            },
            error: function() {
                console.warn('Помилка при отриманні відділень.');
            }
        });
    }

    // Функция для получения почтоматов по выбранному городу
    function fetchPostMachines(cityRef) {
        if (checkoutFormHasLongFilm()) {
            postMachinesData = [];
            return;
        }
        let locale = $("meta[name='locale']").attr("content");
        $.ajax({
            url: '/'+locale+"/api/get-postmachines", // API для получения почтоматов
            type: "GET",
            data: { cityRef: cityRef },
            success: function(response) {
                let $lockerAddressInput = $("#locker_address");

                postMachinesData = response.data || [];

                $lockerAddressInput.prop("disabled", false);

                updateLockerSuggestions("");
                const lockerErr = document.querySelector('#locker-fields [data-for="locker_address"]');
                if (lockerErr) {
                    lockerErr.style.display = 'none';
                }
            },
            error: function(xhr, status, error) {
                console.warn('Помилка при отриманні почтоматів:', error, xhr.responseText);
            }
        });
    }

    /**
     * У полі після вибору: «Місто, Назва відділення» — старий фільтр шукав лише name.indexOf(весь_рядок) → «не знайдено».
     */
    function npLabelMatchesQuery(storedLabel, fieldValue) {
        const q = (fieldValue || '').trim().toLowerCase();
        if (!q) {
            return true;
        }
        const n = (storedLabel || '').trim().toLowerCase();
        if (!n) {
            return false;
        }
        return n.indexOf(q) !== -1 || q.indexOf(n) !== -1;
    }

    // Функция для обновления списка предложений по введенному тексту
    function updateAddressSuggestions(query) {
        let $addressSuggestionsBox = $("#address-suggestions");
        let suggestionsList = "";

        // Фильтруем предложения по введенному запросу
        branchesData.forEach(function(branch) {
            if (npLabelMatchesQuery(branch.name, query)) {
                suggestionsList += `<li data-id="${branch.id}" data-value="${branch.name}" data-city="${branch.city || ''}">
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

    // Функция для обновления списка предложений почтоматов
    function updateLockerSuggestions(query) {
        let $lockerSuggestionsBox = $("#locker-suggestions");
        let suggestionsList = "";

        if (checkoutFormHasLongFilm()) {
            $lockerSuggestionsBox.hide();
            return;
        }

        // Проверяем, что данные есть
        if (!postMachinesData || (Array.isArray(postMachinesData) ? postMachinesData.length === 0 : Object.keys(postMachinesData).length === 0)) {
            $lockerSuggestionsBox.hide();
            return;
        }

        // Преобразуем данные в массив (работает как с объектом, так и с массивом)
        let postMachinesArray = Array.isArray(postMachinesData) ? postMachinesData : Object.values(postMachinesData);
        const qLocker = (query || '').trim();

        postMachinesArray.forEach(function(postMachine) {
            const addr = postMachine.address || '';
            const matches = qLocker === ''
                || npLabelMatchesQuery(postMachine.name, qLocker)
                || (addr && npLabelMatchesQuery(addr, qLocker));
            if (matches) {
                // У НП name уже містить читабельний опис поштомата; не додаємо address, щоб не дублювати текст.
                let displayName = postMachine.name;
                
                // Экранируем HTML и правильно формируем атрибуты
                let escapedName = postMachine.name.replace(/"/g, '&quot;');
                let escapedDisplayName = displayName.replace(/"/g, '&quot;');
                let city = (postMachine.city || "").replace(/"/g, '&quot;');
                
                suggestionsList += `<li data-id="${postMachine.id}" data-value="${escapedName}" data-city="${city}">
                    ${escapedDisplayName}
                </li>`;
            }
        });

        if (suggestionsList === "") {
            suggestionsList = "<li class='no-results'>Почтомат не знайдено</li>";
        }

        $lockerSuggestionsBox.html(suggestionsList).show();
    }

    // Обработка ввода в поле shipping_address (автозаполнение)
    $("#shipping_address").on("input", function() {
        if (!$("#novaposhta").is(":checked")) {
            return;
        }
        let query = $(this).val().trim();
        updateAddressSuggestions(query); // Обновляем предложения в зависимости от введенного текста
    });

    // Обработка ввода в поле locker_address (автозаполнение)
    $("#locker_address").on("input", function() {
        if (!$("#novaposhta_locker").is(":checked") || checkoutFormHasLongFilm()) {
            return;
        }
        let query = $(this).val().trim();
        updateLockerSuggestions(query); // Обновляем предложения в зависимости от введенного текста
    });

    $(document).on("click", "#address-suggestions li", function(e) {
        e.preventDefault();
        if ($(this).hasClass('no-results')) {
            return;
        }
        let selectedAddress = $(this).data("value");
        let ref = $(this).data("id"); // <--- ВАЖНО
        let city = $(this).data("city") || ""; // Получаем город

        // Формируем адрес с городом: "Город, Название отделения"
        let fullAddress = city ? city + ", " + selectedAddress : selectedAddress;

        // Заполняем поле с адресом (полный адрес с городом)
        $("#shipping_address").val(fullAddress);

        // Записываем ref в скрытое поле
        $("#novaposhta_warehouse_ref").val(ref); // <--- ВОТ ЭТО

        const branchErr = document.querySelector('#branch-fields [data-for="shipping_address"]');
        if (branchErr) {
            branchErr.style.display = 'none';
        }

        // Прячем список предложений
        $("#address-suggestions").hide();
    });

    // Обработчик клика по почтомату
    $(document).on("click", "#locker-suggestions li", function(e) {
        e.preventDefault();
        if ($(this).hasClass('no-results')) {
            return;
        }
        let ref = $(this).data("id");
        let displayText = $(this).text().trim(); // Получаем текст для отображения

        // displayText уже содержит читаемое название, часто с городом/адресом — без дублирования префикса города
        let fullAddress = displayText;

        // Заполняем поле с почтоматом полным названием с городом
        $("#locker_address").val(fullAddress);
        
        // Сохраняем ID почтомата в скрытое поле
        $("#locker_warehouse_ref").val(ref);

        const lockerErr = document.querySelector('#locker-fields [data-for="locker_address"]');
        if (lockerErr) {
            lockerErr.style.display = 'none';
        }

        // Выбираем радио кнопку "locker"
        $("#novaposhta_locker").prop('checked', true);
        
        // Обновляем поля Nova Poshta
        window.updateNovaPoshtaFields();

        // Прячем список предложений после обновления полей
        setTimeout(function() {
            $("#locker-suggestions").hide();
        }, 100);
    });

    // Скрываем список предложений при клике вне поля
    $(document).on("click", function(e) {
        if (!$(e.target).closest("#city-input-wrapper").length) {
            $("#city-suggestions").hide();
        }

        // #address-suggestions — сусід input, не всередині #shipping_address; клік по пункту списку не має закривати до вибору
        if (!$(e.target).closest("#branch-fields").length) {
            $("#address-suggestions").hide();
        }

        if (!$(e.target).closest("#locker-fields").length) {
            $("#locker-suggestions").hide();
        }
    });

    /** Повний список відділень — зручно змінити вибір; фільтр лише під час введення (input). */
    function openBranchSuggestionsFullList() {
        if (!$("#novaposhta").is(":checked")) {
            return;
        }
        if (!(cityRefSelected && branchesData && branchesData.length)) {
            return;
        }
        updateAddressSuggestions("");
        $("#address-suggestions").show();
    }

    $("#shipping_address").on("focus click", function() {
        openBranchSuggestionsFullList();
    });

    function openLockerSuggestionsFullList() {
        if (!$("#novaposhta_locker").is(":checked") || checkoutFormHasLongFilm()) {
            return;
        }
        const pm = postMachinesData;
        const hasData = pm && (Array.isArray(pm) ? pm.length > 0 : Object.keys(pm).length > 0);
        if (!hasData) {
            return;
        }
        updateLockerSuggestions("");
        $("#locker-suggestions").show();
    }

    $("#locker_address").on("focus click", function() {
        openLockerSuggestionsFullList();
    });

    // Слушаем обновления корзины
    window.addEventListener('cartUpdated', function(event) {
        // Обновляем счетчик товаров в корзине (в хедере)
        if (event.detail.cartItemsCount !== undefined) {
            $('#cart-total').html(event.detail.cartItemsCount);
        }
        
        // Обновляем сумму в корзине (в хедере)
        if (event.detail.sum !== undefined) {
            const numberParts = decimalAndIntParts(event.detail.sum);
            $('.cart-mini-bott .total .value').html(numberParts.integer + '<span class="coins">' + numberParts.decimal + '</span>' + ' ₴');
        }
        
        // Если корзина пуста, перенаправляем на главную
        if (event.detail.cartItemsCount === 0) {
            window.location.href = '/';
        }
        
        // Загружаем обновленную корзину для чекаута
        const locale = $("meta[name='locale']").attr("content") || '';
        $.ajax({
            url: (locale ? '/' + locale : '') + '/api/get-checkout-cart',
            method: 'GET',
            beforeSend: function() {
                // Показываем небольшой прелоадер только для обновления корзины
                $('.simplecheckout-cart tbody').html('<tr><td colspan="6" style="text-align: center; padding: 30px; color: ' + (document.body.dataset.theme === 'dark' ? '#FFCE1C' : '#D04B4B') + '; font-weight: 600;">Обновление корзины...</td></tr>');
            },
            success: function (response) {
                $('.simplecheckout-cart tbody').html(response.cartItems);
                $('.simplecheckout-cart-total-value').html(response.sum);

                if (typeof response.has_long_film_rolls === 'boolean') {
                    const rolls = response.has_long_film_rolls;
                    $('#checkoutForm').attr('data-has-long-film', rolls ? '1' : '0');
                    const lockerRadio = document.querySelector('#locker-radio');
                    if (lockerRadio) {
                        lockerRadio.style.display = rolls ? 'none' : '';
                    }
                    if (rolls) {
                        postMachinesData = [];
                        $('#locker_address').val('');
                        $('#locker-suggestions').empty().hide();
                        const branchRadio = document.querySelector('input[name="nova_poshta_type"][value="branch"]');
                        if (branchRadio) {
                            branchRadio.checked = true;
                        }
                        if (typeof window.updateNovaPoshtaFields === 'function') {
                            window.updateNovaPoshtaFields();
                        }
                        if (cityRefSelected) {
                            fetchWarehouses(cityRefSelected);
                        }
                    }
                }
            },
            error: function () {
                console.log('Ошибка при загрузке корзины для чекаута');
                $('.simplecheckout-cart tbody').html('<tr><td colspan="6" style="text-align: center; padding: 20px; color: red;">Ошибка загрузки корзины</td></tr>');
            }
        });
    });
});
