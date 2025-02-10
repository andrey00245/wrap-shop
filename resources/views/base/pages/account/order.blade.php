@extends('base.pages.account.layout')

@section('title', __('personal-account.order-history.meta_title'))

@section('account.content')
  <h1>{{__('personal-account.order-history.title')}}</h1>
  @foreach(auth()->user()->orders as $order)
  <div class="order-list">

    <div class="order-list-item">
      <div class="info flex-justify">
        <div class="item icon"><i class="fal fa-box-full"></i></div>
        <div class="item order"><span class="label">{{__('personal-account.order-history.order-id')}}</span> {{$order->id}}</div>
        <div class="item data"><span class="label">{{__('personal-account.order-history.date-added')}}</span>
            {{ $order->created_at->format('d-m-Y') }}
        </div>
        <div class="item price"><span class="label">{{__('personal-account.order-history.total')}}</span> {{$order->total}} ₴</div>
        <div class="item status">{{__('personal-account.order-history.statuses.new')}}</div>
        <div class="item button" data-id="#order-product-{{$order->id}}"><i class="fal fa-chevron-down"></i></div>
      </div>

      <div class="products" id="order-product-{{$order->id}}">
          @foreach($order->products as $product)
        <div class="item flex-justify">
          <div class="image"><a href="{{route('products.show',['product' => $product->id])}}"><img data-src="{{$product->getMedia('images')[0]->getUrl('preview')}}" src="{{$product->getMedia('images')[0]->getUrl('preview')}}"></a></div>
          <div class="name">
            <span class="model"></span>
            <a href="{{route('products.show',['product' => $product->id])}}">{{$product->name}}</a>
            <div class="option"></div>
          </div>
          <div class="price">{{$product->pivot->price}} ₴<span class="price-unit-xvr"></span></div>
          <div class="quantity">{{$product->pivot->quantity}}</div>
          <div class="total">{{$product->getPriceByCount($product->pivot->quantity)}} ₴</div>
          <div class="reorder"></div>
          <div class="return"><a href="">&nbsp;</a></div>
        </div>
          @endforeach
      </div>
    </div>

  </div>
  @endforeach
  <div class="flex-justify" id="review-result">
{{--    <div class="item-result">{{__('personal-account.order-history.showing_from_pages', ['num_1' => 1, 'num_2' => 1, 'num_3' => 1, 'num_4' => 1,])}}</div>--}}
    <div class="item-pagination"></div>
  </div>

@if(auth()->user()->orders()->count() == 0)
  <p>{{__('personal-account.order-history.you_have_not_shopped_yet')}}</p>
@endif
  <div class="buttons clearfix">
    <div class="pull-right"><a href="{{route('index')}}" class="btn btn-primary">{{__('personal-account.order-history.continue')}}</a></div>
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
