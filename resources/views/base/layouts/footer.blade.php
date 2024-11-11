<section class="home-about">
  <img loading="lazy" src="{{asset('assets/img/about.webp')}}" title="Wrap-Shop" alt="Wrap-Shop" width="1512" height="549">

{{--    <video width="1512" height="549" autoplay muted loop>--}}
{{--        <source src="image/catalog/video-preview/1.mp4" type="video/mp4">--}}
{{--        Your browser does not support the video tag.--}}
{{--      </video>--}}


  <div class="home-about-text">
    <h2 class="title">Стайлінг – наше життя</h2>
    <p style="margin-bottom: 0; padding: 0; touch-action: manipulation;"><span style="font-size: 14px;">Wrap.Shop - ваш путівник у світ автомобільного вдосконалення та тюнінгу.</span></p>
    <p style="margin-bottom: 0; padding: 0; touch-action: manipulation;"><span style="font-size: 14px;">Кольорові та захисні плівки, інструменти для майстрів, продукти для догляду та детейлінгу, повний тюнінг авто - усе тут.</span></p>
  </div>

  <div class="home-about-play button" data-src="{{asset('assets/video/video.mp4')}}" data-fancybox="about-play">
    <i class="far fa-play button colord"></i>
  </div>
  <div class="home-about-play button" data-src="{{asset('assets/video/video.mp4')}}" data-fancybox="about-play">
    <i class="far fa-play button colord"></i>
  </div>
</section>


@include('base.components.login-registration')
@include('base.components.cart-popup')
@include('base.components.search-popup')

