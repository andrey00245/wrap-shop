import './wishlist'
import './subscription'
import {ru, uk, en} from '/third-party/intlTelInput/js/i18n'
import {imageSliderInProduct, popupSliderInitialization} from "./sliderInitialization";
import {language} from './variables'

$(document).ready(function () {
    showHideSubCategories();
    popupSliderInitialization('cartProductSlider')
    imageSliderInProduct('home-products-item');

    let scroll = $(window).scrollTop();
    slideHeader(scroll);

    $(window).scroll(function (event) {
        scroll = $(window).scrollTop();
        slideHeader(scroll);
    });

    function slideHeader(scroll) {
        if (scroll > 100) {
            $('.fixed-header').addClass('show');
            $('.popup-right').addClass('fixed-header-active');
            $('.head-top').addClass('fixed-header-active');

        } else {
            $('.fixed-header').removeClass('show');
            $('.popup-right').removeClass('fixed-header-active');
            $('.head-top').removeClass('fixed-header-active');

        }
    }

    $(".head-catalog-open, .head-catalog-bg").on("click", function () {
        $('.head-catalog').slideToggle();
        $(".head-catalog-open, .head-catalog-bg").toggleClass("active");
    });
    $(".mobil-catalog-open, .head-catalog-close").on("click", function () {
        $('.head-catalog').toggleClass("active");
    });

    $(".child-popup-open.open").on("click", function () {
        if($(this).hasClass('active')){
            $(".child-popup-open.open").removeClass('active').siblings(".child-popup").slideUp();
        }
        else{
            $(".child-popup-open.open").removeClass('active').siblings(".child-popup").slideUp();
            $(this).addClass("active").siblings(".child-popup").slideToggle();
        }
    });

    $("#restore-popup-back").on("click", function () {
        $(this).closest('#login-popup').attr('data-step', '1')
        $(this).closest('.popup-form').find('.clear-text').text("");
    })

    $("#login-popup-back").on("click", function () {
        $(this).closest('#login-popup').attr('data-step', '1')
    })

    $(".notify-available-btn").on("click", function () {
        $('#button-submit-report-availability').attr('data-product-id', $(this).data('product-id'));
    })

    $("#button-restore-password").click(function () {
        $(this).closest('#login-popup').attr('data-step', '3')
    })

    let seoContentHeight = 104;
    let seoContent = $('.seo-content');
    seoContent.css("height", "auto");
    let outerContentsHeight = seoContent.height();
    seoContent.css("height", seoContentHeight + "px");

    $(window).on('resize', function () {
        seoContent.css("height", "auto");
        outerContentsHeight = seoContent.height();
        if (seoContent.hasClass('open')) {
            seoContent.css('height', outerContentsHeight + 'px')
        } else {
            seoContent.css('height', seoContentHeight + 'px')
        }
    })

    $('.seo-btn.show').click(function () {
        $('.seo-btn.show').hide();
        $('.seo-btn.collapse').show()
        seoContent.toggleClass('open');
        showCollapseSeoContent()
    })

    $('.seo-btn.collapse').click(function () {
        $('.seo-btn.show').show();
        $('.seo-btn.collapse').hide()
        seoContent.toggleClass('open');
        showCollapseSeoContent()
    })

    function showCollapseSeoContent() {
        if (seoContent.hasClass('open')) {
            seoContent.css('height', outerContentsHeight + 'px')
        } else {
            seoContent.css('height', seoContentHeight + 'px')
        }
    }

    getUserData(telInputInitialization)
    popupSearchAction()
})

function getUserData(callback) {
    let dataToSession = (callback) => {
        if (sessionStorage.userData === undefined) {
            fetch("https://ipapi.co/json")
                .then(res => {
                    return res.json();
                })
                .then(data => {
                    sessionStorage.setItem('userData', JSON.stringify(data));
                    callback(data);
                })
                .catch((error) => {
                    sessionStorage.setItem('userData', JSON.stringify({country_code: "UA"}));
                    callback({country_code: "UA"});
                });
        } else {
            const userData = sessionStorage.userData;
            callback(userData);
        }
    }

    dataToSession(callback);
}

