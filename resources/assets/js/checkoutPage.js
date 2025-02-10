let shippingMethod = document.querySelector('#simplecheckout_shipping');
let shippingMethodAddress = document.querySelector('#simplecheckout_shipping_address');
let novaPoshtaDesc = document.querySelector('#novaposhta_desc');
let cityField = document.querySelector('#city');  // Поле города
let shippingAddressField = document.querySelector('#shipping_address');  // Поле адреса доставки

let inputsOpen = document.querySelectorAll('.simplecheckout-cart-total .inputs-open.button');

// Убираем описание Новой Почты при инициализации
if (novaPoshtaDesc) {
    novaPoshtaDesc.remove();
}

// Устанавливаем метод доставки "pickup" как выбранный по умолчанию, если это первый заход
if (!shippingMethod.querySelector('input:checked')) {
    shippingMethod.querySelector('#pickup').checked = true;
}

// Проверка для отображения/скрытия полей city и shipping_address
function updateAddressFields() {
    // Получаем выбранный метод доставки
    const selectedMethod = shippingMethod.querySelector('input:checked');

    // Если выбран метод "pickup", скрываем поле адреса
    if (selectedMethod && selectedMethod.id === 'pickup') {
        shippingMethodAddress.style.display = 'none';
        cityField.required = false;
        shippingAddressField.required = false;
    } else {
        shippingMethodAddress.style.display = '';
        cityField.required = true;
        shippingAddressField.required = true;
    }

    // Для методов "novaposhta" или "novaposhta_doors" отображаем описание Новой Почты
    if (selectedMethod && (selectedMethod.id === 'novaposhta' || selectedMethod.id === 'novaposhta_doors')) {
        if (novaPoshtaDesc && selectedMethod) {
            selectedMethod.parentNode.parentNode.insertAdjacentElement('afterend', novaPoshtaDesc);
        }
    } else {
        if (novaPoshtaDesc) {
            novaPoshtaDesc.remove();
        }
    }
}

// Слушаем изменение метода доставки
shippingMethod.addEventListener('change', function () {
    updateAddressFields();
});

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

updateAddressFields();

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

document.addEventListener('DOMContentLoaded', function () {
    restorePaymentMethod();
});

