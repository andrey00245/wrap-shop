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

        // Если выбран метод "my_addresses", показываем select для города
        if (selectedMethod && selectedMethod.id === 'my_addresses' && myAddressesOption) {
            // Очистка значения поля для адреса
            if (shippingAddressField) {
                shippingAddressField.value = '';
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
    let cartSlider = new Swiper("#checkoutProductSlider .home-products-list", {
        navigation: {
            nextEl: "#checkoutProductSlider .home-slide-button .swiper-button-next",
            prevEl: "#checkoutProductSlider .home-slide-button .swiper-button-prev",
        },
        spaceBetween: 10,
        slidesPerView: 2.1,
        lazy: true,
        observeSlideChildren: true,
        observeParents: true,
        observer: true,
        breakpoints: {
            1331: {
                slidesPerView: 2.1,
                spaceBetween: 10,
            },
            1020: {
                slidesPerView: 1.2,
                spaceBetween: 10,
            },
            480: {
                slidesPerView: 2.1,
                spaceBetween: 10,
            },
            280: {
                slidesPerView: 1.1,
                spaceBetween: 10,
            },
        }
    });

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
        console.log(11);
        const cityInput = document.querySelector('input[name="city"]');
        const citySelect = document.querySelector('select[name="city_select"]');

        if (citySelect && citySelect.style.display === 'block') {
            // Если выбран метод "Мої адреси" и отображается select, передаем значение select в input
            cityInput.value = citySelect.options[citySelect.selectedIndex].value; // Записываем в поле "city" значение выбранного города
        }
    });
});
