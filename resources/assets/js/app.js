import './wishlist'
import './subscription'
import {ru, uk, en} from '/third-party/intlTelInput/js/i18n'
import {imageSliderInProduct, popupSliderInitialization} from "./sliderInitialization";
import {language} from './variables'


$(document).ready(function () {
    popupSliderInitialization('cartProductSlider')
    imageSliderInProduct('home-products-item');
    imageSliderInProduct('account-products-list .product-default');

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

    function isDesktopCatalogMega() {
        return window.matchMedia("(min-width: 992px)").matches;
    }

    let catalogMegaHoverTimer = null;
    let catalogMegaPromoLocked = false;
    const CATALOG_MEGA_HOVER_DELAY_MS = 500;

    function clearCatalogMegaHoverTimer() {
        if (catalogMegaHoverTimer) {
            window.clearTimeout(catalogMegaHoverTimer);
            catalogMegaHoverTimer = null;
        }
    }

    function setActiveCatalogMegaGroup($group, options = {}) {
        const immediate = options.immediate === true;
        const $mega = $("#headCatalogMega");
        const $groups = $mega.find(".head-catalog-mega__group");
        if (!$groups.length) {
            return;
        }

        const $target = $group && $group.length ? $group : $groups.first();
        if ($target.hasClass("is-active") && !immediate) {
            return;
        }

        $groups.removeClass("is-active");
        $target.addClass("is-active");
        updateCatalogMegaPromo($target);
    }

    function scheduleCatalogMegaGroup($group) {
        if (catalogMegaPromoLocked) {
            return;
        }

        clearCatalogMegaHoverTimer();

        if ($group && $group.hasClass("is-active")) {
            return;
        }

        catalogMegaHoverTimer = window.setTimeout(function () {
            catalogMegaHoverTimer = null;
            if (catalogMegaPromoLocked) {
                return;
            }
            setActiveCatalogMegaGroup($group);
        }, CATALOG_MEGA_HOVER_DELAY_MS);
    }

    function setCatalogMegaOpen(open) {
        const $mega = $("#headCatalogMega");
        const $trigger = $(".head-catalog-open");
        const $bg = $(".head-catalog-bg");
        const header = document.querySelector(".fixed-header");

        clearCatalogMegaHoverTimer();
        catalogMegaPromoLocked = false;

        if (open && header) {
            const top = Math.ceil(header.getBoundingClientRect().bottom);
            document.documentElement.style.setProperty("--catalog-mega-top", top + "px");
        }

        $mega.toggleClass("is-open", open);
        if (open) {
            $mega.removeAttr("hidden");
            setActiveCatalogMegaGroup($mega.find(".head-catalog-mega__group").first(), { immediate: true });
        } else {
            $mega.attr("hidden", "hidden");
            $mega.find(".head-catalog-mega__group.is-active").removeClass("is-active");
            $mega.find(".head-catalog-mega__item.has-children.is-open")
                .removeClass("is-open")
                .children(".head-catalog-mega__toggle")
                .attr("aria-expanded", "false");
            $mega.find(".head-catalog-mega__list.is-expanded").each(function () {
                const $list = $(this);
                const $btn = $list.find(".head-catalog-mega__more");
                $list.removeClass("is-expanded");
                $btn.attr("aria-expanded", "false");
                $btn.find(".head-catalog-mega__more-text").text($btn.attr("data-label-more") || "");
            });
        }

        $trigger.toggleClass("active", open);
        $bg.toggleClass("active", open);
        $trigger.attr("aria-expanded", open ? "true" : "false");
        $("body").toggleClass("catalog-mega-open", open);
    }

    $(".head-catalog-open, .head-catalog-bg").on("click", function () {
        if (isDesktopCatalogMega()) {
            const willOpen = !$("#headCatalogMega").hasClass("is-open");
            setCatalogMegaOpen(willOpen);
            return;
        }

        $(".head-catalog").slideToggle();
        $(".head-catalog-open, .head-catalog-bg").toggleClass("active");
    });

    function resetMobileCatalogAccordion($root) {
        const $scope = $root && $root.length ? $root : $(document);
        $scope.find(".head-catalog-mob__item.has-children.is-open")
            .removeClass("is-open")
            .find(".head-catalog-mob__toggle, .head-catalog-mob__subtoggle")
            .attr("aria-expanded", "false");
        $scope.find(".head-catalog-mob__subitem.is-open").removeClass("is-open");
    }

    function setMenuCatalogOpen(open) {
        const $menu = $("#menu-popup");
        const $btn = $menu.find(".mob-menu-catalog");
        const $panel = $menu.find(".mob-menu-catalog-panel");

        $menu.toggleClass("is-catalog-open", open);
        $btn.toggleClass("is-back", open).attr("aria-expanded", open ? "true" : "false");
        $panel.prop("hidden", !open);

        if (!open) {
            resetMobileCatalogAccordion($panel);
        }
    }

    $(".mob-menu-catalog").on("click", function () {
        setMenuCatalogOpen(!$("#menu-popup").hasClass("is-catalog-open"));
    });

    $(".mobil-catalog-open").on("click", function () {
        setCatalogMegaOpen(false);
        $("body").removeClass("catalog-mega-open");
        $("#menu-popup").removeClass("active");
        setMenuCatalogOpen(false);

        const $catalog = $(".head-catalog");
        $catalog.toggleClass("active");
        if (!$catalog.hasClass("active")) {
            resetMobileCatalogAccordion($catalog);
            unlockBodyScroll();
        }
    });

    $(".head-catalog-close").on("click", function () {
        setCatalogMegaOpen(false);
        $("body").removeClass("catalog-mega-open");
        $(".head-catalog").removeClass("active");
        resetMobileCatalogAccordion($(".head-catalog"));
        $("#menu-popup").removeClass("active");
        setMenuCatalogOpen(false);
        unlockBodyScroll();
    });

    $(document).on("keydown", function (event) {
        if (event.key === "Escape") {
            setCatalogMegaOpen(false);
        }
    });

    $(window).on("resize", function () {
        if (!isDesktopCatalogMega()) {
            setCatalogMegaOpen(false);
        }
    });

    $(document).on("click", ".head-catalog-mega__toggle", function (event) {
        event.preventDefault();
        event.stopPropagation();

        const $item = $(this).closest(".head-catalog-mega__item.has-children");
        const willOpen = !$item.hasClass("is-open");

        $item
            .siblings(".head-catalog-mega__item.has-children.is-open")
            .removeClass("is-open")
            .children(".head-catalog-mega__toggle")
            .attr("aria-expanded", "false");

        $item.toggleClass("is-open", willOpen);
        $(this).attr("aria-expanded", willOpen ? "true" : "false");
    });

    $(document).on("click", ".head-catalog-mega__more", function (event) {
        event.preventDefault();
        event.stopPropagation();

        const $btn = $(this);
        const $list = $btn.closest(".head-catalog-mega__list");
        const willExpand = !$list.hasClass("is-expanded");
        const label = willExpand
            ? ($btn.attr("data-label-less") || "")
            : ($btn.attr("data-label-more") || "");

        $list.toggleClass("is-expanded", willExpand);
        $btn.attr("aria-expanded", willExpand ? "true" : "false");
        $btn.find(".head-catalog-mega__more-text").text(label);

        if (!willExpand) {
            $list.find(".head-catalog-mega__item.has-children.is-open")
                .removeClass("is-open")
                .children(".head-catalog-mega__toggle")
                .attr("aria-expanded", "false");
        }
    });

    function updateCatalogMegaPromo($group) {
        const $promo = $("#headCatalogMega .head-catalog-mega__promo");
        if (!$promo.length) {
            return;
        }

        const useDefaults = !$group || !$group.length;
        const title = useDefaults ? $promo.attr("data-default-title") : $group.attr("data-promo-title");
        const url = useDefaults ? $promo.attr("data-default-url") : $group.attr("data-promo-url");
        let products = [];

        try {
            products = JSON.parse(
                useDefaults ? ($promo.attr("data-default-products") || "[]") : ($group.attr("data-promo-products") || "[]")
            );
            if (!Array.isArray(products)) {
                products = [];
            }
        } catch (e) {
            products = [];
        }

        $promo.addClass("is-updating");
        window.setTimeout(function () {
            $promo.find(".head-catalog-mega__promo-title").text(title || "");
            $promo.find(".head-catalog-mega__promo-cta").attr("href", url || "#");

            const $list = $promo.find(".head-catalog-mega__promo-products");
            $list.toggleClass("is-empty", products.length === 0);

            $list.find(".head-catalog-mega__promo-product").each(function (index) {
                const $product = $(this);
                const card = products[index] || null;
                const hasProduct = !!(card && card.name && card.url);

                $product.toggleClass("is-empty", !hasProduct);
                if (!hasProduct) {
                    return;
                }

                $product.attr("href", card.url).attr("title", card.name);
                $product.find(".head-catalog-mega__promo-product-image").attr("src", card.image || "");
                $product.find(".head-catalog-mega__promo-product-name").text(card.name || "");
                $product.find(".head-catalog-mega__promo-product-price")
                    .text(card.price || "")
                    .toggleClass("is-empty", !card.price);
            });

            $promo.removeClass("is-updating");
        }, 120);
    }

    $(document).on("mouseenter", ".head-catalog-mega__group", function () {
        scheduleCatalogMegaGroup($(this));
    });

    $(document).on("mouseleave", ".head-catalog-mega__group", function () {
        clearCatalogMegaHoverTimer();
    });

    $(document).on("mouseenter", ".head-catalog-mega__promo", function () {
        catalogMegaPromoLocked = true;
        clearCatalogMegaHoverTimer();
    });

    $(document).on("mouseleave", ".head-catalog-mega__promo", function () {
        catalogMegaPromoLocked = false;
    });

    $(document).on("click", ".mob-menu-lang__current", function (event) {
        event.preventDefault();
        event.stopPropagation();
        const $wrap = $(this).closest(".mob-menu-lang");
        const willOpen = !$wrap.hasClass("is-open");
        $(".mob-menu-lang").removeClass("is-open");
        $(".head-lang-dropdown").removeClass("is-open");
        $wrap.toggleClass("is-open", willOpen);
        $(this).attr("aria-expanded", willOpen ? "true" : "false");
    });

    $(document).on("click", ".head-lang-dropdown__current", function (event) {
        event.preventDefault();
        event.stopPropagation();
        const $wrap = $(this).closest(".head-lang-dropdown");
        const willOpen = !$wrap.hasClass("is-open");
        $(".head-lang-dropdown").removeClass("is-open");
        $(".mob-menu-lang").removeClass("is-open");
        $wrap.toggleClass("is-open", willOpen);
        $(this).attr("aria-expanded", willOpen ? "true" : "false");
    });

    $(document).on("click", function (event) {
        if (!event.target.closest(".mob-menu-lang")) {
            $(".mob-menu-lang").removeClass("is-open");
            $(".mob-menu-lang__current").attr("aria-expanded", "false");
        }
        if (!event.target.closest(".head-lang-dropdown")) {
            $(".head-lang-dropdown").removeClass("is-open");
            $(".head-lang-dropdown__current").attr("aria-expanded", "false");
        }
    });

    $("#enter_with_email").on("click", function (){
        $(".enter-with-phone").hide()
        $(".enter-with-email").show()
        $("#column-login").attr('data-type-login', 'email')

    })

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

    document.querySelector('body').addEventListener('click', function (event) {
        const notifyBtns = event.target.closest('.notify-available-btn')
        const reportAvailabilytyBtns = event.target.closest('.stock-in-alert')
        if (notifyBtns) {
            document.querySelector('#button-submit-report-availability').setAttribute('data-product-id', notifyBtns.getAttribute('data-product-id'));
        }
        if (reportAvailabilytyBtns) {
            document.querySelector('#button-submit-report-availability').setAttribute('data-product-id', reportAvailabilytyBtns.getAttribute('data-product-id'));
        }
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

    $('.review-form-open').click(function (){
        $(this).closest('.general-popup').attr('data-step', 3)
    })
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
        6: document.querySelector("#login_phone"),
        7: document.querySelector("#input-phone-forgot"),
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
                });

                inputs[key].addEventListener("input", function () {
                    if (this.value.startsWith("0")) {
                        this.value = this.value.replace(/^0+/, "");
                    }
                });
            }
        }
    }

    window.phoneInputs = {
        elements: inputs,
        instances: iti,
    }
}


