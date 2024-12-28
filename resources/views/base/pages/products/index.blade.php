@extends('base.layouts.app')
@section('content')

  @push('styles')
    <link rel="stylesheet" href="{{mix('build/css/all-dark.css')}}">
    <link rel="stylesheet" href="{{mix('build/css/style-category-dark.css')}}">
  @endpush
  <section class="category-page row">
    <div class="category-top">
      <img class="products-background" src="{{asset('assets/img/products/fon_wrap_webp.webp')}}" alt="">
      <div class="products-background"></div>
      <nav class="category-breadcrumbs">
        <ul class="flex-center">
          <li><a href="{{route('index')}}" title="{{__('header_footer.home')}}" class="button"><i class="far fa-chevron-left"></i>{{__('header_footer.home')}}</a>
          </li>
          @if(isset($category) && isset($subcategory))
            <li><a href="{{route('products.category', ['category' => $category->slugEn])}}" title="{{$category->name}}"
                   class="button"><i class="far fa-chevron-left"></i>{{$category->name}}</a></li>
          @endif
          @if(isset($category) && isset($subcategory) && isset($subsubcategory))
            <li><a
                href="{{route('products.category', ['category' => $category->slugEn, 'subcategory' => $subcategory->slugEn])}}"
                title="{{$subcategory->name}}" class="button"><i class="far fa-chevron-left"></i>{{$subcategory->name}}
              </a></li>
          @endif
        </ul>
      </nav>
      @if(isset($subsubcategory))
        <h1 class="title">{{$subsubcategory->name ?? ''}}</h1>
      @elseif(isset($subcategory))
        <h1 class="title">{{$subcategory->name ?? ''}}</h1>
      @else
        <h1 class="title">{{$category->name ?? ''}}</h1>
      @endif
      @if(isset($category))
        <nav class="category-child-nav">
          <ul>
            @foreach($childrenCategories as $childrenCategory)
              @if($category && !$subcategory)
                <li>
                  <a href="{{route('products.category', ['category' => $category->slugEn, 'subcategory'=>$childrenCategory->slugEn])}}"
                     title="{{$childrenCategory->name}}"
                     class="flex-center">
                    {{$childrenCategory->name}}
                  </a>
                </li>
              @elseif($category && $subcategory && !$subsubcategory)
                <li>
                  <a href="{{route('products.category', ['category' => $category->slugEn, 'subcategory' => $subcategory->slugEn, 'subsubcategory'=>$childrenCategory->slugEn])}}"
                    title="{{$childrenCategory->name}}"
                    class="flex-center">{{$childrenCategory->name}}</a>
                </li>
              @endif
            @endforeach
          </ul>
        </nav>
      @endif
    </div>
    <div class="category-content wrap flex-justify">
      <div class="category-left">
        <div class="search-left">
          <div class="title">{{__('product-index.find-in-catalog')}}</div>
          <div id="search" class="flex-justify">
            <input type="text" name="search1" value="" placeholder="{{__('product-index.i_am_looking_for')}}:" class="form-control input-lg">
            <button type="button" class="button colord btn btn-default btn-lg"><i class="fal fa-search"></i>{{__('product-index.search')}}
            </button>
          </div>
        </div>
        <div class="ocf-container ocf-theme-light ocf-mobile-1 ocf-mobile-left ocf-vertical ocf-left"
             id="ocf-module-1">
          <link rel="stylesheet" href="">

          <div class="ocf-content">
            <div class="ocf-header">
              {{__('product-index.filter')}}
              <button type="button" data-ocf="mobile" class="ocf-close-mobile" aria-label="Close filter"><i
                  class="fal fa-times"></i></button>
            </div>

            <div class="ocf-body">
              <div class="ocf-filter-list ocf-clearfix">


                <div class="ocf-filter ocf-dropdown ocf-open" id="ocf-filter-2-0-1">
                  <div class="ocf-filter-body">
                    <div class="ocf-filter-header" data-ocf="expand">
                      <span class="ocf-active-label"></span>
                      <span class="ocf-filter-name">{{__('product-index.price')}}</span>
                      <span class="ocf-filter-header-append">
                        <span class="ocf-filter-discard ocf-icon ocf-icon-16 ocf-minus-circle" data-ocf-discard="2.0"></span>
                        <span class="ocf-plus-minus"></span>
                      </span>
                    </div>
                    <div class="ocf-filter-collapse ocf-collapse ocf-in">
                      <div class="ocf-input-group ocf-slider-input-group">
                        <input type="number" name="min_price" min="{{$minPrice}}" max="{{$maxPrice}}" value="{{$minPrice}}"
                               class="ocf-form-control ocf-grouping ocf-min"
                               autocomplete="off" aria-label="{{__('product-index.price')}}">
                        <input type="number" name="max_price" min="{{$minPrice}}" max="{{$maxPrice}}" value="{{$maxPrice}}"
                               class="ocf-form-control ocf-grouping ocf-max"
                               autocomplete="off" aria-label="{{__('product-index.price')}}">
                      </div>

                      <div class="ocf-value-list">
                        <div class="ocf-value-list-body">
                          @for($i = 0; $i<4;$i++)
                            <button type="button" class="ocf-value ocf-radio"
                                    data-value-min="{{$minPrice + $step*$i}}"
                                    data-value-max="{{$minPrice + $step*($i+1)}}">
                              <span class="ocf-value-input ocf-value-input-radio"></span>
                              <span class="ocf-value-name">{{$minPrice + $step*$i}} - {{$minPrice + $step*($i+1)}} ₴</span>
                            </button>
                          @endfor
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                  @foreach($attributes as $attribute)
                      @if($attribute->field_name === 'main_shade')
                          <div class="ocf-filter ocf-open ocf-dropdown">
                              <div class="ocf-filter-body">
                                  <div class="ocf-filter-header" data-ocf="expand">
                                      <span class="ocf-active-label"></span>
                                      <span class="ocf-filter-name">{{$attribute->name}}</span>
                                      <span class="ocf-filter-header-append">
                                        <span class="ocf-filter-discard ocf-icon ocf-icon-16 ocf-minus-circle"
                                              data-ocf-discard="40.2"></span>
                                        <span class="ocf-plus-minus"></span>
                                      </span>
                                  </div><!-- /.ocf-filter-header -->
                                  <div class="ocf-filter-collapse ocf-collapse ocf-in">
                                      <div class="ocf-value-list">
                                          <div class="ocf-scroll-y">
                                              <div class="ocf-value-list-body">
                                                  @foreach($attribute->getPivotValue() as $value)
                                                  <button type="button"
                                                          class="ocf-value ocf-checkbox filterProducts" data-filter="{{$value}}" data-filter-type="{{$attribute->field_name}}">
                                                      <span class="ocf-value-color"
                                                            style="background-color: {{__('colors.' . $value)}};"></span>

                                                      <span class="ocf-value-name">{{$value}}</span>
                                                      <span class="ocf-value-append">
    <span class="ocf-value-count">{{$attribute->getDefaultProductsCount($attribute->id, $value)}}</span>
  </span>
                                                  </button>
                                                  @endforeach
                                              </div>

                                          </div>

                                      </div>
                                  </div>
                              </div>
                          </div>
                      @endif
                  @endforeach
                @foreach($attributes as $attribute)
                      @if($attribute->field_name !== 'main_shade')
                          <div class="ocf-filter ocf-dropdown" id="ocf-filter-86-2-1">
                              <div class="ocf-filter-body">
                                  <div class="ocf-filter-header" data-ocf="expand">
                                      <span class="ocf-active-label"></span>

                                      <span class="ocf-filter-name">{{$attribute->name}}</span>

                                      <span class="ocf-filter-header-append">

        <span class="ocf-filter-discard ocf-icon ocf-icon-16 ocf-minus-circle" data-ocf-discard="86.2"></span>

        <span class="ocf-plus-minus"></span>
      </span>
                                  </div><!-- /.ocf-filter-header -->
                                  <div class="ocf-filter-collapse ocf-collapse">


                                      <div class="ocf-value-list">
                                          <div class="ocf-value-list-body">
                                              @foreach($attribute->getPivotValue() as $value)
                                                  <button type="button" class="ocf-value ocf-checkbox filterProducts"
                                                          data-filter="{{$value}}" data-filter-type="{{$attribute->field_name}}">
                                                      <span class="ocf-value-input ocf-value-input-checkbox"></span>
                                                      <span class="ocf-value-name">{{$value}}</span>
                                                      <span class="ocf-value-append">
            <span class="ocf-value-count">{{$attribute->getDefaultProductsCount($attribute->id, $value)}}</span>
        </span>
                                                  </button>
                                              @endforeach
                                          </div>


                                      </div>
                                  </div>
                              </div>
                          </div>
                      @endif
                @endforeach
              </div>
            </div>
            <div class="ocf-footer">
              <div class="ocf-between">
                <button type="button" data-ocf-discard="*" class="ocf-btn ocf-btn-link ocf-disabled"
                        disabled="disabled">Скасувати
                </button>

                <button type="button" class="ocf-btn ocf-disabled ocf-btn-block" data-ocf="button"
                        data-loading-text="Завантаження..." disabled="disabled">Виберіть фільтри
                </button>

              </div>
            </div>
          </div><!-- /.ocf-content -->

          <div class="ocf-is-mobile"></div>

          <div class="ocf-btn-mobile-fixed ocf-mobile ocf-hidden">
            <button type="button" class="ocf-btn ocf-btn-default" data-ocf="mobile" aria-label="Фільтри">
              <span class="ocf-btn-name">Фільтри</span>
              <i class="ocf-icon ocf-icon-16 ocf-brand ocf-sliders"></i>
            </button>
          </div>

          <div class="ocf-hidden">
            <button class="ocf-btn ocf-search-btn-popover" data-ocf="button" data-loading-text="Завантаження...">
              Виберіть фільтри
            </button>
          </div>
        </div>

      </div>
      <div class="category-right" id="content">

        <div class="category-right-top flex-justify">
          <div class="category-sort flex-justify">
            <div class="label">Сортувати за:</div>
            <div class="category-sort-close button"><i class="fal fa-times"></i></div>
            <div class="list">
              <a href="{{route('products.category', [
                  'category' => $category->slugEn,
                  'subcategory' => $subcategory ? $subcategory->slugEn : null,
                  'subsubcategory' => $subsubcategory ? $subsubcategory->slugEn : null,
                  ])}}"
                 class="button {{!request()->get('sort_by') && !request()->get('sort_direction') ? 'active' : null}}"
                 title="Замовчуванням">Замовчуванням</a>
              <a href="{{route('products.category', [
                  'category' => $category->slugEn,
                  'subcategory' => $subcategory ? $subcategory->slugEn : null,
                  'subsubcategory' => $subsubcategory ? $subsubcategory->slugEn : null,
                  'sort_by' => 'name',
                  'sort_direction' => 'asc',
                  ])}}" class="button {{request()->get('sort_by') === 'name' && request()->get('sort_direction') === 'asc' ? 'active' : null}}"
                 title="Алфавітом">Алфавітом</a>
              <a href="{{route('products.category', [
                  'category' => $category->slugEn,
                  'subcategory' => $subcategory ? $subcategory->slugEn : null,
                  'subsubcategory' => $subsubcategory ? $subsubcategory->slugEn : null,
                  'sort_by' => 'price',
                  'sort_direction' => 'asc',
                  ])}}" class="button {{request()->get('sort_by') === 'price' && request()->get('sort_direction') === 'asc' ? 'active' : null}}" title="Зростанням ціни">Зростанням
                ціни</a>
              <a href="{{route('products.category', [
                  'category' => $category->slugEn,
                  'subcategory' => $subcategory ? $subcategory->slugEn : null,
                  'subsubcategory' => $subsubcategory ? $subsubcategory->slugEn : null,
                  'sort_by' => 'price',
                  'sort_direction' => 'desc',
                  ])}}" class="button {{request()->get('sort_by') === 'price' && request()->get('sort_direction') === 'desc' ? 'active' : null}}" title="Спаданням ціни">Спаданням
                ціни</a>
              <a href="{{route('products.category', [
                  'category' => $category->slugEn,
                  'subcategory' => $subcategory ? $subcategory->slugEn : null,
                  'subsubcategory' => $subsubcategory ? $subsubcategory->slugEn : null,
                  'sort_by' => 'is_top_seller',
                  'sort_direction' => 'desc',
                  ])}}" class="button {{request()->get('sort_by') === 'is_top_seller' && request()->get('sort_direction') === 'desc' ? 'active' : null}}" title="Найпопулярнішими">Найпопулярнішими</a>
              <a href="{{route('products.category', [
                  'category' => $category->slugEn,
                  'subcategory' => $subcategory ? $subcategory->slugEn : null,
                  'subsubcategory' => $subsubcategory ? $subsubcategory->slugEn : null,
                  'sort_by' => 'created_at',
                  'sort_direction' => 'desc',
                  ])}}" class="button {{request()->get('sort_by') === 'created_at' && request()->get('sort_direction') === 'desc' ? 'active' : null}}"
                 title="Датою додавання">Датою додавання</a>
            </div>
          </div>
          <div class="category-view flex-justify">
            <div class="button active" id="grid-view"><i class="fas fa-th"></i></div>
            <div class="button" id="list-view"><i class="fas fa-th-list"></i></div>
          </div>
        </div>

        <div class="category-button flex-justify">
          <div class="button-filtr category-filtr-open button" data-ocf="mobile"><i class="fal fa-filter"></i>Фільтри
          </div>
          <div class="button-sort category-sort-open button"><i class="fal fa-sort-alt"></i>Сортування</div>
        </div>
        <div class="category-products flex-wrap">
          <a href="https://wrap.shop/plivky/kolorovi-plivky/?ocf=F1S0V11" title="3m color"
             class="category-products-item product-default product-layout banner product-grid">
            <img class="vertical" src="https://wrap.shop/image/catalog/category/webp/banners%20catalog/1-3m-vert.webp"
                 alt="3m color" title="3m color">
            <img class="gorizont" src="https://wrap.shop/image/catalog/category/webp/banners%20catalog/1-3m-gor.webp"
                 alt="3m color" title="3m color">
          </a>
          @foreach($products as $product)
            <div class="category-products-item product-default product-layout product-grid"
                 id="categoryProductsItem{{$product->id}}">
                @if($product->is_top_seller)
              <div class="sale statuses list">
                <div class="category-status category-status-1 status-inline text rectangle "
                     style=" color:#ffffff; background-color:#d04b4b;">
                  {{__('general-translate.sales_hit')}}
                </div>
              </div>
                @endif
              <div class="product-default-texts-wrapper">

                <div class="top flex-justify">
                    @if($product->is_top_seller)
                  <div class="sale statuses grid">
                    <div class="category-status category-status-1 status-inline text rectangle "
                         style=" color:#ffffff; background-color:#d04b4b;">
                      {{__('general-translate.sales_hit')}}
                    </div>
                  </div>
                    @endif
                  <div class="sku">{{__('general-translate.product_card.code')}} {{$product->code}}</div>
                  <div class="review grid">
                    <i class="fal fa-star"></i>
                    <i class="fal fa-star"></i>
                    <i class="fal fa-star"></i>
                    <i class="fal fa-star"></i>
                    <i class="fal fa-star"></i>
                    <div class="rating-result" style="width: 100%">
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                    </div>
                  </div>
                  <div class="wishlist">
                    <button type="button" class="button far fa-heart"
                            title="{{__('general-translate.product_card.add_wishlist')}}"></button>
                  </div>
                </div>
              </div>

              <div class="image swiper swiper-initialized swiper-horizontal swiper-android swiper-backface-hidden">
                <i class="far fa-search-plus colord" data-src="{{$product->getImage()}}"
                   data-fancybox="product-gallery{{$product->id}}" data-caption="{{$product->name}}"></i>
                <a href="{{route('products.show', ['product' => $product->id])}}" title="{{$product->name}}"
                   class="swiper-wrapper">

                  @foreach($product->getMedia('images') as $key => $image)
                    <div class="swiper-slide item flex-center swiper-slide-active" role="group">

                      @if($key>0)
                        <div class="hide"
                             data-src="{{$image->getUrl()}}"
                             data-fancybox="product-gallery{{$product->id}}"
                             data-caption="{{$product->name}}"></div>
                      @endif
                      <img loading="lazy"
                           src="{{$image->getUrl('preview')}}"
                           alt="{{$product->name}}"
                           title="{{$product->name}}" class="swiper-lazy swiper-lazy-loaded"
                           width="310" height="310">
                    </div>
                  @endforeach
                </a>
                {{--                <div class="swiper-button-next button" tabindex="0" role="button" aria-label="Next slide"></div>--}}
                {{--                <div class="swiper-button-prev button" tabindex="-1" role="button" aria-label="Previous slide"></div>--}}
                <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
              <div class="product-default-texts-wrapper list">

                <div class="center">
                  <div class="category">{{$product->category?->name}}</div>
                  <a href="{{route('products.show', ['product' => $product->id])}}" title="{{$product->name}}"
                     class="name">{{$product->name}}</a>
                </div>
                <div class="bottom flex-center">
                  <div class="price">{{number_format($product->getPrice())}} ₴<span
                      class="price-unit-xvr"></span></div>
                  <button class="button colord remarketing_cart_button" data-product_id="{{$product->id}}"><i
                      class="fas fa-chevron-right"></i>{{__('general-translate.product_card.add_to_cart')}}</button>
                </div>
              </div>
              {{--              <div class="params">--}}
              {{--                <div class="item flex-column">Призначення <span class="label">декоративна</span></div>--}}
              {{--                <div class="item flex-column">Структура <span class="label">сатинова</span></div>--}}
              {{--              </div>--}}
            </div>

          @endforeach

        </div>

        <div class="category-bottom flex-center">
          <div class="category-pagination">
            @if ($products->hasPages())
              <ul class="flex-center pagination">
                @if ($products->currentPage() > 3)
                  <li><a href="{{ $products->appends((request()->except('page')))->url(1) }}">1</a></li>
                  <li class="disabled"><span>&hellip;</span></li>
                @endif
                @for ($i = max($products->currentPage() - 2, 1); $i <= min($products->currentPage() + 2, $products->lastPage()); $i++)
                  @if ($i == $products->currentPage())
                    <li class="active"><span>{{$i}}</span></li>
                  @else
                    <li><a href="{{ $products->appends((request()->except('page')))->url($i) }}">{{ $i }}</a></li>
                  @endif
                @endfor
                @if ($products->currentPage() < $products->lastPage() - 2)
                  <li class="disabled"><span>&hellip;</span></li>
                  <li><a href="{{ $products->appends((request()->except('page')))->url($products->lastPage()) }}">{{ $products->lastPage() }}</a></li>
                @endif
              </ul>
            @endif
          </div>
        </div>
      </div>
    </div>
  </section>

  @push('scripts')
    <script src="{{asset('js/jquery/swiper/js/swiper.jquery.min.js')}}"></script>
    {{--    <script>--}}
    {{--      let categoryProductsItem = new Swiper(".category-products-item .image", {--}}
    {{--        navigation: {--}}
    {{--          nextEl: ".category-products-item .image .swiper-button-next",--}}
    {{--          prevEl: ".category-products-item .image .swiper-button-prev",--}}
    {{--        },--}}
    {{--        lazy: true,--}}
    {{--      });--}}
    {{--    </script>--}}

    <script>
      const lang = '{{app()->getLocale()}}';
    </script>
    <script src="{{mix('build/js/productIndex.js')}}"></script>
  @endpush
@endsection
