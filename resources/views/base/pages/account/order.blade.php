@extends('base.pages.account.layout')

@section('title', __('personal-account.order-history.meta_title'))

@section('account.content')
  <h1>{{__('personal-account.order-history.title')}}</h1>

  <div class="order-list">
      @foreach(auth()->user()->orders as $order)
    <div class="order-list-item">
      <div class="info flex-justify">
        <div class="item icon"><i class="fal fa-box-full"></i></div>
        <div class="item order"><span class="label">{{__('personal-account.order-history.order-id')}}</span> {{$order->id}}</div>
        <div class="item data"><span class="label">{{__('personal-account.order-history.date-added')}}</span>
            {{ $order->created_at->format('d-m-Y') }}
        </div>
        <div class="item price"><span class="label">{{__('personal-account.order-history.total')}}</span> 0.00 ₴</div>
        <div class="item status">{{__('personal-account.order-history.statuses.new')}}</div>
        <div class="item button" data-id="#order-product-817"><i class="fal fa-chevron-down"></i></div>
      </div>
        @foreach($order->products as $product)
      <div class="products" id="order-product-817">
        <div class="item flex-justify">
          <div class="image"><a href="https://wrap.shop/en/tuning/diski/komplekt-diskv-brabus-monoblock-f-black-platinum"><img data-src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/0fc38704-b644-11ee-0a80-033a0001d519_0-415x415.webp" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/0fc38704-b644-11ee-0a80-033a0001d519_0-415x415.webp"></a></div>
          <div class="name">
            <span class="model"></span>
            <a href="https://wrap.shop/en/tuning/diski/komplekt-diskv-brabus-monoblock-f-black-platinum">Комплект дисків Brabus MONOBLOCK F "BLACK PLATINUM"</a>
            <div class="option"></div>
          </div>
          <div class="price">0.00 ₴<span class="price-unit-xvr"></span></div>
          <div class="quantity">1.000</div>
          <div class="total">0.00 ₴</div>
          <div class="reorder"></div>
          <div class="return"><a href="">&nbsp;</a></div>
        </div>
      </div>
        @endforeach
    </div>
      @endforeach
  </div>

  <div class="flex-justify" id="review-result">
    <div class="item-result">{{__('personal-account.order-history.showing_from_pages', ['num_1' => 1, 'num_2' => 1, 'num_3' => 1, 'num_4' => 1,])}}</div>
    <div class="item-pagination"></div>
  </div>

@if(auth()->user()->orders()->count() == 0)
  <p>{{__('personal-account.order-history.you_have_not_shopped_yet')}}</p>
@endif
  <div class="buttons clearfix">
    <div class="pull-right"><a href="{{route('account')}}" class="btn btn-primary">{{__('personal-account.order-history.continue')}}</a></div>
  </div>

  @push('scripts')
    <script>
      $(document).ready(function(){
        $(".order-list-item .info .item.button").on("click", function(){
          var activId = $(this).attr("data-id");
          $(activId).slideToggle();
        });
      });
    </script>
  @endpush
@endsection
@push('fixed-catalog')
  @include('base.components.categories-catalog')
@endpush