$(document).ready(function () {
    popupsOpenClose()
    initHomeKitsAccordion()
    openAuthModalFromUrl()
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
            phone: $(this).find('input[name="phone"][type="hidden"]').val() || $('#fast_order_phone').val(),
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

    $('#home-review-form').on('submit', function (e) {
        e.preventDefault();

        const form = this;
        const submitBtn = $('#home-review-submit');
        const alertBox = $('#home-review-alert');
        const originalBtnText = submitBtn.text();
        const ratingChecked = form.querySelector('input[name="rating"]:checked');

        alertBox.empty();
        if (!ratingChecked) {
            alertBox.html(`
                <div class="alert alert-danger">
                    <ul class="mb-0"><li>${window.wrapReviewRatingRequiredText || 'Select rating.'}</li></ul>
                </div>
            `);
            return;
        }

        submitBtn.prop('disabled', true).text(`${window.wrapReviewLoadingText || 'Loading'}...`);

        const formData = new FormData(form);

        $.ajax({
            url: form.action,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'Accept': 'application/json'
            },
            success: function () {
                alertBox.html(`
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> ${window.wrapReviewSuccessText || 'Review sent successfully.'}
                    </div>
                `);
                form.reset();
                const photoName = document.getElementById('home-review-photo-name');
                if (photoName) {
                    photoName.textContent = window.wrapReviewPhotoEmptyText || '';
                }
            },
            error: function (xhr) {
                const messages = [];
                const errors = xhr?.responseJSON?.errors || null;

                if (errors) {
                    Object.values(errors).forEach((fieldErrors) => {
                        (fieldErrors || []).forEach((msg) => messages.push(msg));
                    });
                } else if (xhr?.responseJSON?.message) {
                    messages.push(xhr.responseJSON.message);
                } else {
                    messages.push(window.wrapReviewErrorText || 'Error sending review.');
                }

                alertBox.html(`
                    <div class="alert alert-danger">
                        <ul class="mb-0">${messages.map((msg) => `<li>${msg}</li>`).join('')}</ul>
                    </div>
                `);
            },
            complete: function () {
                submitBtn.prop('disabled', false).text(originalBtnText);
            }
        });
    });

    (function initHomeReviewDropzone() {
        const dropzone = document.getElementById('home-review-dropzone');
        const input = document.getElementById('home-review-photo');
        const photoName = document.getElementById('home-review-photo-name');

        if (!dropzone || !input || !photoName) {
            return;
        }

        const updateName = () => {
            photoName.textContent = (input.files && input.files.length > 0)
                ? input.files[0].name
                : (window.wrapReviewPhotoEmptyText || '');
        };

        dropzone.addEventListener('click', () => input.click());
        input.addEventListener('change', updateName);

        ['dragenter', 'dragover'].forEach((evt) => {
            dropzone.addEventListener(evt, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.add('is-dragover');
            });
        });

        ['dragleave', 'dragend', 'drop'].forEach((evt) => {
            dropzone.addEventListener(evt, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.remove('is-dragover');
            });
        });

        dropzone.addEventListener('drop', (e) => {
            const files = e.dataTransfer?.files;
            if (!files || files.length === 0) {
                return;
            }

            const dt = new DataTransfer();
            dt.items.add(files[0]);
            input.files = dt.files;
            updateName();
        });
    })();

});


