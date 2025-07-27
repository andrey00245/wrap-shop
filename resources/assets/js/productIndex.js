import {language} from './variables'
import {imageSliderInProduct} from "./sliderInitialization";

$(document).ready(function () {
    imageSliderInProduct('category-products-item');


    function filertsDropdownAction(element) {
        const collapsingElement = $(element.querySelector('.ocf-filter-collapse'))

        element.querySelector('.ocf-filter-header').onclick = null;
        $(element).toggleClass('ocf-open')
        if ($(element).hasClass('ocf-open')) {
            collapsingElement.removeClass('ocf-collapse')
            const height = collapsingElement.height()
            collapsingElement.addClass('ocf-collapsing')
            collapsingElement.height(height)
            setTimeout(function () {
                collapsingElement.removeClass('ocf-collapsing')
                collapsingElement.addClass('ocf-collapse')
                collapsingElement.addClass('ocf-in')
                collapsingElement.css('height', '');
                element.querySelector('.ocf-filter-header').onclick = () => filertsDropdownAction(element);
            }, 350)
        } else if (!$(element).hasClass('ocf-open')) {
            collapsingElement.removeClass('ocf-collapse')
            collapsingElement.removeClass('ocf-in')
            let height = collapsingElement.height()
            collapsingElement.css('height', height + 'px');
            height = 0
            collapsingElement.addClass('ocf-collapsing')
            collapsingElement.height(height);
            setTimeout(function () {
                collapsingElement.removeClass('ocf-collapsing')
                collapsingElement.addClass('ocf-collapse')
                collapsingElement.css('height', '');
                element.querySelector('.ocf-filter-header').onclick = () => filertsDropdownAction(element);
            }, 350)
        }
    }

    function openCloseFilterMenu() {
        const filterContainer = $('.ocf-container')
        const filterOpenBtn = $('.category-filtr-open')
        const filterCloseBtn = $('.ocf-container .ocf-close-mobile')

        const sortContainer = $('.category-right-top')
        const sortOpenBtn = $('.category-sort-open')
        const sortCloseBtn = $('.category-right-top .category-sort-close')

        filterOpenBtn[0].onclick = () => {
            filterContainer.addClass('ocf-mobile-active')
        }

        sortOpenBtn[0].onclick = () => {
            sortContainer.addClass('active')
        }

        filterCloseBtn[0].onclick = () => {
            filterContainer.removeClass('ocf-mobile-active')
        }

        sortCloseBtn[0].onclick = () => {
            sortContainer.removeClass('active')
        }
    }

    // Product List
    $('#list-view').click(function () {
        $('#content .product-grid').addClass('product-list');
        $('#content .product-grid').removeClass('product-grid');
        $('#grid-view').removeClass('active');
        $('#list-view').addClass('active');
        localStorage.setItem('display', 'list');
    });

    // Product Grid
    $('#grid-view').click(function () {
        $('#content .product-list').addClass('product-grid');
        $('#content .product-list').removeClass('product-list');
        $('#list-view').removeClass('active');
        $('#grid-view').addClass('active');
        localStorage.setItem('display', 'grid');
    });

    if (localStorage.getItem('display') == 'list') {
        $('#list-view').trigger('click');
        $('#list-view').addClass('active');
    } else {
        $('#grid-view').trigger('click');
        $('#grid-view').addClass('active');
    }

    const filtersDropdown = $('.ocf-body .ocf-filter-list .ocf-dropdown')

    filtersDropdown.each(function (index, element) {
        element.querySelector('.ocf-filter-header').onclick = () => filertsDropdownAction(element)
    })

    let filters = {};
    let minPrice = 0;
    let maxPrice = 0;
    const filterProducts = $('.filterProducts')
    const categoryId = $('#categoryId').data('category-id');
    let filterProductsPrice = $('.filterProductsPrice')

    openCloseFilterMenu()
    filterProductsClick()
    updateStatusFilters()
    unselectAllFilters()
    // filterProductsPriceClick();
    checkFiltersOnPageLoad();
    initializationPriceSlider();
    search();

    function search() {
        const searchInput = document.querySelector('#search input')
        const searchButton = document.querySelector('#search button')
        searchButton.addEventListener('click', function () {
            redirectWithParameters(searchInput)
        })
        searchInput.addEventListener('keydown', function () {
            if (event.key === 'Enter') {
                redirectWithParameters(searchInput)
            }
        })
    }

    function redirectWithParameters(searchInput) {
        const data = {}
        const getParametesUrl = new URL(window.location.href);
        const params = new URLSearchParams(getParametesUrl.search);

        data.search = searchInput.value
        if (params.get('sub_category') !== null) {
            data.sub_category = params.get('sub_category');
        }
        if (params.get('description') !== null) {
            data.description = params.get('description');
        }
        if (params.get('category_id') !== null) {
            data.category_id = params.get('category_id');
        }

        const queryString = new URLSearchParams(data).toString();
        if (language !== 'uk') {
            window.location.href = `/${language}/search?${queryString}`;
        } else {
            window.location.href = `/search?${queryString}`;
        }
    }


    // function filterProductsPriceClick() {
    //     filterProductsPrice.on('click', function () {
    //         if (!$(this).hasClass('ocf-selected')) {
    //             filterProductsPrice.each(function () {
    //                 $(this).removeClass('ocf-selected');
    //             })
    //         }
    //
    //         $(this).toggleClass('ocf-selected')
    //         // setMaxMinVal();
    //         getCountAjax($(this))
    //     })
    // }

    // function setMaxMinVal() {
    //     let tempCount = 0;
    //     filterProductsPrice.each(function () {
    //         if ($(this).hasClass('ocf-selected')) {
    //             tempCount++;
    //             minPrice = $(this).data('value-min')
    //             maxPrice = $(this).data('value-max')
    //         }
    //     })
    //     if (tempCount === 0) {
    //         minPrice = 0
    //         maxPrice = 0
    //     }
    // }

    function changeFilterTotal(windowWidth) {
        if (windowWidth < 768) {
            $('#ocfFilter').addClass('ocf-bottom');
            $('#ocfFilter').removeClass('ocf-right');
        } else {
            $('#ocfFilter').removeClass('ocf-bottom');
            $('#ocfFilter').addClass('ocf-right');
        }
    }

    function updateStatusFilters() {
        filterProducts.each(function () {
            // setMaxMinVal()
            let filterType = $(this).data('filter-type')
            let filterValue = $(this).data('filter')
            if ($(this).hasClass('ocf-selected')) {
                filters[filterType][filterValue]['active'] = true;
            }
        })
    }


    function countSelected(obj) {
        let count = 0;

        function traverseObject(curObj) {
            $.each(curObj, function (key, value) {
                if (typeof value === "object" && value !== null) {
                    traverseObject(value);
                } else if (value === true) {
                    count++
                }
            });
        }

        traverseObject(obj);
        return count;
    }

    function unselectAllFilters() {
        $('#cancel').on('click', function (e) {
            if ($('.ocf-selected-card .ocf-selected-filter .ocf-selected-discard').length === 0) {
                filterProducts.each(function () {
                    let filterType = $(this).data('filter-type')
                    let filterValue = $(this).data('filter')
                    filters[filterType][filterValue]['active'] = false;
                    $(this).removeClass('ocf-selected');
                })
                // filterProductsPrice.each(function () {
                //     $(this).removeClass('ocf-selected');
                //     minPrice = 0;
                //     maxPrice = 0;
                // })
                getCountAjax()
            } else {
                window.location.href = $(this).data('link');
            }
        })
    }

    function checkFiltersOnPageLoad() {
        let filterSelected = false;
        filterProducts.each(function () {
            if ($(this).hasClass('ocf-selected')) {
                filterSelected = true;
            }
        })

        let minInput = $('#min_price');
        let maxInput = $('#max_price');

        maxPrice = parseInt(maxInput.val());
        minPrice = parseInt(minInput.val());

        if (parseInt(minInput.attr('min')) !== parseInt(minInput.val()) || parseInt(maxInput.attr('max')) !== parseInt(maxInput.val())) {
            filterSelected = true;
        }

        if (filterSelected) {
            $('#cancel').prop("disabled", false)
        }
    }

    function showTotalCountPopup(button, filters, count = 0, location = '') {
        $('#ocfFilter .ocf-search-btn-popover').html(count);
        $('.ocfFilterBottom').each(function () {
            $(this).html(count)
        });

        let selectedFilters = countSelected(filters);

        if (selectedFilters > 0 || (maxPrice !== 0 && minPrice !== 0)) {
            $('#ocfFilter .ocf-search-btn-popover').attr("onclick", "location.href='" + location + "'")
            $('#ocfFilter .ocf-search-btn-popover').prop("disabled", false)
            $('.ocfFilterBottom').each(function () {
                $(this).attr("onclick", "location.href='" + location + "'")
                $(this).prop("disabled", false)
            });
            $('#cancel').prop("disabled", false)
        } else {
            $('#ocfFilter .ocf-search-btn-popover').attr("onclick", "")
            $('#ocfFilter .ocf-search-btn-popover').prop("disabled", true)
            $('.ocfFilterBottom').each(function () {
                $(this).attr("onclick", "")
                $(this).prop("disabled", true)
            });
            $('#cancel').prop("disabled", true)
        }

        let filtersBlock = $('.category-left .ocf-container');
        let popup = $('#ocfFilter');

        if (button != null) {
            let buttonOffset = button.offset();
            let buttonWidth = button.outerWidth();
            let buttonHeight = button.outerHeight();

            let filterBlockOffset = filtersBlock.offset();

            let popupWidth = popup.outerWidth();
            let popupHeight = popup.outerHeight();

            let screenWidth = $(window).width();
            let screenHeight = $(window).height();

            let popupPositionLeft = buttonOffset.left + buttonWidth - filterBlockOffset.left;
            let popupPositionTop = buttonOffset.top - filterBlockOffset.top + (buttonHeight / 2) - (popupHeight / 2);

            if (screenWidth < 768) {
                popupPositionLeft = buttonOffset.left - filterBlockOffset.left + (buttonWidth / 2) - (popupWidth / 2);
                popupPositionTop = buttonOffset.top + buttonHeight / 2 - filterBlockOffset.top;

                if (popupPositionLeft + popupWidth > screenWidth) {
                    popupPositionLeft = screenWidth - popupWidth - 10;
                }
                if (popupPositionLeft < 10) {
                    popupPositionLeft = 10;
                }
            }
            popup.css({
                top: popupPositionTop,
                left: popupPositionLeft
            }).fadeIn();
        } else {
            popup.fadeOut();
        }
    }

    changeFilterTotal($(window).width());

    function filterProductsClick() {
        let hideTimer;
        filterProducts.each(function () {
            let filterType = $(this).data('filter-type')
            let filterValue = $(this).data('filter')

            if (!filters[filterType]) {
                filters[filterType] = {};
            }
            filters[filterType][filterValue] = {};
            filters[filterType][filterValue]['active'] = false;
        })

        let resizeTimeout;
        $(window).on('resize', function () {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(function () {
                var windowWidth = $(window).width();
                changeFilterTotal(windowWidth);
            }, 200);
        });

        filterProducts.on('click', function (e) {
            let filterType = $(this).data('filter-type')
            let filterValue = $(this).data('filter')
            let categoryId = $('#categoryId').data('category-id');
            let button = $(this);
            filters[filterType][filterValue]['active'] = !filters[filterType][filterValue]['active'];
            if (filters[filterType][filterValue]['active']) {
                $(this).addClass('ocf-selected')
            } else {
                $(this).removeClass('ocf-selected')
            }
            getCountAjax(button)
        })
    }

    function initializationPriceSlider() {
        const priceSlider = document.getElementById('price-slider');

        const sliderMin = parseInt($('#min_price').attr('min'));
        const sliderMinVal = parseInt($('#min_price').val());
        const sliderMax = parseInt($('#max_price').attr('max'));
        const sliderMaxVal = parseInt($('#max_price').val());

        let minInput = document.querySelector('#min_price');
        let maxInput = document.querySelector('#max_price');
        let maxInputJq = $('#max_price');
        if (sliderMin !== 0 && sliderMax !== 0) {
            noUiSlider.create(priceSlider, {
                start: [sliderMinVal, sliderMaxVal],
                connect: true,
                range: {
                    'min': sliderMin,
                    'max': sliderMax
                },
                step: 1,
                format: {
                    to: function (value) {
                        return Math.round(value);
                    },
                    from: function (value) {
                        return Number(value);
                    }
                }
            });
        } else {
            noUiSlider.create(priceSlider, {
                start: [sliderMinVal, sliderMaxVal],
                connect: true,
                range: {
                    'min': 0,
                    'max': 1
                },
                step: 1,
                format: {
                    to: function (value) {
                        return Math.round(value);
                    },
                    from: function (value) {
                        return Number(value);
                    }
                }
            });
        }

        priceSlider.noUiSlider.on('update', function (values, handle) {
            minInput.value = values[0];
            maxInput.value = values[1];
        });

        priceSlider.noUiSlider.on('change', function (values, handle) {
            minPrice = values[0];
            maxPrice = values[1];
            getCountAjax(maxInputJq)
        });

        minInput.addEventListener('change', function () {
            priceSlider.noUiSlider.set([this.value, null]);
            minPrice = this.value;
        });
        minInput.addEventListener('change', function () {
            getCountAjax(maxInputJq)
        });
        maxInput.addEventListener('change', function () {
            priceSlider.noUiSlider.set([null, this.value]);
            maxPrice = this.value;
        });
        maxInput.addEventListener('change', function () {
            getCountAjax(maxInputJq)
        });
    }

    function getCountAjax(button = null) {
        let url = '/get-count'

        if (language !== 'uk') {
            url = '/' + language + url;
        }
        const getParametesUrl = new URL(window.location.href);
        const params = new URLSearchParams(getParametesUrl.search);
        const search = params.get('search');
        const category_id = params.get('category_id');
        const sub_category = params.get('sub_category');
        const description = params.get('description');
        const data = {}


        data.filters = filters
        if (categoryId === undefined) {
            data.category_id = category_id
        } else {
            data.category_id = categoryId
        }
        data.min_price = minPrice
        data.max_price = maxPrice
        data.search = search
        data.sub_category = sub_category
        data.description = description

        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: data,
            success: function (response) {
                let filterTypeActive = [];

                filterProducts.each(function () {
                    if (response['attributes_count'][$(this).data('filter-type')][$(this).data('filter')]['active'] === "true") {
                        filterTypeActive.push($(this).data('filter-type'));
                    }
                })

                filterProducts.each(function () {
                    if (filterTypeActive.includes($(this).data('filter-type'))) {
                        $(this).find('.ocf-value-count').text('+' + response['attributes_count'][$(this).data('filter-type')][$(this).data('filter')]['count'])
                    } else {
                        $(this).find('.ocf-value-count').text(response['attributes_count'][$(this).data('filter-type')][$(this).data('filter')]['count'])
                    }
                    if (response['attributes_count'][$(this).data('filter-type')][$(this).data('filter')]['count'] === 0) {
                        $(this).find('.ocf-value-count').text(response['attributes_count'][$(this).data('filter-type')][$(this).data('filter')]['count'])
                        if ($(this).hasClass('ocf-selected')) {
                            $(this).prop('disabled', false);
                        } else {
                            $(this).addClass('ocf-disabled');
                            $(this).prop('disabled', true);
                        }

                    } else {
                        $(this).removeClass('ocf-disabled');
                        $(this).prop('disabled', false);
                    }
                })
                showTotalCountPopup(button, filters, response['total_count'], response['new_url'])
            },
            error: function (xhr, status, error) {
                console.log('Error:', error);
            },
            complete: function (xhr, status) {
                console.log('Request completed');
            }
        });
    }

    let loading = false;
    const productList = document.querySelector('.category-products');
    const urlParams = new URLSearchParams(window.location.search);
    let currentPage = parseInt(urlParams.get('page')) || 1;
    let paginationItems = document.querySelector('.category-pagination .pagination');
    currentPage += 1;
    const showMore = $('#ss_showmore');

    window.addEventListener('scroll', () => {
        if (loading) return;

        showMore.show();
        const rect = productList.getBoundingClientRect();
        const isVisible = rect.bottom <= window.innerHeight + 1500;

        if (isVisible) {
            loading = true;

            const newParams = new URLSearchParams(window.location.search);
            newParams.set('page', currentPage);

            fetch(window.location.pathname + '?' + newParams.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.html.trim().length === 0) {
                        window.removeEventListener('scroll', this);
                        document.getElementById('ss_showmore').style.display = 'none';
                        return;
                    }

                    productList.insertAdjacentHTML('beforeend', data.html);
                    imageSliderInProduct('category-products-item');

                    currentPage++;

                    paginationItems.innerHTML = '';
                    getPagination(currentPage - 1, data.lastPage).forEach(function (el){
                        newParams.set('page', String(el));
                        const pageItem = document.createElement('li');
                        let innerPage;

                        if (el === currentPage - 1) {
                            pageItem.className = 'active';
                            innerPage = document.createElement('span');
                            innerPage.innerText = String(el)
                        } else {
                            innerPage = document.createElement('a');
                            innerPage.innerText = String(el)
                            innerPage.setAttribute('href', window.location.pathname + '?' + newParams);
                        }
                        pageItem.appendChild(innerPage);
                        paginationItems.appendChild(pageItem)
                    })

                    loading = false;

                    newParams.set('page', currentPage - 1);
                    const newUrl = window.location.pathname + '?' + newParams.toString();
                    window.history.replaceState(null, '', newUrl);
                });
        }

    });

    document.addEventListener('click', function(e) {
        const link = e.target.closest('a');
        if (link && link.href.includes('/products/')) {
            sessionStorage.setItem('scrollPosition', window.scrollY);
        }
    });

    window.addEventListener('pageshow', (event) => {
        const scrollPos = sessionStorage.getItem('scrollPosition');
        if (scrollPos !== null) {
            window.scrollTo(0, parseInt(scrollPos));
            sessionStorage.removeItem('scrollPosition'); // очистить, чтобы не мешало
        }
    });
})

function getPagination(current, total, maxVisible = 9) {
    const pages = [];

    if (total <= maxVisible) {
        for (let i = 1; i <= total; i++) {
            pages.push(i);
        }
    } else {
        let half = Math.floor(maxVisible / 2);
        let start = current - half;
        let end = current + half;

        if (start < 1) {
            start = 1;
            end = maxVisible;
        }

        if (end > total) {
            end = total;
            start = total - maxVisible + 1;
        }

        for (let i = start; i <= end; i++) {
            pages.push(i);
        }
    }
    return pages;
}
