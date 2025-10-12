<div class="splide home-category flex-justify wrap" id="slideCategory">
    <div class="splide__track">
        <ul class="splide__list">
            @foreach($mainCategories as $mainCategory)
                <li class="splide__slide swiper-slide home-banner-item">
                    <a href="{{ route('products.category', ['category' => $mainCategory->slugEn]) }}"
                       class="swiper-slide home-category-item flex-justify" title="">
                        <figure class="img flex-center"><img loading="lazy" src="{{$mainCategory->getPreviewImage()}}"
                                                             srcset="" alt="" title=""></figure>
                        <div class="right">
                            <div class="name">{{$mainCategory->name}}</div>
                            <div class="link button"><i
                                    class="fas fa-chevron-right"></i>{{__('general-translate.view')}}</div>
                        </div>
                    </a>
                </li>
            @endforeach

        </ul>
    </div>

    <div class="splide__arrows home-category-buttons"></div>
</div>


