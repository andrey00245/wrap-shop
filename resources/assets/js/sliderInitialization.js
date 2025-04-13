export function productSliderInitialization(id) {
    let productsSlider = new Splide('#' + id + ' .splide', {
        pagination: false,
        perPage: 4,
        gap: '5px',
        padding: {right: '8%'},
        classes: {
            arrows: 'splide__arrows home-products-slide-buttons',
            prev: 'splide__arrow--prev',
            next: 'splide__arrow--next',
        },
        breakpoints: {
            1331: {
                perPage: 3,
                padding: {right: '3%'},
            },
            1019: {
                perPage: 2,
                padding: {right: '15%'},
            },
            767: {
                perPage: 2,
                padding: {right: 0},
            },
            400: {
                perPage: 1,
                padding: {right: '25%'},
            }
        }
    }).mount();

    $('#' + id + ' .home-products-nav .item').on('click', function () {
        var activeItem = $(this).attr('data-cat');
        if (activeItem != 'all') {
            $('#' + id + ' .home-products-nav .item').removeClass('active');
            $('#' + id + ' .home-products-item').removeClass('show').addClass('hide');
            $(this).addClass('active');
            $('#' + id + ' .home-products-item[data-ids="' + activeItem + '"]').removeClass('hide').addClass('show');
        } else {
            $('#' + id + ' .home-products-nav .item').removeClass('active');
            $('#' + id + ' .home-products-item').removeClass('hide');
            $(this).addClass('active');
        }
    });
}


export function popupSliderInitialization(id) {
    let productsSlider = new Splide('#' + id + ' .splide', {
        pagination: false,
        perPage: 2,
        gap: '5px',
        padding: {right: '8%'},
        classes: {
            arrows: 'splide__arrows home-products-slide-buttons">',
            prev: 'splide__arrow--prev',
            next: 'splide__arrow--next',
        },
        breakpoints: {
            1331: {
                perPage: 3,
                padding: {right: '3%'},
            },
            1019: {
                perPage: 2,
                padding: {right: '5%'},
            },
            767: {
                perPage: 1,
                padding: {right: '45%'},
            },
            400: {
                perPage: 1,
                padding: {right: '25%'},
            }
        }
    }).mount();

    $('#' + id + ' .home-products-nav .item').on('click', function () {
        var activeItem = $(this).attr('data-cat');
        if (activeItem != 'all') {
            $('#' + id + ' .home-products-nav .item').removeClass('active');
            $('#' + id + ' .home-products-item').removeClass('show').addClass('hide');
            $(this).addClass('active');
            $('#' + id + ' .home-products-item[data-ids="' + activeItem + '"]').removeClass('hide').addClass('show');
        } else {
            $('#' + id + ' .home-products-nav .item').removeClass('active');
            $('#' + id + ' .home-products-item').removeClass('hide');
            $(this).addClass('active');
        }
    });
}

export function imageSliderInProduct(item_class) {
    let elms = document.querySelectorAll('.' + item_class + ' .products-images');
    let hoverItems = document.querySelectorAll('.' + item_class);
    let image_splides = []

    for (var i = 0; i < elms.length; i++) {
        image_splides[i] = new Splide(elms[i], {
            pagination: false,
            perPage: 1,
            arrows:false
        }).mount();
    }

    hoverItems.forEach(function (el, i) {
        el.addEventListener('mouseenter', function () {
            image_splides[i].go(1);
        })
        el.addEventListener('mouseleave', function () {
            image_splides[i].go(0);
        })
    })
}

export function imageSliderInDefaultProduct(item_class) {
    let elms = document.querySelectorAll('.' + item_class + ' .default-products-images');
    let hoverItems = document.querySelectorAll('.' + item_class);
    let image_splides = []

    for (var i = 0; i < elms.length; i++) {
        image_splides[i] = new Splide(elms[i], {
            pagination: false,
            gap: 5,
            perPage: 1,
        }).mount();
    }
}