<footer class="footer">
  <div class="seo-wrapper">
    <div class="seo-content">
      <h2>Стайлінг – наше життя</h2>
      <p>WrapShop - це найбільша мережа магазинів і установчих центрів, де ви можете купити автомобільні плівки (антигравійна, тонувальна, атермальна, вінілова), інструмент для роботи з автомобільними плівками (тонувальний інструмент, турбосушка), матеріали для детейлінгу.</p>
      <p>Ми - піонери на ринку автомобільних плівок і були першими, хто привіз у 2008 році поліуретанову плівку в Україну. Ми є прямим імпортером і офіційним дистриб'ютором таких брендів, як 3M, SunTek, LLumar, PremiumShield, Madico, Meguiar's, Lake</p>
    </div>
    <span class="seo-btn show"><i class="fas fa-chevron-right"></i>{{__('header_footer.read_more')}}</span>
    <span class="seo-btn collapse" style="display: none"><i class="fas fa-chevron-right"></i>{{__('header_footer.read_less')}}</span>
  </div>
  <div class="ellipse orange"></div>
  <div class="wrap flex-justify">
    <div class="foot-info">
      <div class="foot-logo">
        <a href="{{route('index')}}" title="Wrap-Shop"><img loading="lazy" src="{{asset('assets/img/logo.png')}}" alt="Wrap-Shop" title="Wrap-Shop" width="118" height="124"></a>
      </div>
    </div>
    <nav class="foot-catalog foot-item">
      <ul class="footer-cat">
        <li>
          <a href="/plivky" title="Плівки для автомобіля">
            <figure><img loading="lazy" src="http://localhost:8888/image/cachewebp/catalog/category/wrap-180x140.webp" srcset="http://localhost:8888/image/cachewebp/catalog/category/wrap-360x280.webp 2x" alt="Плівки для автомобіля" title="Плівки для автомобіля" width="180" height="140"></figure>
            <div>
              <div class="name">Плівки для автомобіля</div>
            </div>
          </a>
        </li>
        <li>
          <a href="/materialy-dlya-detejlingu-avto" title="Матеріали для детейлінгу">
            <figure><img loading="lazy" src="http://localhost:8888/image/cachewebp/catalog/category/det-180x140.webp" srcset="http://localhost:8888/image/cachewebp/catalog/category/det-360x280.webp 2x" alt="Матеріали для детейлінгу" title="Матеріали для детейлінгу" width="180" height="140"></figure>
            <div>
              <div class="name">Матеріали для детейлінгу</div>
            </div>
          </a>
        </li>
        <li>
          <a href="/instrumenty-rozhidnyky" title="Інструменти / Розхідники">
            <figure><img loading="lazy" src="http://localhost:8888/image/cachewebp/catalog/category/tools-180x140.webp" srcset="http://localhost:8888/image/cachewebp/catalog/category/tools-360x280.webp 2x" alt="Інструменти / Розхідники" title="Інструменти / Розхідники" width="180" height="140"></figure>
            <div>
              <div class="name">Інструменти / Розхідники</div>
            </div>
          </a>
        </li>
        <li>
          <a href="/materialy-dlya-shumoizolyaciyi-avto" title="Матеріали для шумоізоляції авто">
            <figure><img loading="lazy" src="http://localhost:8888/image/cachewebp/catalog/category/sound-180x140.webp" srcset="http://localhost:8888/image/cachewebp/catalog/category/sound-360x280.webp 2x" alt="Матеріали для шумоізоляції авто" title="Матеріали для шумоізоляції авто" width="180" height="140"></figure>
            <div>
              <div class="name">Матеріали для шумоізоляції авто</div>
            </div>
          </a>
        </li>
        <li>
          <a href="/avtomobilni-kylymy-hand-made" title="Автомобільні килими Hand made">
            <figure><img loading="lazy" src="http://localhost:8888/image/cachewebp/catalog/category/swc-180x140.webp" srcset="http://localhost:8888/image/cachewebp/catalog/category/swc-360x280.webp 2x" alt="Автомобільні килими Hand made" title="Автомобільні килими Hand made" width="180" height="140"></figure>
            <div>
              <div class="name">Автомобільні килими Hand made</div>
            </div>
          </a>
        </li>
        <li>
          <a href="/zahysne-sklo-dlya-multymedia-avto" title="Захисне скло для мультимедіа авто">
            <figure><img loading="lazy" src="http://localhost:8888/image/cachewebp/catalog/category/glass-180x140.webp" srcset="http://localhost:8888/image/cachewebp/catalog/category/glass-360x280.webp 2x" alt="Захисне скло для мультимедіа авто" title="Захисне скло для мультимедіа авто" width="180" height="140"></figure>
            <div>
              <div class="name">Захисне скло для мультимедіа авто</div>
            </div>
          </a>
        </li>
        <li>
          <a href="/tuning" title="Тюнінг">
            <figure><img loading="lazy" src="http://localhost:8888/image/cachewebp/catalog/category/tun-180x140.webp" srcset="http://localhost:8888/image/cachewebp/catalog/category/tun-360x280.webp 2x" alt="Тюнінг" title="Тюнінг" width="180" height="140"></figure>
            <div>
              <div class="name">Тюнінг</div>
            </div>
          </a>
        </li>
        <li>
          <a href="/merch" title="Мерч">
            <figure><img loading="lazy" src="http://localhost:8888/image/cachewebp/catalog/category/merch-180x140.webp" srcset="http://localhost:8888/image/cachewebp/catalog/category/merch-360x280.webp 2x" alt="Мерч" title="Мерч" width="180" height="140"></figure>
            <div>
              <div class="name">Мерч</div>
            </div>
          </a>
        </li>
      </ul>
    </nav>
    <nav class="foot-menu foot-item">
      <div class="title">{{__('header_footer.information')}}</div>
      @include('base.layouts.menu')
    </nav>
    <div class="foot-add foot-item">
      <div class="title">{{__('header_footer.our_office')}}</div>
      <a href="#" target="_blank" title="32321321312">32321321312</a>

      <a href="#" target="_blank" title="32321321312">32321321312</a>

      <a href="#" target="_blank" title="32321321312">32321321312</a>

    </div>

    <div class="foot-contact foot-item">
      <div class="title">{{__('header_footer.contact_us')}}</div>
      <a href="tel:+38000000000" class="phone" ><span>+38</span>00000000</a>
      <a href="mailto:ааааааааааа" class="email" title="ааааааааааа" >ааааааааааа</a>
      <div class="social">
        <a href="#" title="Telegram" target="_blank" class="fab fa-telegram-plane"></a>
        <a href="#" title="Instagram" target="_blank" class="fab fa-instagram"></a>
      </div>
    </div>
    <div class="foot-copir">{{__('header_footer.all_rights_reserved', ['year'=> date('Y')])}}</div>
  </div>

  <div class="foot-form foot-item">
    <div class="wrap">
      <div class="title">{{__('header_footer.subscribe')}}</div>
{{--      <div id="ocdw_form_builder-block-2">--}}
{{--        <div class="inner-center">--}}
          <div class="alert-block"></div>
          <form id="ocdw_form_builder-form-2">
            <div class="inner-box">
              <div class="fields-block">
                <div data-error-row="5" data-field-type="email" class="field-col-1 ">
                  <div class="inner-field">
                    <input name="email" class="form-control" type="email" value="" placeholder="Email">
                  </div>
                </div>
              </div>
            </div>
            <button type="button" class="save-form button button-loading foot-form-send">{{__('header_footer.subscribe_btn')}}</button>
          </form>
{{--        </div>--}}
{{--      </div>--}}
    </div>
  </div>
</footer>


<div class="map" id="map"></div>
<div class="mobil-catalog-fixed wrap"><div class="mobil-catalog-open button"><i class="far fa-th-large"></i>Каталог товарів</div></div>
@push('scripts')

@endpush

<link rel="stylesheet" href="{{asset('assets/css/form.css')}}" media="screen">
<link rel="stylesheet" href="{{asset('third-party/fancybox/jquery.fancybox.min.css')}}" media="screen">