$(document).on("click", ".head-catalog-mob__toggle", function (event) {
    event.preventDefault();
    event.stopPropagation();
    toggleMobileCatalogItem($(this).closest(".head-catalog-mob__item.has-children"), $(this));
});

$(document).on("click", ".head-catalog-mob__link", function (event) {
    const $item = $(this).closest(".head-catalog-mob__item");
    if (!$item.hasClass("has-children")) {
        return;
    }

    event.preventDefault();
    event.stopPropagation();
    toggleMobileCatalogItem($item, $item.find("> .head-catalog-mob__row .head-catalog-mob__toggle"));
});

function toggleMobileCatalogItem($item, $toggle) {
    if (!$item.length) {
        return;
    }

    const willOpen = !$item.hasClass("is-open");
    const $menu = $item.closest(".head-catalog-mob");

    $menu.find(".head-catalog-mob__item.has-children.is-open")
        .not($item)
        .removeClass("is-open")
        .find(".head-catalog-mob__subitem.is-open")
        .removeClass("is-open");
    $menu.find(".head-catalog-mob__item.has-children")
        .not($item)
        .find(".head-catalog-mob__toggle, .head-catalog-mob__subtoggle")
        .attr("aria-expanded", "false");

    $item.toggleClass("is-open", willOpen);
    $toggle.attr("aria-expanded", willOpen ? "true" : "false");

    if (!willOpen) {
        $item.find(".head-catalog-mob__subitem.is-open")
            .removeClass("is-open")
            .children(".head-catalog-mob__subtoggle")
            .attr("aria-expanded", "false");
    }
}

