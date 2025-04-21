import {language} from './variables'

$(document).ready(function () {
    const buttonSearch = document.querySelector('#button-search')
    buttonSearch.addEventListener('click', function (){
        const inputSearch = document.querySelector('#input-search');
        const categorySearch = document.querySelector('select[name="category_id"]');
        const subCategory = document.querySelector('#sub_category');
        const descriptionSearch = document.querySelector('#description');

        const data = {}

        data.search = inputSearch.value

        if(subCategory.checked){
            data.sub_category = subCategory.checked
        }
        if(descriptionSearch.checked){
            data.description = descriptionSearch.checked
        }
        if(categorySearch.value !== "0"){
            data.category_id = categorySearch.value
        }


        const queryString = new URLSearchParams(data).toString();

        window.location.href = '?' + queryString;
    })
})
