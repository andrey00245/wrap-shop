@extends('base.layouts.app')
@section('content')

    @push('styles')
        @if($theme === 'dark')
            <link rel="stylesheet" href="{{mix('build/css/all-dark.css')}}">
            <link rel="stylesheet" href="{{mix('build/css/style-category-dark.css')}}">
        @else
            <link rel="stylesheet" href="{{mix('build/css/all-light.css')}}">
            <link rel="stylesheet" href="{{mix('build/css/style-category-light.css')}}">
        @endif

    @endpush

    @section('title',__('search-page.title', ['string'=> request()->get('search') ? '- ' . request()->get('search') : '']))
    @section('description', __('search-page.title', ['string'=> request()->get('search') ? '- ' . request()->get('search') : '']))


    <section class="category-page row">
        <div class="category-top">
            <img class="products-background" src="{{asset('assets/img/search-page/background.jpg')}}" alt="background">
            <nav class="category-breadcrumbs">
                <ul class="flex-center">
                    <li><a href="{{route('index')}}" title="{{__('header_footer.home')}}" class="button"><i
                                class="far fa-chevron-left"></i>{{__('header_footer.home')}}</a>
                    </li>
                </ul>
            </nav>
            <h1 class="title">{{__('search-page.title', ['string'=> request()->get('search') ? '- ' . request()->get('search') : ''])}}</h1>
        </div>
        <div class="category-content wrap flex-justify">
            <div class="section-empty">
                <p>{{__('search-page.no_products')}}</p>
                <div class="search-options form-horizontal" id="search">
                    <div class="search-options-list">
                        <div class="item">
                            <input type="text" name="search" value="{{request()->get('search')}}" placeholder="{{__('search-page.keywords')}}" id="input-search" class="form-control">
                        </div>
                        <div class="item">

                            <select name="category_id" class="form-control">
                                <option value="0">{{__('search-page.categories')}}</option>
                                @foreach($categories->where('parent_id', null) as $category)
                                    @dump($category)
                                    <option value="{{$category->id}}" {{request()->get('category_id')==$category->id ? 'selected' : ''}}>&nbsp;&nbsp;{{$category->name}}</option>
                                    @if($category->hasChildren())
                                        @foreach($category->children as $children)
                                            <option {{request()->get('category_id')==$children->id ? 'selected' : ''}} value="{{$children->id}}">&nbsp;&nbsp;&nbsp;&nbsp;{{$children->name}}</option>
                                            @if($children->hasChildren())
                                                @foreach($children->children as $childrenChildren)
                                                    <option {{request()->get('category_id')==$childrenChildren->id ? 'selected' : ''}} value="{{$childrenChildren->id}}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$childrenChildren->name}}</option>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="item">
                            <div class="radio-checbox-options">
                                <input type="checkbox" name="sub_category" {{request()->get('sub_category') === "true" ? 'checked' : ''}} id="sub_category">
                                <label class="option-name" for="sub_category">{{__('search-page.search_in_sub_categories')}}</label>
                            </div>
                        </div>
                        <div class="item">
                            <div class="radio-checbox-options">
                                <input type="checkbox" name="description" {{request()->get('description')==="true" ? 'checked' : ''}} id="description">
                                <label class="option-name" for="description">{{__('search-page.search_in_description')}}</label>
                            </div>
                        </div>
                    </div>
                    <input type="button" value="{{__('search-page.search')}}" id="button-search" class="button colord btn btn-primary">
                </div>
            </div>
        </div>

    </section>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.7.0/nouislider.min.js"></script>
        <script src="{{mix('build/js/baseSearch.js')}}"></script>
    @endpush
    @push('styles')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.7.0/nouislider.min.css"/>
    @endpush
@endsection