function telInputInitialization() {
    let i18nFile;

    if (language === 'ru') {
        i18nFile = ru
    }
    if (language === 'uk') {
        i18nFile = uk
    }
    if (language === 'en') {
        i18nFile = en
    }

    const userData = JSON.parse(sessionStorage.getItem('userData'));

    const inputs = {
        0: document.querySelector("#phone_account"),
        1: document.querySelector("#fast_order_phone"),
        2: document.querySelector("#input-telephone"),
        3: document.querySelector("#phone_consultation"),
        4: document.querySelector("#phone_checkout"),
        5: document.querySelector("#report_order_phone"),
    }

    const iti = {};

    for (let key in inputs) {
        if (inputs.hasOwnProperty(key)) {
            if (inputs[key] !== null && inputs[key] !== undefined) {
                iti[key] = window.intlTelInput(inputs[key], {
                    initialCountry: userData.country_code,
                    hiddenInput: () => ({phone: "phone"}),
                    strictMode: true,
                    separateDialCode: true,
                    i18n: i18nFile,
                    loadUtilsOnInit: "/third-party/intlTelInput/js/utils.js"
                })
            }
        }
    }
}


$(document).ready(function () {
    popupsOpenClose()
    $('#popup-consultation').on('submit', function (e) {
        e.preventDefault()
        var name = $('#name').val().trim();
        var phone = $(this).find('input[type="hidden"][name="phone"]').val().trim();
        var email = $('#email').val().trim();
        var comment = $('#comment').val().trim();
        var productId = $('#consult-popup-product-id').val();

        var formData = {
            'name': name,
            'phone': phone,
            'email': email,
            'comment': comment,
            'product_id': productId
        };

        $.ajax({
            url: '/consultation',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    $('#consult-popup').attr('data-step', 3);
                } else {
                    $('.error-message-consultation').text(response.message).show();
                }
            },
            error: function (xhr, status, error) {
                $('.error-message-consultation').empty().show();

                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    for (var field in errors) {
                        var errorMessage = errors[field].join('<br>');
                        var errorElement = '<div class="error-item"><span>' + errorMessage + '</span></div>';
                        $('.error-message-consultation').append(errorElement);
                    }
                } else {
                    $('.error-message-consultation').text('Произошла ошибка. Попробуйте снова.').show();
                }
            }
        });
    });

    $('#fast-order-form').on('submit', function (e) {
        e.preventDefault();

        let formData = {
            product_id: $('#fast-order-product-id').val(),
            quantity: $('#input-quantity').val(),
            name: $('#fast_order_name').val(),
            phone: $(this).find('input[name="phone"][type="hidden"]').val(),
            email: $('#fast_order_email').val(),
            comment: $('#fast_order_comment').val(),
            total_price: $('#fast-order-popup .total-price').text(),
        };

        $.ajax({
            url: '/fast-order',
            method: 'POST',
            data: formData,
            success: function (response) {
                if (response.status === 'success') {
                    $('#fast-order-popup').attr('data-step', 3);
                }
            },
            error: function (xhr, status, error) {
                $('.error-message-fast-order').empty().show();

                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    for (var field in errors) {
                        var errorMessage = errors[field].join('<br>');
                        var errorElement = '<div class="error-item"><span>' + errorMessage + '</span></div>';
                        $('.error-message-fast-order').append(errorElement);
                    }
                }
            }
        });
    });

    $('#report-availability-form').on('submit', function (e) {
        e.preventDefault();

        let formData = {
            product_id: $(this).find('#button-submit-report-availability').data('product-id'),
            name: $('#report_order_name').val(),
            phone: $(this).find('input[name="phone"][type="hidden"]').val(),
            email: $('#report_order_email').val(),
        };

        $.ajax({
            url: '/report-availability',
            method: 'POST',
            data: formData,
            success: function (response) {
                if (response.status === 'success') {
                    $('#report-availability-popup').attr('data-step', 3)
                    $('#report_order_name').val('');
                    $('#report_order_phone').val('');
                    $('#report_order_email').val('');
                }
            },
            error: function (xhr, status, error) {
                $('.error-message-report-order').empty().show();

                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    for (var field in errors) {
                        var errorMessage = errors[field].join('<br>');
                        var errorElement = '<div class="error-item"><span>' + errorMessage + '</span></div>';
                        $('.error-message-report-order').append(errorElement);
                    }
                }
            }
        });
    });

});


