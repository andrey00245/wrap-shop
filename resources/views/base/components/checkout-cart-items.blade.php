@foreach($cartItems as $item)
    <tr class="item flex-justify">
        <td class="image">
            <a href="{{route('products.show', ['product' => $item['product']->slugEn])}}">
                <img loading="lazy"
                     src="{{$item['product']->getMedia('images')[0]->getUrl('preview_webp')}}"
                     alt="{{$item['product']->name}}"
                     title="{{$item['product']->name}}"
                     class="img-thumbnail">
            </a>
        </td>
        <td class="name">
            <span class="cat">{{$item['product']->category->name}}</span>
            <a href="{{route('products.show', ['product'=>$item['product']->slugEn])}}">{{$item['product']->name}}</a>
            <div class="options"></div>
        </td>
        <td class="model">11059</td>
        <td class="quantity">
            <div class="input-group-quantity-cart-xvr">
                <div class="pull-left">
                    {{__('checkout.count_full')}}
                </div>
            </div>
        </td>
        <td class="price">
            {{$item['product']->getPrice()}} ₴<span class="price-unit-xvr"></span>
        </td>
        <td class="total">
            {{$item['product']->getPriceByCount($item['quantity']) ?? $sum}}
            ₴<span class="count">x {{$item['product']->getRollSize() ? __('checkout.quantity_mp', ['quantity' => $item['quantity']]) : __('checkout.quantity_pc', ['quantity' => $item['quantity']])}}</span>
        </td>
    </tr>
@endforeach
