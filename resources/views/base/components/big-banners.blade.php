<div class="home-banner wrap" id="topBannersSlider">
  <div class="swiper">
    <div class="swiper-wrapper">
        @foreach($banners as $banner )
      <div class="swiper-slide home-banner-item">
        <a href="{{$banner->url}}">
          <img loading="lazy" src="{{$banner->getImage()}}" width="1312" height="450" alt="#" title="#">
        </a>
{{--        <img loading="lazy" src="#" alt="#" title="#" width="1312" height="450">--}}
      </div>
        @endforeach
    </div>
    <div class="home-banner-bott flex-justify">
      <div class="swiper-pagination"></div>
      <div class="home-slide-button">
        <div class="swiper-button-next button"></div>
        <div class="swiper-button-prev button"></div>
      </div>
    </div>
  </div>
</div>