$(document).on("click", ".head-catalog-mob__subtoggle", function (event) {
    event.preventDefault();
    event.stopPropagation();

    const $item = $(this).closest(".head-catalog-mob__subitem.has-children");
    const willOpen = !$item.hasClass("is-open");

    $item
        .siblings(".head-catalog-mob__subitem.has-children.is-open")
        .removeClass("is-open")
        .children(".head-catalog-mob__subtoggle")
        .attr("aria-expanded", "false");

    $item.toggleClass("is-open", willOpen);
    $(this).attr("aria-expanded", willOpen ? "true" : "false");
});


function popupSearchAction(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    const popupRoot = document.querySelector('#search-popup #search');
    if (popupRoot) {
        initSearchWidget({
            input: popupRoot.querySelector('input[name="search-popup"]'),
            button: popupRoot.querySelector('button'),
            dropdown: popupRoot.querySelector('.dropdown-menu'),
        });
    }

    const headRoot = document.querySelector('#head-search');
    if (headRoot) {
        initSearchWidget({
            input: headRoot.querySelector('input[name="head-search"]'),
            button: headRoot.querySelector('.head-search__submit'),
            dropdown: headRoot.querySelector('.head-search__dropdown'),
        });
    }
}

function initSearchWidget({ input, button, dropdown }) {
    if (!input || !button || !dropdown) {
        return;
    }

    const searchRoot = input.closest('#head-search')
        || input.closest('#search-popup')
        || input.closest('#search')
        || input.parentElement;

    document.addEventListener('click', function (e) {
        if (searchRoot && !searchRoot.contains(e.target)) {
            dropdown.style.display = 'none';
        }
    });

    input.addEventListener('click', function () {
        if (input.value.trim().length >= 3) {
            dropdown.style.display = 'block';
        }
    });

    const goToSearch = function () {
        const value = input.value.trim();
        let nextLink = (language && language !== 'uk') ? `/${language}/search` : '/search';
        if (value.length !== 0) {
            const q = encodeURIComponent(value);
            nextLink = (language && language !== 'uk')
                ? `/${language}/search?search=${q}`
                : `/search?search=${q}`;
        }
        window.location.href = nextLink;
    };

    button.addEventListener('click', function (event) {
        event.preventDefault();
        goToSearch();
    });

    input.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            goToSearch();
        }
    });

    const debouncedSearch = debounce(function (event) {
        popupSearchHandler(dropdown, event);
    }, 500);
    input.addEventListener('input', debouncedSearch);
}

