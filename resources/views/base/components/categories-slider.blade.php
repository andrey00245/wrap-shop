<div class="home-category flex-justify wrap" id="slideCategory" >
  <div class="swiper">
    <div class="swiper-wrapper">
      @foreach($mainCategories as $mainCategory)
        <a href="" class="swiper-slide home-category-item flex-justify" title="" >
          <figure class="img flex-center"><img loading="lazy" src="" srcset="" alt="" title=""></figure>
          <div class="right">
            <div class="name">{{$mainCategory->name}}</div>
            <div class="link button" ><i class="fas fa-chevron-right"></i>{{__('general-translate.view')}}</div>
          </div>
        </a>
      @endforeach
    </div>
  </div>
  <div class="swiper-button-next home-category-button button"></div>
  <div class="swiper-button-prev home-category-button button"></div>
</div>
