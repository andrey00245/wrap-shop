<section class="home-about">
  <img loading="lazy" src="{{asset('assets/img/about.webp')}}" title="Wrap-Shop" alt="Wrap-Shop" width="1512"
       height="549">

  {{--    <video width="1512" height="549" autoplay muted loop>--}}
  {{--        <source src="image/catalog/video-preview/1.mp4" type="video/mp4">--}}
  {{--        Your browser does not support the video tag.--}}
  {{--      </video>--}}


  <div class="home-about-text">
    <h2 class="title">{{$settings->getVideoBannerTitle()}}</h2>
    {!! $settings->getVideoBannerDesc() !!}
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
      <h2>{{$settings->getSloganTitle()}}</h2>
      {!! $settings->getSloganDesc() !!}
    </div>
    <span class="seo-btn show"><i class="fas fa-chevron-right"></i>{{__('header_footer.read_more')}}</span>
    <span class="seo-btn collapse" style="display: none"><i class="fas fa-chevron-right"></i>{{__('header_footer.read_less')}}</span>
  </div>
  <div class="ellipse orange"></div>
  <div class="wrap flex-justify">
    <div class="foot-info">
      <div class="foot-logo">
        <a href="{{route('index')}}" title="Wrap-Shop"><img loading="lazy" src="{{asset('assets/img/logo.png')}}"
                                                            alt="Wrap-Shop" title="Wrap-Shop" width="118" height="124"></a>
      </div>
    </div>
    <nav class="foot-catalog foot-item">
      <ul class="footer-cat">
        @foreach($mainCategories as $category)
          <li>
            <a href="{{route('products.category', ['category' => $category->slugEn])}}" title="{{$category->name}}">
              <figure><img loading="lazy" src="{{$category->getImage()}}" srcset="{{$category->getImage()}}"
                           alt="{{$category->name}}" title="{{$category->name}}" width="180" height="140"></figure>
              <div>
                <div class="name">{{$category->name}}</div>
              </div>
            </a>
          </li>
        @endforeach
      </ul>
    </nav>
    <nav class="foot-menu foot-item">
      <div class="title">{{__('header_footer.information')}}</div>
      @include('base.layouts.menu')
    </nav>
    <div class="foot-add foot-item">
      <div class="title">{{__('header_footer.our_office')}}</div>
      <a href="{{$settings->getGoogleMapsUrl()}}" target="_blank"
         title="{{$settings->getAddress()}}">{{$settings->getAddress()}}</a>

    </div>

    <div class="foot-contact foot-item">
      <div class="title">{{__('header_footer.contact_us')}}</div>
      <a href="tel:{{$settings->getPhone()}}" class="phone"> {!! $settings->getPhoneView() !!}</a>
      <a href="mailto:{{$settings->getEmail()}}" class="email" title="Email">{{$settings->getEmail()}}</a>
      <div class="social">
        <a href="{{$settings->getTelegramUrl()}}" title="Telegram" target="_blank" class="fab fa-telegram-plane"></a>
        <a href="{{$settings->getInstagramUrl()}}" title="Instagram" target="_blank" class="fab fa-instagram"></a>
      </div>
    </div>
    <div class="foot-copir">{{__('header_footer.all_rights_reserved', ['year'=> date('Y')])}}</div>
  </div>

  <div class="foot-form foot-item">
    <div class="wrap">
      <div class="title">{{__('header_footer.subscribe')}}</div>
      <div class="foot-form-thanks flex-center hide">
        <i class="fas fa-smile"></i>
        <span>{!! __('subscription.subscribe_success') !!}</span>
      </div>

      <div class="form-error-container">
        <form id="subscription-form" action="{{route('subscribe')}}" method="POST">
          @csrf
          <input name="email"
                 class="form-control"
                 type="email"
                 value="{{auth()->check() ? auth()->user()->email : ''}}"
                 placeholder="Email">
          <button type="submit"
                  class="save-form button button-loading foot-form-send">
            {{__('header_footer.subscribe_btn')}}
          </button>
        </form>
        <div class="error-text"></div>
      </div>
    </div>
  </div>
</footer>


<div class="map" id="map"></div>
@stack('fixed-catalog')

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
      center: {lat: 50.4126427, lng: 30.5161722},
      zoom: 12,
      mapId: "c0e797acbec89fee",
      disableDefaultUI: true,
    });

    const linkHref = document.createElement("a")
    linkHref.href = "https://maps.app.goo.gl/EYgRqUsBU19i7m1k8";
    linkHref.target = "_blank";

    const beachFlagImg = document.createElement("img");
    beachFlagImg.src = "{{asset('assets/img/marker.png')}}";
    beachFlagImg.width = "122";
    beachFlagImg.height = "130";

    linkHref.appendChild(beachFlagImg);

    const markerView = new google.maps.marker.AdvancedMarkerView({
      map,
      position: {lat: 50.411282921548, lng: 30.51737119923972},
      content: linkHref,
      title: "Wrap Shop",
    });
  }

  window.initMap = initMap;
  // map
  window.onload = function () {
    setTimeout(() => {
      $('#map-script').attr('src', 'https://maps.googleapis.com/maps/api/js?key={{config('app.google_map_key')}}&callback=initMap&libraries=marker,places&v=beta&language={{LaravelLocalization::getCurrentLocale()}}');
    }, 3000);
  }
</script>
<script id="map-script" defer></script>
<script>

  $('input[type="tel"]').on('click', function () {
    $(this).prev('.notice-text').remove();
    $(this).before('<span class="notice-text">{{__('header_footer.phone_notice')}}</span>');
  });

  $('input[type="tel"]').on('blur', function () {
    $(this).prev('.notice-text').remove();
  });
</script>
<script src="//code.tidio.co/qzgrxeqydewl3hb2slszufoiu8sb9we7.js" async></script>
<script type="text/javascript" async>
  (function (d, w, s) {
    var widgetHash = 'mayoy83q3bdx7xddnztw', gcw = d.createElement(s);
    gcw.type = 'text/javascript';
    gcw.async = true;
    gcw.src = '//widgets.binotel.com/getcall/widgets/' + widgetHash + '.js';
    var sn = d.getElementsByTagName(s)[0];
    sn.parentNode.insertBefore(gcw, sn);
  })(document, window, 'script');
</script>
