import './wishlist'
import './subscription'
import {ru, uk, en} from '/third-party/intlTelInput/js/i18n'
import {imageSliderInProduct, imageSliderInDefaultProduct, popupSliderInitialization} from "./sliderInitialization";
import {language} from './variables'

$(document).ready(function () {
    showHideSubCategories();
    popupSliderInitialization('cartProductSlider')
    imageSliderInProduct('home-products-item');
    imageSliderInDefaultProduct('product-default__splide');

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

    $('.head-top-close, .head-top-open').on('click', function () {
        menuPopup()
    });

    function cartPopup(e) {
        e.preventDefault()
        $('#cart-popup').toggleClass('active');
        $('#simple-popup').removeClass('active');
        $('#search-popup').removeClass('active');
        $('#login-popup').removeClass('active');
        $('#fast-order-popup').removeClass('active');
        $('#report-availability-popup').removeClass('active');
        $('.head-top').removeClass('active');
        if ($('#cart-popup').hasClass('active')) {
            $("html").addClass('no-overflow');
        } else {
            $("html").removeClass('no-overflow');
        }
    }

    function searchPopup() {
        $('#search-popup').toggleClass('active');
        $('#simple-popup').removeClass('active');
        $('#cart-popup').removeClass('active');
        $('#report-availability-popup').removeClass('active');
        $('#login-popup').removeClass('active');
        $('.head-top').removeClass('active');
    }

    function loginPopup() {
        $('#login-popup').toggleClass('active');
        $('#search-popup').removeClass('active');
        $('#simple-popup').removeClass('active');
        $('#report-availability-popup').removeClass('active');
        $('#cart-popup').removeClass('active');
        $('.head-top').removeClass('active');
    }

    function consultPopup() {
        $('#consult-popup').toggleClass('active');
        $('#search-popup').removeClass('active');
        $('#simple-popup').removeClass('active');
        $('#report-availability-popup').removeClass('active');
        $('#cart-popup').removeClass('active');
        $('#fast-order-popup').removeClass('active');
        $('.head-top').removeClass('active');
    }

    function fastOrderPopup() {
        $('#fast-order-popup').toggleClass('active');
        $('#consult-popup').removeClass('active');
        $('#report-availability-popup').removeClass('active');
        $('#search-popup').removeClass('active');
        $('#simple-popup').removeClass('active');
        $('#cart-popup').removeClass('active');
        $('.head-top').removeClass('active');
    }

    function reportAvailabilityPopup() {
        $('#report-availability-popup').toggleClass('active');
        $('#fast-order-popup').removeClass('active');
        $('#consult-popup').removeClass('active');
        $('#search-popup').removeClass('active');
        $('#simple-popup').removeClass('active');
        $('#cart-popup').removeClass('active');
        $('.head-top').removeClass('active');
    }

    function reviewPopup() {
        $('#review-popup').toggleClass('active');
        $('#report-availability-popup').removeClass('active');
        $('#fast-order-popup').removeClass('active');
        $('#consult-popup').removeClass('active');
        $('#search-popup').removeClass('active');
        $('#simple-popup').removeClass('active');
        $('#cart-popup').removeClass('active');
        $('.head-top').removeClass('active');
    }

    function menuPopup() {
        $('.head-top').toggleClass('active');
        $('#login-popup').removeClass('active');
        $('#search-popup').removeClass('active');
        $('#simple-popup').removeClass('active');
        $('#report-availability-popup').removeClass('active');
        $('#cart-popup').removeClass('active');
        $('#fast-order-popup').removeClass('active');

        if ($('.head-top').hasClass('active')) {
            $("html").addClass('no-overflow');
        } else {
            $("html").removeClass('no-overflow');
        }
    }

    $(".child-popup-open.open").on("click", function () {
        if($(this).hasClass('active')){
            $(".child-popup-open.open").removeClass('active').siblings(".child-popup").slideUp();
        }
        else{
            $(".child-popup-open.open").removeClass('active').siblings(".child-popup").slideUp();
            $(this).addClass("active").siblings(".child-popup").slideToggle();
        }
    });



    $(".cart-open").on("click", function (e) {
        cartPopup(e)
    })

    $("body").on("click", ".close-cart-popup", function (e) {
        cartPopup(e);
    });

    $(".search-popup-open").on("click", function () {
        searchPopup()
        $("html").addClass('no-overflow');
    })

    $(".search-popup-close").on("click", function () {
        searchPopup()
        $("html").removeClass('no-overflow');
    })

    $(".login-popup-open").on("click", function () {
        if (!$('#login-popup').hasClass('active')) {
            loginPopup()
        }
    })

    $("#login-popup-back").on("click", function () {
        $("#popup-registration").hide()
        $("#popup-login").show()
    })

    $(".login-popup-close").on("click", function () {
        loginPopup()
        $("html").removeClass('no-overflow');
    })

    $(".consult-popup-open").on("click", function () {
        if (!$('#consult-popup').hasClass('active')) {
            consultPopup()
        }
    })

    $(".consult-popup-close").on("click", function () {
        consultPopup()
    });

    $(".fast-order-popup-open").on("click", function () {
        fastOrderPopup()
        // $('.fast-order-success').hide();
    })

    $(".fast-order-popup-close").on("click", function () {
        fastOrderPopup()
    })

    $(".report-availability-open").on("click", function () {
        $('#button-submit-report-availability').attr('data-product-id', $(this).data('product-id'));
        $('.error-message-report-order').hide();

        reportAvailabilityPopup()
    })

    $(".report-availability-close").on("click", function () {
        $('.report-availability-success').hide();
        $('#report-availability-form').show();

        reportAvailabilityPopup()
    })

    $(".reviews-open").on("click", function () {
        $('#button-submit-review').attr('data-product-id', $(this).data('product-id'));

        reviewPopup()
    })

    $(".close-review").on("click", function () {
        $('#button-submit-review').attr('data-product-id', $(this).data('product-id'));

        $('#review-alert').html('');

        $('#form-review')[0].reset();
        reviewPopup()
    })

    $('.review-form-open').on("click", function () {
        $(this).hide();
        $('#form-review').show();
    })

    // $(".login-show").click(function () {
    //   $(".registration-show").removeClass('active');
    //   $(".login-show").addClass('active');
    //   return open_pop_up("#popup-login");
    // });

    // $(".registration-show").click(function () {
    //   $(".login-show").removeClass('active');
    //   $(".registration-show").addClass('active')
    //   return open_pop_up("#popup-registration");
    // });

    $(".forgot_password-show").click(function () {
        return open_pop_up("#popup-forgot_password");
    })

    function open_pop_up(e) {
        $("#credential_picker_container").toggle();
        // $(".popup-window").fadeOut();
        $(".popup-window").hide()

        $(".popup-window").removeClass('active');
        $(".popup-overlay").addClass('active');
        // $(".popup-overlay").fadeIn();
        $("html").addClass('no-overflow');
        // $(e).fadeIn().addClass('active');
        $(e).show().addClass('active');

    }

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
                    $('#popup-consultation').hide();
                    $('.popup-consult-thanks').show();
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
                    $('#order-popup').hide();
                    $('#fast-order-form').hide();
                    $('.consult-title').hide();
                    $('.fast-order-success').show();
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
                    $('#report-availability-form').hide();
                    $('.report-availability-success').show();
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

function debounce(fn, delay) {
    let timeoutId;
    return function (...args) {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => {
            fn.apply(this, args);
        }, delay);
    };
}