<script src="{{asset('js/jquery/jquery.min.js')}}"></script>
<script src="{{asset('js/jquery/swiper/js/swiper.jquery.min.js')}}"></script>
<script src="{{asset('third-party/fancybox/jquery.fancybox.min.js')}}"></script>
<script src="{{mix('build/js/app.js')}}" type="text/javascript"></script>
<script src="{{mix('build/js/mask.js')}}" type="text/javascript"></script>
<script src="{{mix('build/js/auth.js')}}" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js"></script>

@stack('scripts')


<script>
  function initMap() {
    const map = new google.maps.Map(document.getElementById("map"), {
      center: { lat: 50.4126427, lng: 30.5161722 },
      zoom: 12,
      mapId: "c0e797acbec89fee",
      disableDefaultUI: true,
    });

    const linkHref = document.createElement("a")
    linkHref.href="https://maps.app.goo.gl/EYgRqUsBU19i7m1k8";
    linkHref.target="_blank";

    const beachFlagImg = document.createElement("img");
    beachFlagImg.src = "{{asset('assets/img/marker.png')}}";
    beachFlagImg.width = "122";
    beachFlagImg.height = "130";

    linkHref.appendChild(beachFlagImg);

    const markerView = new google.maps.marker.AdvancedMarkerView({
      map,
      position: { lat: 50.411282921548, lng: 30.51737119923972 },
      content: linkHref,
      title: "Wrap Shop",
    });
  }
  window.initMap = initMap;
  // map
  window.onload = function() {
    setTimeout(() => {
      $('#map-script').attr('src', 'https://maps.googleapis.com/maps/api/js?key={{config('app.google_map_key')}}&callback=initMap&libraries=marker,places&v=beta&language={{LaravelLocalization::getCurrentLocale()}}');
    },3000);
  }
</script>
<script id="map-script" defer></script>
<script>
  let btn = document.getElementById("theme-button");
  let btn1 = document.getElementById("theme-button1");
  let link = document.getElementById("theme-link");
  let link1 = document.getElementById("theme-link-category");
  let link2 = document.getElementById("theme-link-product");
  let link3 = document.getElementById("theme-link-information");
  let link4 = document.getElementById("theme-link-blog");
  let link5 = document.getElementById("theme-link-account");
  let link6 = document.getElementById("theme-link-form");
  let link7 = document.getElementById("theme-link-simple");
  let link8 = document.getElementById("theme-link-fastorder");
  let link9 = document.getElementById("theme-link-ocfilter");

  $(document).ready(function($) {

    $('.category-products-item .swiper-slide').css('display', 'flex');
  });

  let seoContentHeight = 104;
  let seoContent = $('.seo-content');
  seoContent.css("height", "auto");
  let outerContentsHeight = seoContent.height();
  seoContent.css("height", seoContentHeight+"px");

  $(window).on('resize',function(){
    seoContent.css("height", "auto");
    outerContentsHeight = seoContent.height();
    if( seoContent.hasClass('open')){
      seoContent.css('height', outerContentsHeight+'px')
    }
    else {
      seoContent.css('height', seoContentHeight+'px')
    }
  })

  $('.seo-btn.show').click(function () {
    $('.seo-btn.show').hide();
    $('.seo-btn.collapse').show()
    seoContent.toggleClass('open');
    showCollapseSeoContent()
  })

  $('.seo-btn.collapse').click(function () {
    $('.seo-btn.show').show();
    $('.seo-btn.collapse').hide()
    seoContent.toggleClass('open');
    showCollapseSeoContent()
  })

  function showCollapseSeoContent(){
    if( seoContent.hasClass('open')){
      seoContent.css('height', outerContentsHeight+'px')
    }
    else {
      seoContent.css('height', seoContentHeight+'px')
    }
  }

  // mask("#tel-phone-loginpopup");
  // mask("#input-telephone");

  $('input[type="tel"]').on('click', function() {
    $(this).prev('.notice-text').remove();
    $(this).before('<span class="notice-text">{{__('header_footer.phone_notice')}}</span>');
  });

  $('input[type="tel"]').on('blur', function() {
    $(this).prev('.notice-text').remove();
  });
</script>
<script src="//code.tidio.co/qzgrxeqydewl3hb2slszufoiu8sb9we7.js" async></script>
<script type="text/javascript" async>
  (function(d, w, s) {
    var widgetHash = 'mayoy83q3bdx7xddnztw', gcw = d.createElement(s); gcw.type = 'text/javascript'; gcw.async = true;
    gcw.src = '//widgets.binotel.com/getcall/widgets/'+ widgetHash +'.js';
    var sn = d.getElementsByTagName(s)[0]; sn.parentNode.insertBefore(gcw, sn);
  })(document, window, 'script');
</script>