function showHideSubCategories() {
    let openSubMenu = $('.openSubMenu')
    let mainMenu = $('.head-catalog-container.mainMenu')
    let subMenu = $('.head-catalog-container.subMenu')
    let backToMainCategory = $('.head-catalog-container .back_to_main_category')

    backToMainCategory.on('click', function () {
        let currentCutegoryId = $(this).data('category-id')
        subMenu.each(function () {
            if ($(this).data('modal-category') === currentCutegoryId) {
                $(this).removeClass('show')
            }
        })
        mainMenu.addClass('show')
    })

    openSubMenu.on('click', function () {
        let currentCutegoryId = $(this).data('category-id')
        mainMenu.removeClass('show')
        subMenu.each(function () {
            if ($(this).data('modal-category') === currentCutegoryId) {
                $(this).addClass('show')
            }
        })
    })
}


function popupSearchAction(){
    const searchField = document.querySelector('#search-popup #search input[name="search-popup"]');
    const searchButton = document.querySelector('#search-popup #search button');
    const dropdownMenu = document.querySelector('#search-popup #search .dropdown-menu');
    let searchLink = '/search';
    if(language !== 'uk'){
        searchLink = `/${language}/search`;
    }

    document.addEventListener('click', function (e){
        if(!e.target.closest('.dropdown-menu') && !e.target.closest('input[name="search-popup"]')){
            dropdownMenu.style.display = 'none';
        }
    })

    searchField.addEventListener('click', function (){
        if(searchField.value.length>=3){
            dropdownMenu.style.display = 'block';
        }
    })

    searchButton.addEventListener('click', function (){
        if(searchField.value.trim().length !== 0){
            searchLink = '/search?search='+searchField.value.trim();
            if(language !== 'uk'){
                searchLink = `/${language}/search?search=${searchField.value.trim()}`;
            }
        }
        window.location.href = searchLink;
    })

    const debouncedSearch = debounce(popupSearchHandler.bind(null, dropdownMenu), 500);
    searchField.addEventListener('input', debouncedSearch)
}

function popupSearchHandler(dropdownMenu, event) {
    const query = event.target.value.trim();
    let url = '/get-search-items';
    if(language !== 'uk'){
        let url = `/${language}/get-search-items`;
    }

    if(query.length >= 3){
        $.ajax({
            url: url,
            type: 'POST',
            data: {search: query},
            dataType: 'json',
            success: function (response) {
                dropdownMenu.innerHTML = response.data.view;
                if(response.data.total_count === 0){
                    dropdownMenu.style.display = 'none';
                }
                else{
                    dropdownMenu.style.display = 'block';
                }
            },
            error: function (xhr, status, error) {

            }
        });
    }
    else {
        dropdownMenu.style.display = 'none';
    }
}

function popupsOpenClose(){
    const popupsBtns = document.querySelectorAll('.general-popup-btn')
    const closeBtns = document.querySelectorAll('.general-popup .popup-close')
    const popups = document.querySelectorAll('.general-popup');

    popupsBtns.forEach(function (el, index){
        popupsBtns[index].addEventListener('click', function (){
            closeAllPopups(popups)
            const target = popupsBtns[index].getAttribute('data-popup')
            document.getElementById(target).classList.add('active');
        })
    })

    closeBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            btn.closest('.general-popup').classList.remove('active');
            btn.closest('.general-popup').setAttribute('data-step', '1')
            clearText()
        });
    });
}

function clearText(){
    document.querySelectorAll('.clear-text').forEach(function (el){
        el.innerText = "";
    })
}

function closeAllPopups(popups) {
    clearText()
    popups.forEach(popup  =>  {
        popup.classList.remove('active')
        popup.setAttribute('data-step', '1')
    });
}

function debounce(fn, delay) {
    let timeoutId;
    return function (...args) {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => {
            fn.apply(this, args);
        }, delay);
    };
}
