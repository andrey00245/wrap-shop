let productSliderNav = new Swiper(".product-slider-nav", {
  loop: false,
  spaceBetween: 20,
  slidesPerView: 4,
  freeMode: true,
  watchSlidesProgress: true,
  lazy: true,

  breakpoints: {
    1020: {
      slidesPerView: 4,
      spaceBetween: 15
    },
    1332: {
      slidesPerView: 4,
      spaceBetween: 30
    }
  }
});

let productSlider = new Swiper(".product-slider", {
  loop: false,
  navigation: {
    nextEl: ".product-slider .swiper-button-next",
    prevEl: ".product-slider .swiper-button-prev",
  },
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

recalcTotalPrice(quantityInput.val())


quantityPlus.on('click', function (){
  plusMinus(quantityInput, 'plus')
})

quantityMinus.on('click', function (){
  plusMinus(quantityInput, 'minus')
})

quantityInput.on('change', function (){
  plusMinus(quantityInput, 'change')
})

quantityInput.on('input', function (){
  let value = $(this).val();
  let newValue = value.replace(/[^0-9.]/g, '');
  let parts = newValue.split('.');

  if (parts.length > 2) {
    newValue = parts.shift() + '.' + parts.join('');
  }
  $(this).val(newValue);
})


function plusMinus(input, action){
  let currentVal = parseFloat(input.val())
  const step = parseFloat(input.data('step'))
  const maxVal = parseFloat(input.data('max'))
  const minVal = parseFloat(input.data('min'))

  if (action === 'plus')
  {
    let newVal = currentVal + step
    if (maxVal > 0) {
      if (newVal >= maxVal) {
        newVal = maxVal
      }
    }
    else {
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
    }
    else {
      currentVal = 0
    }
    currentVal = integerNumber(currentVal, step)

    recalcTotalPrice(currentVal)
    input.val(decimalPoint(currentVal))
  }
}

function decimalPoint(val){
  if(val%1 === 0){
    return val.toFixed(0)
  }
  else {
    return val.toFixed(1)
  }
}

function integerNumber(currentVal, step) {
  currentVal = Math.floor(currentVal / step) * step
  return currentVal
}

function recalcTotalPrice(newVal){
  for (const key in discounts) {
    if (discounts.hasOwnProperty(key)) {
      const item = discounts[key];
      if(newVal>=item.discountFrom){
        price.text(item.price.toFixed(2))
        totalPrice.text((item.price*newVal).toFixed(2))
        restyleThreeLastChart('.price-wrap .only-price')
        restyleThreeLastChart('.autocalc-product-price .total-price')
      }
    }
  }
}

restyleThreeLastChart('.price-wrap .only-price')

function restyleThreeLastChart(selector) {

  console.log(selector)
  const element = $(selector);
  let text = element.text()
  console.log(text)

  if (text.length > 3) {
    let lastThree = text.slice(-3);
    element.html(text.slice(0, -3) + '<span class="highlight">' + lastThree + '</span>')
  }
}
