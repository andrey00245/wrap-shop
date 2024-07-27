<div class="home-banner wrap" id="swiper">
  <div class="swiper">
    <div class="swiper-wrapper">
      <div class="swiper-slide home-banner-item">
        <a href="#">
          <img loading="lazy" src="https://wrap.shop/image/catalog/category/webp/kybertane_webp.webp" width="1312" height="450" alt="#" title="#">
        </a>
{{--        <img loading="lazy" src="#" alt="#" title="#" width="1312" height="450">--}}
      </div>
      <div class="swiper-slide home-banner-item">
        <a href="#">
          <img loading="lazy" src="https://wrap.shop/image/catalog/category/webp/kybertane_webp.webp" width="1312" height="450" alt="#" title="#">
        </a>
{{--                <img loading="lazy" src="#" alt="#" title="#" width="1312" height="450">--}}
      </div>
      <div class="swiper-slide home-banner-item">
        <a href="#">
          <img loading="lazy" src="https://wrap.shop/image/catalog/category/webp/kybertane_webp.webp" width="1312" height="450" alt="#" title="#">
        </a>
{{--                <img loading="lazy" src="#" alt="#" title="#" width="1312" height="450">--}}
      </div>
      <div class="swiper-slide home-banner-item">
        <a href="#">
          <img loading="lazy" src="https://wrap.shop/image/catalog/category/webp/kybertane_webp.webp" width="1312" height="450" alt="#" title="#">
        </a>
{{--                <img loading="lazy" src="#" alt="#" title="#" width="1312" height="450">--}}
      </div>
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

@push('scripts')
<script src="{{asset('js/jquery/jquery.min.js')}}"></script>
<script src="{{asset('js/jquery/swiper/js/swiper.jquery.min.js')}}"></script>
<script type="text/javascript">
  jQuery(document).ready(function ($) {
    jQuery('.home-banner .swiper-slide').css('display', 'block');
    var homeBanner = new Swiper("#swiper .swiper", {
      navigation: {
        nextEl: "#swiper .swiper-button-next",
        prevEl: "#swiper .swiper-button-prev",
      },
      pagination: {
        el: "#swiper .swiper-pagination",
        clickable: true,
      },
      lazy: true,
      autoplay: {
        delay: 3000,
      },
    });
  });
</script>
@endpush
