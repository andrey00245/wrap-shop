import {productSliderInitialization} from "./sliderInitialization";

$(document).ready(function () {

    var main = new Splide('#product-slider', {
        pagination: false,
        arrows: false,
    });

    var thumbnails = new Splide('#thumbnail-carousel', {
        perPage: 2,
        gap: 10,
        pagination: false,
        isNavigation: true,
    });

    main.sync(thumbnails);
    main.mount();
    thumbnails.mount();

    try {
        var productExampleImages = new Splide('#product-example-slider', {
            pagination: false,
            arrows: true,
            type: 'loop'
        });
        productExampleImages.mount()
    } catch (e) {
        console.log('gallery is empty')
    }


    const quantityInput = $('#quantity-block .fast-order-quantity')
    const quantityPlus = $('#quantity-block #plus-btn')
    const quantityMinus = $('#quantity-block #minus-btn')
    const discounts = $('.item-price').data('discount')
    const price = $('.item-price .only-price')
    const totalPrice = $('.autocalc-product-price .total-price')

    const hiddenParams = $('.params .list .item.hide')
    const showAll = $('#show-all')
    const showLess = $('#show-less')

// discount text
    const textDiscount = $('.text-discount')
    const countForDiscont = $('.text-discount .count-for-discont')
    const savingVal = $('.text-discount .saving-val')

    recalcTotalPrice(quantityInput.val())
    initializationAllLessButtons()


    quantityPlus.on('click', function () {
        plusMinus(quantityInput, 'plus')
    })

    quantityMinus.on('click', function () {
        plusMinus(quantityInput, 'minus')
    })

    quantityInput.on('change', function () {
        plusMinus(quantityInput, 'change')
    })

    quantityInput.on('input', function () {
        let value = $(this).val();
        let newValue = value.replace(/[^0-9.]/g, '');
        let parts = newValue.split('.');

        if (parts.length > 2) {
            newValue = parts.shift() + '.' + parts.join('');
        }
        $(this).val(newValue);
    })

    function plusMinus(input, action) {
        let currentVal = parseFloat(input.val())
        const step = parseFloat(input.data('step'))
        const maxVal = parseFloat(input.data('max'))
        const minVal = parseFloat(input.data('min'))

        if (action === 'plus') {
            let newVal = currentVal + step
            if (maxVal > 0) {
                if (newVal >= maxVal) {
                    newVal = maxVal
                }
            } else {
                newVal = 0
            }

            recalcTotalPrice(newVal)
            input.val(decimalPoint(newVal))
        }

        if (action === 'minus') {
            let newVal = currentVal - step
            if (maxVal > 0) {
                if (newVal <= minVal) {
                    newVal = minVal
                }
            } else {
                newVal = 0
            }

            recalcTotalPrice(newVal)
            input.val(decimalPoint(newVal))
        }

        if (action === 'change') {
            if (maxVal > 0) {
                if (isNaN(currentVal)) {
                    currentVal = minVal
                }
                if (currentVal >= maxVal) {
                    currentVal = maxVal
                } else if (currentVal <= minVal) {
                    currentVal = minVal
                }
            } else {
                currentVal = 0
            }
            currentVal = integerNumber(currentVal, step)

            recalcTotalPrice(currentVal)
            input.val(decimalPoint(currentVal))
        }
        onOffSelectedQuantity()
    }

    function decimalPoint(val) {
        if (val % 1 === 0) {
            return val.toFixed(0)
        } else {
            return val.toFixed(1)
        }
    }

    function integerNumber(currentVal, step) {
        currentVal = Math.round(currentVal / step) * step
        if (isNaN(currentVal)) {
            currentVal = 0;
        }
        return currentVal
    }

    function recalcTotalPrice(newVal) {
        // Нормализуем в массив в правильном порядке по ключам 0,1,2...
        const tiers = Object.keys(discounts)
            .sort((a, b) => parseInt(a) - parseInt(b))
            .map(k => discounts[k]);

        if (!Array.isArray(tiers) || tiers.length === 0) {
            textDiscount.hide();
            return;
        }

        // Находим текущий активный уровень (максимальный discountFrom <= newVal)
        let currentIdx = 0;
        for (let i = 0; i < tiers.length; i++) {
            if (newVal >= parseFloat(tiers[i].discountFrom)) {
                currentIdx = i;
            }
        }
        const currentTier = tiers[currentIdx];
        const nextTier = tiers[currentIdx + 1];

        // Обновляем цену за ед. и сумму по текущему уровню
        const unitPrice = parseFloat(currentTier.price);
        price.text(unitPrice.toFixed(2));
        $('.autocalc-product-price .total-price').text((unitPrice * newVal).toFixed(2));
        restyleThreeLastChart('.price-wrap .only-price');
        restyleThreeLastChart('.autocalc-product-price .total-price-main');

        // Обновляем текст о следующей скидке, если она реально существует и даёт экономию
        if (nextTier) {
            const threshold = parseFloat(nextTier.discountFrom);
            const nextPrice = parseFloat(nextTier.price);
            const maxVal = parseFloat(quantityInput.data('max'));
            const left = threshold - newVal;
            
            // Если уже достигли/перешли порог — сразу скрываем сообщение
            if (left <= 1e-9) {
                textDiscount.hide();
                return;
            }
            
            // Если порог скидки недостижим (больше максимального количества) — не показываем
            if (maxVal > 0 && threshold > maxVal) {
                textDiscount.hide();
                return;
            }
            
            const potentialSaving = threshold * (unitPrice - nextPrice);

            if (potentialSaving > 0) {
                // показываем только если есть реальная экономия
                const leftDisplay = (left % 1 === 0) ? left.toFixed(0) : left.toFixed(1);
                countForDiscont.text(leftDisplay);
                savingVal.text(potentialSaving.toFixed(2));
                textDiscount.show();
            } else {
                textDiscount.hide();
            }
        } else {
            // Следующего уровня нет — скрываем
            textDiscount.hide();
        }
    }

    restyleThreeLastChart('.price-wrap .only-price')

    function restyleThreeLastChart(selector) {
        const element = $(selector);
        let text = element.text()
        if (text.length > 3) {
            let lastThree = text.slice(-3);
            element.html(text.slice(0, -3) + '<span class="highlight">' + lastThree + '</span>')
        }
    }

    function onOffSelectedQuantity() {
        choseQuantity.each(function (index) {
            $(this).removeClass('is-active')
            $(this).text($(this).data('choose'))

            if (choseQuantity[index + 1] !== undefined) {
                if (parseFloat(choseQuantity[index].getAttribute('data-quantity')) <= parseFloat(quantityInput.val())
                    && parseFloat(choseQuantity[index + 1].getAttribute('data-quantity')) > parseFloat(quantityInput.val())) {
                    $(this).addClass('is-active')
                    $(this).text($(this).data('chosen'))
                }
            } else {
                if (parseFloat(choseQuantity[index].getAttribute('data-quantity')) <= parseFloat(quantityInput.val())) {
                    $(this).addClass('is-active')
                    $(this).text($(this).data('chosen'))
                }
            }
        })
    }

//show hide params and buttons
    function initializationAllLessButtons() {
        if (hiddenParams.length !== 0) {
            showAll.show()
        }
    }

    function showAllParameters() {
        hiddenParams.each(function (index) {
            $(this).removeClass('hide')
        });
    }

    function hideAllParameters() {
        hiddenParams.each(function (index) {
            $(this).addClass('hide')
        });
    }

    showAll.on('click', function () {
        showAllParameters()
        $(this).hide()
        showLess.show()
    })

    showLess.on('click', function () {
        hideAllParameters()
        $(this).hide()
        showAll.show()
    })


// chose max value and click select button
    const choseMax = $('.max-value-group .select-max')
    const choseQuantity = $('.product-discounts .select-quantity')

    choseMax.on('click', function () {
        quantityInput.val($(this).data('quantity'))
        quantityInput.trigger('change');
    })


    choseQuantity.on('click', function () {
        quantityInput.val($(this).data('quantity'))
        quantityInput.trigger('change');
    })

    productSliderInitialization('homeLatest');
});