function popupSearchHandler(dropdownMenu, event) {
    const query = event.target.value.trim();
    let url = '/get-search-items';
    if (language && language !== 'uk') {
        url = `/${language}/get-search-items`;
    }

    if (query.length >= 3) {
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                search: query,
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
            dataType: 'json',
            success: function (response) {
                if (!response || !response.data) {
                    dropdownMenu.style.display = 'none';
                    return;
                }
                dropdownMenu.innerHTML = response.data.view;
                dropdownMenu.style.display = response.data.total_count === 0 ? 'none' : 'block';
            },
            error: function () {
                dropdownMenu.style.display = 'none';
            }
        });
    } else {
        dropdownMenu.style.display = 'none';
    }
}

function initHomeKitsAccordion() {
    document.querySelectorAll('.js-home-kits-accordion').forEach(function (accordion) {
        if (accordion.dataset.kitsAccordionReady === '1') {
            return;
        }

        accordion.dataset.kitsAccordionReady = '1';

        accordion.querySelectorAll('.home-block-kits__accordion-item').forEach(function (item) {
            item.addEventListener('toggle', function () {
                if (!item.open) {
                    return;
                }

                accordion.querySelectorAll('.home-block-kits__accordion-item').forEach(function (other) {
                    if (other !== item) {
                        other.open = false;
                    }
                });
            });
        });
    });
}

