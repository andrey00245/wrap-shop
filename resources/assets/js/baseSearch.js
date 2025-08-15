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

        // preserve active filters from current page when jumping into search
        const current = new URL(window.location.href);
        const currentParams = new URLSearchParams(current.search);
        const blocked = new Set(['search','category_id','description','sub_category','page']);
        currentParams.forEach((value, key) => {
            if (!blocked.has(key)) {
                if (!data[key]) data[key] = [];
                if (Array.isArray(data[key])) data[key].push(value); else data[key] = value;
            }
        });

        const queryString = new URLSearchParams(data).toString();

        window.location.href = '?' + queryString;
    })
})
