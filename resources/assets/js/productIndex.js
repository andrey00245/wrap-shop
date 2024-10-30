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

$(document).ready(function () {
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
  openCloseFilterMenu()
})





