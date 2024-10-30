import {imageSliderInProduct, productSliderInitialization} from "./sliderInitialization";


let productSliderNav = new Swiper(".product-slider-nav", {
  loop: false,
  spaceBetween: 10,
  slidesPerView: 2,
  freeMode: true,
  watchSlidesProgress: true,
  lazy: true,
  navigation: {
    nextEl: ".nav-slider-wrapper .swiper-button-next",
    prevEl: ".nav-slider-wrapper .swiper-button-prev",
  },

  breakpoints: {
    1020: {
      slidesPerView: 2,
      spaceBetween: 10
    },
    1332: {
      slidesPerView: 2,
      spaceBetween: 10
    }
  }
});

let productSlider = new Swiper(".product-slider", {
  loop: false,
  thumbs: {
    swiper: productSliderNav,
  },
  lazy: true,
});

const quantityInput = $('#quantity-block input[name="quantity"]')
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
}

function decimalPoint(val) {
  if (val % 1 === 0) {
    return val.toFixed(0)
  } else {
    return val.toFixed(1)
  }
}

function integerNumber(currentVal, step) {
  currentVal = Math.round(currentVal/step) * step
  return currentVal
}

function recalcTotalPrice(newVal) {
  for (const key in discounts) {
    if (discounts.hasOwnProperty(key)) {
      const item = discounts[key];
      if (key != 0) {
        const prevItem = discounts[key - 1];
        if (newVal < item.discountFrom && newVal >= prevItem.discountFrom) {
          countForDiscont.text((item.discountFrom - newVal).toFixed(1))
          savingVal.text(item.discountFrom*prevItem.price - item.discountFrom*item.price)
        }
        if(newVal >= item.discountFrom){
          textDiscount.hide()
        }
        else{
          textDiscount.show()

        }
      }
      if (newVal >= item.discountFrom) {
        price.text(item.price.toFixed(2))
        totalPrice.text((item.price * newVal).toFixed(2))
        restyleThreeLastChart('.price-wrap .only-price')
        restyleThreeLastChart('.autocalc-product-price .total-price')
      }
    }
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

choseQuantity.each(function (){
  $(this).removeClass('disabled')

  $(this).on('click', function () {
    $(this).addClass('disabled')
    quantityInput.val($(this).data('quantity'))
    quantityInput.trigger('change');
  })
})

productSliderInitialization('homeLatest')
imageSliderInProduct('home-products-item');

