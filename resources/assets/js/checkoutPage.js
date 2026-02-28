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
                        // Показываем сообщение об ошибке для поля отделения
                        const branchErrorMsg = document.querySelector('[data-for="shipping_address_address_1"]');
                        if (branchErrorMsg) {
                            branchErrorMsg.style.display = 'block';
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
                    console.log('Locker case selected, hasLongFilm:', hasLongFilm);
                    if (!hasLongFilm) {
                        if (cityField) cityField.disabled = false;
                        if (lockerFields) {
                            console.log('Showing locker fields');
                            lockerFields.style.display = 'block';
                            const lockerAddressField = document.querySelector('#locker_address');
                            if (lockerAddressField) {
                                lockerAddressField.disabled = false;
                                lockerAddressField.required = true;
                            }
                            // Показываем сообщение об ошибке для поля почтомата
                            const lockerErrorMsg = document.querySelector('[data-for="locker_address"]');
                            if (lockerErrorMsg) {
                                lockerErrorMsg.style.display = 'block';
                            }
                        }
                        // Убираем required с поля отделения и скрываем его сообщение об ошибке
                        const shippingAddressField = document.querySelector('#shipping_address');
                        if (shippingAddressField) {
                            shippingAddressField.required = false;
                        }
                        const branchErrorMsg = document.querySelector('[data-for="shipping_address_address_1"]');
                        if (branchErrorMsg) {
                            branchErrorMsg.style.display = 'none';
                        }
                        
                        // Сбрасываем заголовок для почтомата
                        const subtitle = document.querySelector('#nova-poshta-subtitle');
                        if (subtitle) {
                            subtitle.textContent = 'Адреса доставки';
                        }
                    } else {
                        console.log('Long film detected, switching to branch');
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
    // Флаг: содержит ли корзина пленки (м.п.) от 1 м
    const hasLongFilm = document.getElementById('checkoutForm')?.dataset?.hasLongFilm === '1';

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
        $("#address-suggestions").empty();
        $("#shipping_address").val('');
        $("#locker_address").val('');
        let selectedCity = $(this).data("value");
        let cityRef = $(this).data("id"); // Сохраняем идентификатор города

        // Заполняем поле города
        $("#city").val(selectedCity);

        // Запоминаем выбранный cityRef
        cityRefSelected = cityRef;

        // Теперь запрашиваем отделения и почтоматы для выбранного города
        if (cityRefSelected) {
            fetchWarehouses(cityRefSelected);
            fetchPostMachines(cityRefSelected);
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

    // Функция для получения почтоматов по выбранному городу
    function fetchPostMachines(cityRef) {
        let locale = $("meta[name='locale']").attr("content");
        console.log('Fetching post machines for cityRef:', cityRef);
        $.ajax({
            url: '/'+locale+"/api/get-postmachines", // API для получения почтоматов
            type: "GET",
            data: { cityRef: cityRef },
            success: function(response) {
                console.log('Post machines response:', response);
                let $lockerAddressInput = $("#locker_address");
                let $lockerSuggestionsBox = $("#locker-suggestions");

                // Сохраняем данные о полученных почтоматах
                postMachinesData = response.data || [];
                console.log('Post machines data type:', typeof postMachinesData);
                console.log('Post machines data length:', Object.keys(postMachinesData).length);
                console.log('First post machine:', Object.values(postMachinesData)[0]);

                // Показываем поле для ввода почтомата
                $lockerAddressInput.prop("disabled", false);

                // Заполняем список предложений
                updateLockerSuggestions("");
            },
            error: function(xhr, status, error) {
                console.log('Помилка при отриманні почтоматів:', error);
                console.log('Response:', xhr.responseText);
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
        console.log('updateLockerSuggestions called with query:', query);
        console.log('postMachinesData:', postMachinesData);
        let $lockerSuggestionsBox = $("#locker-suggestions");
        console.log('locker suggestions box found:', $lockerSuggestionsBox.length);
        let suggestionsList = "";

        // Проверяем, что данные есть
        if (!postMachinesData || (Array.isArray(postMachinesData) ? postMachinesData.length === 0 : Object.keys(postMachinesData).length === 0)) {
            console.log('No post machines data available');
            $lockerSuggestionsBox.hide();
            return;
        }

        // Преобразуем данные в массив (работает как с объектом, так и с массивом)
        let postMachinesArray = Array.isArray(postMachinesData) ? postMachinesData : Object.values(postMachinesData);
        
        postMachinesArray.forEach(function(postMachine) {
            console.log('Checking post machine:', postMachine.name, 'against query:', query);
            // Если запрос пустой, показываем все почтоматы
            if (query === "" || postMachine.name.toLowerCase().indexOf(query.toLowerCase()) !== -1) {
                // Создаем более читаемое название
                let displayName = postMachine.name;
                if (postMachine.address) {
                    displayName = `${postMachine.name} (${postMachine.address})`;
                }
                
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

        console.log('Generated suggestions HTML:', suggestionsList);
        // Показываем предложения
        $lockerSuggestionsBox.html(suggestionsList).show();
        console.log('Suggestions box should be visible now');
        console.log('Suggestions box display:', $lockerSuggestionsBox.css('display'));
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
        console.log('Locker input event triggered');
        console.log('novaposhta_locker checked:', $("#novaposhta_locker").is(":checked"));
        if (!$("#novaposhta_locker").is(":checked")) {
            console.log('Locker input: novaposhta_locker not checked, returning');
            return;
        }
        let query = $(this).val().trim();
        console.log('Locker input query:', query);
        updateLockerSuggestions(query); // Обновляем предложения в зависимости от введенного текста
    });

    $(document).on("click", "#address-suggestions li", function(e) {
        e.preventDefault();
        let selectedAddress = $(this).data("value");
        let ref = $(this).data("id"); // <--- ВАЖНО
        let city = $(this).data("city") || ""; // Получаем город

        // Формируем адрес с городом: "Город, Название отделения"
        let fullAddress = city ? city + ", " + selectedAddress : selectedAddress;

        // Заполняем поле с адресом (полный адрес с городом)
        $("#shipping_address").val(fullAddress);

        // Записываем ref в скрытое поле
        $("#novaposhta_warehouse_ref").val(ref); // <--- ВОТ ЭТО

        console.log(ref, city);
        // Прячем список предложений
        $("#address-suggestions").hide();
    });

    // Обработчик клика по почтомату
    $(document).on("click", "#locker-suggestions li", function(e) {
        e.preventDefault();
        let selectedLocker = $(this).data("value");
        let ref = $(this).data("id");
        let city = $(this).data("city") || "";
        let displayText = $(this).text().trim(); // Получаем текст для отображения

        // Формируем адрес с городом: "Город, Название почтомата"
        let fullAddress = city ? city + ", " + displayText : displayText;

        // Заполняем поле с почтоматом полным названием с городом
        $("#locker_address").val(fullAddress);
        
        // Сохраняем ID почтомата в скрытое поле
        $("#locker_warehouse_ref").val(ref);
        
        // Выбираем радио кнопку "locker"
        $("#novaposhta_locker").prop('checked', true);
        
        // Обновляем поля Nova Poshta
        window.updateNovaPoshtaFields();

        console.log('Selected locker:', ref, 'City:', city, 'Display text:', displayText);
        console.log('Locker warehouse ref field value:', $("#locker_warehouse_ref").val());
        
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

        if (!$(e.target).closest("#shipping_address").length) {
            $("#address-suggestions").hide();
        }

        if (!$(e.target).closest("#locker_address").length) {
            $("#locker-suggestions").hide();
        }
    });

    // Показывать предложения при фокусе на поле shipping_address
    $("#shipping_address").on("focus", function() {
        if ($(this).val().length >= 2) {
            $("#address-suggestions").show();
        }
    });

    // Показывать предложения при фокусе на поле locker_address
    $("#locker_address").on("focus", function() {
        if ($("#novaposhta_locker").is(":checked")) {
            // Показываем все почтоматы при фокусе (как для отделений)
            updateLockerSuggestions($(this).val());
        }
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
        $.ajax({
            url: '/api/get-checkout-cart',
            method: 'GET',
            beforeSend: function() {
                // Показываем небольшой прелоадер только для обновления корзины
                $('.simplecheckout-cart tbody').html('<tr><td colspan="6" style="text-align: center; padding: 30px; color: ' + (document.body.dataset.theme === 'dark' ? '#FFCE1C' : '#D04B4B') + '; font-weight: 600;">Обновление корзины...</td></tr>');
            },
            success: function (response) {
                // Обновляем таблицу товаров в чекауте
                $('.simplecheckout-cart tbody').html(response.cartItems);
                
                // Обновляем общую сумму в чекауте
                $('.simplecheckout-cart-total-value').html(response.sum);
            },
            error: function () {
                console.log('Ошибка при загрузке корзины для чекаута');
                $('.simplecheckout-cart tbody').html('<tr><td colspan="6" style="text-align: center; padding: 20px; color: red;">Ошибка загрузки корзины</td></tr>');
            }
        });
    });
});
