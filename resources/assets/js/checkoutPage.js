// import {
//   productSliderInitialization,
//   imageSliderInProduct} from "./sliderInitialization";
//
// $(document).ready(function () {
//   productSliderInitialization('homeLatest');
//   imageSliderInProduct('home-products-item');
// });


let shippingMethod = document.querySelector('#simplecheckout_shipping')
let shippingMethodAddress = document.querySelector('#simplecheckout_shipping_address')
let novaPoshtaDesc = document.querySelector('#novaposhta_desc')

let inputsOpen = document.querySelectorAll('.simplecheckout-cart-total .inputs-open.button')

novaPoshtaDesc.remove()

shippingMethod.addEventListener('change', function (el) {
  novaPoshtaDesc.remove()

  if (this.querySelector('#pickup').checked) {
    shippingMethodAddress.style.display = 'none';
  } else {
    shippingMethodAddress.style.display = '';
  }
  if (this.querySelector('#novaposhta').checked) {
    this.querySelector('#novaposhta').parentNode.parentNode.insertAdjacentElement('afterend', novaPoshtaDesc);
  }
  if (this.querySelector('#novaposhta_doors').checked) {
    this.querySelector('#novaposhta_doors').parentNode.parentNode.insertAdjacentElement('afterend', novaPoshtaDesc);
  }
})

inputsOpen.forEach(function (el) {
  el.addEventListener('click', function () {
    el.parentElement.querySelector('.inputs.form-horizontal').classList.toggle('active')
  })
})



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
