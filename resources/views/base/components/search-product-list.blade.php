<li class="disabled">
    <div class="search-suggestion ">
        <div class="center">
            <div class="title">{{__('popup.search_popup.products')}}</div>
        </div>
    </div>
</li>
@foreach($products as $product)
    <li><a href="{{route('products.show', ['product'=>$product->slugEn])}}">
            <div class="search-suggestion product">
                <div class="left">
                    <div class="image"><img
                            src="{{$product->getImage('preview_webp')}}">
                    </div>
                </div>
                <div class="center">
                    <div class="name ">{{$product->getName()}}</div>
                </div>
                <div class="right">
                    <div class="price "><span class="price-base">{{number_format($product->getPrice())}} ₴</span></div>
                </div>
            </div>
        </a>
    </li>
@endforeach

@if($count>0)
    <li class="more"><a href="{{route('search', ['search'=>request()->get('search')])}}">
            <div class="search-suggestion product">
                <div class="center">
                    <div class="more">{{__('popup.search_popup.show_more', ['count'=>$count])}}</div>
                </div>
            </div>
        </a>
    </li>
@endif