let popupScrollLockY = 0;

function lockBodyScroll() {
    if (!window.matchMedia('(max-width: 767px)').matches) {
        return;
    }

    if (document.body.classList.contains('popup-scroll-lock')) {
        return;
    }

    popupScrollLockY = window.scrollY || window.pageYOffset;
    document.documentElement.classList.add('popup-scroll-lock');
    document.body.classList.add('popup-scroll-lock');
    document.body.style.top = `-${popupScrollLockY}px`;
}

function unlockBodyScroll() {
    if (!window.matchMedia('(max-width: 767px)').matches) {
        return;
    }

    if (!document.body.classList.contains('popup-scroll-lock')) {
        return;
    }

    document.documentElement.classList.remove('popup-scroll-lock');
    document.body.classList.remove('popup-scroll-lock');
    document.body.style.top = '';
    window.scrollTo(0, popupScrollLockY);
}

function popupsOpenClose(){
    const popups = document.querySelectorAll('.general-popup');
    const body = document.querySelector('body');

    body.addEventListener('click', function(event) {
        const button = event.target.closest('.general-popup-btn')
        if (button) {
            closeAllPopups(popups)
            const target = button.getAttribute('data-popup')
            const popup = document.getElementById(target);
            if (popup) {
                popup.classList.add('active');
                lockBodyScroll();
            }
        }
    })

    body.addEventListener('click', function (event) {
        const closeBtn = event.target.closest('.general-popup .popup-close');
        if (!closeBtn) {
            return;
        }

        const popup = closeBtn.closest('.general-popup');
        if (popup) {
            popup.classList.remove('active');
            popup.classList.remove('is-catalog-open');
            popup.setAttribute('data-step', '1');
            clearText();
            unlockBodyScroll();

            const catalogBtn = popup.querySelector('.mob-menu-catalog');
            const catalogPanel = popup.querySelector('.mob-menu-catalog-panel');
            if (catalogBtn) {
                catalogBtn.classList.remove('is-back');
                catalogBtn.setAttribute('aria-expanded', 'false');
            }
            if (catalogPanel) {
                catalogPanel.hidden = true;
                catalogPanel.querySelectorAll('.head-catalog-mob__item.has-children.is-open').forEach((item) => {
                    item.classList.remove('is-open');
                });
                catalogPanel.querySelectorAll('.head-catalog-mob__subitem.is-open').forEach((item) => {
                    item.classList.remove('is-open');
                });
                catalogPanel.querySelectorAll('.head-catalog-mob__toggle, .head-catalog-mob__subtoggle').forEach((btn) => {
                    btn.setAttribute('aria-expanded', 'false');
                });
            }
        }
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
        popup.classList.remove('is-catalog-open')
        popup.setAttribute('data-step', '1')

        const catalogBtn = popup.querySelector('.mob-menu-catalog');
        const catalogPanel = popup.querySelector('.mob-menu-catalog-panel');
        if (catalogBtn) {
            catalogBtn.classList.remove('is-back');
            catalogBtn.setAttribute('aria-expanded', 'false');
        }
        if (catalogPanel) {
            catalogPanel.hidden = true;
        }
    });
    unlockBodyScroll();
}

/**
 * Якщо в URL є ?modal=login|register|forgot — відкрити модалку входу/реєстрації/відновлення пароля.
 */
function openAuthModalFromUrl() {
    const params = new URLSearchParams(window.location.search)
    const modal = params.get('modal')
    const steps = { login: '1', register: '5', forgot: '3' }
    if (!modal || !steps[modal]) return
    const popups = document.querySelectorAll('.general-popup')
    const loginPopup = document.getElementById('login-popup')
    if (!loginPopup) return
    closeAllPopups(popups)
    loginPopup.classList.add('active')
    loginPopup.setAttribute('data-step', steps[modal])
    lockBodyScroll()
    params.delete('modal')
    const newSearch = params.toString()
    const newUrl = window.location.pathname + (newSearch ? '?' + newSearch : '') + window.location.hash
    window.history.replaceState({}, '', newUrl)
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
