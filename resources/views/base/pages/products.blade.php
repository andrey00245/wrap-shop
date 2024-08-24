@extends('base.layouts.app')


@section('content')

  @push('styles')
    <link rel="stylesheet" href="{{mix('build/css/all-dark.css')}}">
    <link rel="stylesheet" href="{{mix('build/css/style-category-dark.css')}}">
  @endpush

  <section class="category-page row">
    <div class="category-top" style="background-image: url(&quot;https://wrap.shop/image/catalog/category/webp/cat/fon_wrap_webp.webp&quot;);" data-background="https://wrap.shop/image/catalog/category/webp/cat/fon_wrap_webp.webp">
      <nav class="category-breadcrumbs">
        <ul class="flex-center">
          <li><a href="https://wrap.shop/" title="Головна" class="button"><i class="far fa-chevron-left"></i>Головна</a></li>
        </ul>
      </nav>
      <h1 class="title">Плівки</h1>

      <nav class="category-child-nav">
        <ul>
          <li><a href="https://wrap.shop/plivky/kolorovi-plivky/" title="Кольорові плівки" class="flex-center">Кольорові плівки</a></li>
          <li><a href="https://wrap.shop/plivky/zahysni-plivky/" title="Захисні плівки" class="flex-center">Захисні плівки</a></li>
          <li><a href="https://wrap.shop/plivky/plivka-dlya-avtomobilnyh-vikon/" title="Плівка для автомобільних вікон" class="flex-center">Плівка для автомобільних вікон</a></li>
          <li><a href="https://wrap.shop/plivky/gotovi-dyzajny-ta-plivka-dlya-druku/" title="Готові дизайни та плівка для друку" class="flex-center">Готові дизайни та плівка для друку</a></li>
          <li><a href="https://wrap.shop/plivky/lekala-dlya-salona/" title="Лекала для салона" class="flex-center">Лекала для салона</a></li>
        </ul>
      </nav>
    </div>
    <div class="category-content wrap flex-justify">
      <div class="category-left">
        <div class="search-left">
          <div class="title">Пошук у каталозі</div>
          <div id="search" class="flex-justify">
            <input type="text" name="search1" value="" placeholder="Я шукаю:" class="form-control input-lg">
            <button type="button" class="button colord btn btn-default btn-lg"><i class="fal fa-search"></i>Шукати
            </button>
          </div>
        </div>
        <div class="ocf-container ocf-category-60 ocf-theme-light ocf-mobile-1 ocf-mobile-left ocf-vertical ocf-left" id="ocf-module-1">
          <link rel="stylesheet" href="">

          <div class="ocf-content">
            <div class="ocf-header">
              Фільтри


              <button type="button" data-ocf="mobile" class="ocf-close-mobile" aria-label="Close filter"><i class="fal fa-times"></i></button>
            </div>

            <div class="ocf-body">
              <div class="ocf-filter-list ocf-clearfix">









                <div class="ocf-filter ocf-open" id="ocf-filter-2-0-1">
                  <div class="ocf-filter-body">
                    <div class="ocf-filter-header" data-ocf="expand">
                      <span class="ocf-active-label"></span>

                      <span class="ocf-filter-name">Ціна</span>

                      <span class="ocf-filter-header-append">

        <span class="ocf-filter-discard ocf-icon ocf-icon-16 ocf-minus-circle" data-ocf-discard="2.0"></span>

        <span class="ocf-plus-minus"></span>
      </span>
                    </div><!-- /.ocf-filter-header -->
                    <div class="ocf-filter-collapse ocf-collapse ocf-in">



                      <div class="ocf-input-group ocf-slider-input-group">
                        <input type="number" name="ocf[2-0-1][min]" min="336" max="5586" value="336" class="ocf-form-control ocf-grouping ocf-min" id="ocf-input-min-2-0-1" data-filter-key="2.0" autocomplete="off" aria-label="Ціна">
                        <input type="number" name="ocf[2-0-1][max]" min="336" max="5586" value="5586" class="ocf-form-control ocf-grouping ocf-max" id="ocf-input-max-2-0-1" data-filter-key="2.0" autocomplete="off" aria-label="Ціна">
                      </div>

                      <div class="ocf-value-list">
                        <div class="ocf-value-list-body">

                          <button type="button" id="ocf-v-2-0-330-1700-1" class="ocf-value ocf-radio" data-filter-key="2.0" data-value-id="330-1700">
                            <span class="ocf-value-input ocf-value-input-radio"></span>

                            <span class="ocf-value-name">330 - 1700 ₴</span>
                          </button>
                          <button type="button" id="ocf-v-2-0-1700-3000-1" class="ocf-value ocf-radio" data-filter-key="2.0" data-value-id="1700-3000">
                            <span class="ocf-value-input ocf-value-input-radio"></span>

                            <span class="ocf-value-name">1700 - 3000 ₴</span>
                          </button>
                          <button type="button" id="ocf-v-2-0-3000-4300-1" class="ocf-value ocf-radio" data-filter-key="2.0" data-value-id="3000-4300">
                            <span class="ocf-value-input ocf-value-input-radio"></span>

                            <span class="ocf-value-name">3000 - 4300 ₴</span>
                          </button>
                          <button type="button" id="ocf-v-2-0-4300-5600-1" class="ocf-value ocf-radio" data-filter-key="2.0" data-value-id="4300-5600">
                            <span class="ocf-value-input ocf-value-input-radio"></span>

                            <span class="ocf-value-name">4300 - 5600 ₴</span>
                          </button>          </div>


                      </div>
                    </div>
                  </div>
                </div>




                <div class="ocf-filter ocf-open" id="ocf-filter-1-0-1">
                  <div class="ocf-filter-body">
                    <div class="ocf-filter-header" data-ocf="expand">
                      <span class="ocf-active-label"></span>

                      <span class="ocf-filter-name">Виробник</span>

                      <span class="ocf-filter-header-append">

        <span class="ocf-filter-discard ocf-icon ocf-icon-16 ocf-minus-circle" data-ocf-discard="1.0"></span>

        <span class="ocf-plus-minus"></span>
      </span>
                    </div><!-- /.ocf-filter-header -->
                    <div class="ocf-filter-collapse ocf-collapse ocf-in">




                      <div class="ocf-value-list">
                        <div class="ocf-value-list-body">

                          <button type="button" id="ocf-v-1-0-11-1" class="ocf-value ocf-checkbox" data-filter-key="1.0" data-value-id="11">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">3M</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">189</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-1-0-47-1" class="ocf-value ocf-checkbox" data-filter-key="1.0" data-value-id="47">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">Avery</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">109</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-1-0-51-1" class="ocf-value ocf-checkbox" data-filter-key="1.0" data-value-id="51">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">Hexis</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">21</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-1-0-45-1" class="ocf-value ocf-checkbox" data-filter-key="1.0" data-value-id="45">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">Inozetek</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">39</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-1-0-55-1" class="ocf-value ocf-checkbox" data-filter-key="1.0" data-value-id="55">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">KPMF</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">8</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-1-0-44-1" class="ocf-value ocf-checkbox" data-filter-key="1.0" data-value-id="44">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">Kybertane</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">72</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-1-0-32-1" class="ocf-value ocf-checkbox" data-filter-key="1.0" data-value-id="32">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">Madico</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">2</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-1-0-53-1" class="ocf-value ocf-checkbox" data-filter-key="1.0" data-value-id="53">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">Omega</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">2</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-1-0-56-1" class="ocf-value ocf-checkbox" data-filter-key="1.0" data-value-id="56">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">Oracal</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">4</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-1-0-54-1" class="ocf-value ocf-checkbox" data-filter-key="1.0" data-value-id="54">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">TeckWrap</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>          </div>


                      </div>
                    </div>
                  </div>
                </div>




                <div class="ocf-filter ocf-open" id="ocf-filter-40-2-1">
                  <div class="ocf-filter-body">
                    <div class="ocf-filter-header" data-ocf="expand">
                      <span class="ocf-active-label"></span>

                      <span class="ocf-filter-name">Основний відтінок</span>

                      <span class="ocf-filter-header-append">

        <span class="ocf-filter-discard ocf-icon ocf-icon-16 ocf-minus-circle" data-ocf-discard="40.2"></span>

        <span class="ocf-plus-minus"></span>
      </span>
                    </div><!-- /.ocf-filter-header -->
                    <div class="ocf-filter-collapse ocf-collapse ocf-in">




                      <div class="ocf-value-list">
                        <div class="ocf-scroll-y">
                          <div class="ocf-value-list-body">

                            <button type="button" id="ocf-v-40-2-1225646050-1" class="ocf-value ocf-checkbox" data-filter-key="40.2" data-value-id="1225646050">
                              <span class="ocf-value-color" style="background-color: #fff;"></span>

                              <span class="ocf-value-name">білий</span>
                              <span class="ocf-value-append">
    <span class="ocf-value-count">31</span>
  </span>
                            </button>
                            <button type="button" id="ocf-v-40-2-1718022153-1" class="ocf-value ocf-checkbox" data-filter-key="40.2" data-value-id="1718022153">
                              <span class="ocf-value-color" style="background-color: #16bcb4;"></span>

                              <span class="ocf-value-name">бірюзовий</span>
                              <span class="ocf-value-append">
    <span class="ocf-value-count">8</span>
  </span>
                            </button>
                            <button type="button" id="ocf-v-40-2-2469220262-1" class="ocf-value ocf-checkbox" data-filter-key="40.2" data-value-id="2469220262">
                              <span class="ocf-value-color" style="background-color: #fbad82;"></span>

                              <span class="ocf-value-name">бежевий</span>
                              <span class="ocf-value-append">
    <span class="ocf-value-count">7</span>
  </span>
                            </button>
                            <button type="button" id="ocf-v-40-2-4223283398-1" class="ocf-value ocf-checkbox" data-filter-key="40.2" data-value-id="4223283398">
                              <span class="ocf-value-color" style="background-color: #00aeef;"></span>

                              <span class="ocf-value-name">блакитний</span>
                              <span class="ocf-value-append">
    <span class="ocf-value-count">16</span>
  </span>
                            </button>
                            <button type="button" id="ocf-v-40-2-4164180966-1" class="ocf-value ocf-checkbox" data-filter-key="40.2" data-value-id="4164180966">
                              <span class="ocf-value-color" style="background-color: #7a0026;"></span>

                              <span class="ocf-value-name">бордовий</span>
                              <span class="ocf-value-append">
    <span class="ocf-value-count">6</span>
  </span>
                            </button>
                            <button type="button" id="ocf-v-40-2-3003181703-1" class="ocf-value ocf-checkbox" data-filter-key="40.2" data-value-id="3003181703">
                              <span class="ocf-value-color" style="background-color: #ff0;"></span>

                              <span class="ocf-value-name">жовтий</span>
                              <span class="ocf-value-append">
    <span class="ocf-value-count">17</span>
  </span>
                            </button>
                            <button type="button" id="ocf-v-40-2-1087889661-1" class="ocf-value ocf-checkbox" data-filter-key="40.2" data-value-id="1087889661">
                              <span class="ocf-value-color" style="background-color: #045f20;"></span>

                              <span class="ocf-value-name">зелений</span>
                              <span class="ocf-value-append">
    <span class="ocf-value-count">39</span>
  </span>
                            </button>
                            <button type="button" id="ocf-v-40-2-1952877575-1" class="ocf-value ocf-checkbox" data-filter-key="40.2" data-value-id="1952877575">
                              <span class="ocf-value-color" style="background-color: #fdc68c;"></span>

                              <span class="ocf-value-name">золотистий</span>
                              <span class="ocf-value-append">
    <span class="ocf-value-count">12</span>
  </span>
                            </button>
                            <button type="button" id="ocf-v-40-2-2661832362-1" class="ocf-value ocf-checkbox" data-filter-key="40.2" data-value-id="2661832362">
                              <span class="ocf-value-color" style="background-color: #7c4900;"></span>

                              <span class="ocf-value-name">коричневий</span>
                              <span class="ocf-value-append">
    <span class="ocf-value-count">9</span>
  </span>
                            </button>
                            <button type="button" id="ocf-v-40-2-1310327613-1" class="ocf-value ocf-checkbox" data-filter-key="40.2" data-value-id="1310327613">
                              <span class="ocf-value-input ocf-value-input-checkbox"></span>

                              <span class="ocf-value-name">персиковий</span>
                              <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                            </button>
                            <button type="button" id="ocf-v-40-2-2827293321-1" class="ocf-value ocf-checkbox" data-filter-key="40.2" data-value-id="2827293321">
                              <span class="ocf-value-color" style="background-color: #f16c4d;"></span>

                              <span class="ocf-value-name">помаранчевий</span>
                              <span class="ocf-value-append">
    <span class="ocf-value-count">18</span>
  </span>
                            </button>
                            <button type="button" id="ocf-v-40-2-3801111943-1" class="ocf-value ocf-checkbox" data-filter-key="40.2" data-value-id="3801111943">
                              <span class="ocf-value-color" style="background-color: #ed008c;"></span>

                              <span class="ocf-value-name">рожевий</span>
                              <span class="ocf-value-append">
    <span class="ocf-value-count">10</span>
  </span>
                            </button>
                            <button type="button" id="ocf-v-40-2-2505256508-1" class="ocf-value ocf-checkbox" data-filter-key="40.2" data-value-id="2505256508">
                              <span class="ocf-value-color" style="background-color: #c2c2c2;"></span>

                              <span class="ocf-value-name">сірий</span>
                              <span class="ocf-value-append">
    <span class="ocf-value-count">68</span>
  </span>
                            </button>
                            <button type="button" id="ocf-v-40-2-1747800260-1" class="ocf-value ocf-checkbox" data-filter-key="40.2" data-value-id="1747800260">
                              <span class="ocf-value-color" style="background-color: #0f0;"></span>

                              <span class="ocf-value-name">салатовий</span>
                              <span class="ocf-value-append">
    <span class="ocf-value-count">7</span>
  </span>
                            </button>
                            <button type="button" id="ocf-v-40-2-4177059010-1" class="ocf-value ocf-checkbox" data-filter-key="40.2" data-value-id="4177059010">
                              <span class="ocf-value-color" style="background-color: #00f;"></span>

                              <span class="ocf-value-name">синій</span>
                              <span class="ocf-value-append">
    <span class="ocf-value-count">51</span>
  </span>
                            </button>
                            <button type="button" id="ocf-v-40-2-323495583-1" class="ocf-value ocf-checkbox" data-filter-key="40.2" data-value-id="323495583">
                              <span class="ocf-value-color" style="background-color: #acacac;"></span>

                              <span class="ocf-value-name">сріблястий</span>
                              <span class="ocf-value-append">
    <span class="ocf-value-count">6</span>
  </span>
                            </button>
                            <button type="button" id="ocf-v-40-2-953699298-1" class="ocf-value ocf-checkbox" data-filter-key="40.2" data-value-id="953699298">
                              <span class="ocf-value-color" style="background-color: #855fa8;"></span>

                              <span class="ocf-value-name">фіолетовий</span>
                              <span class="ocf-value-append">
    <span class="ocf-value-count">10</span>
  </span>
                            </button>
                            <button type="button" id="ocf-v-40-2-1770768745-1" class="ocf-value ocf-checkbox" data-filter-key="40.2" data-value-id="1770768745">
                              <span class="ocf-value-color" style="background-color: #005824;"></span>

                              <span class="ocf-value-name">хакі</span>
                              <span class="ocf-value-append">
    <span class="ocf-value-count">7</span>
  </span>
                            </button>
                            <button type="button" id="ocf-v-40-2-967242802-1" class="ocf-value ocf-checkbox" data-filter-key="40.2" data-value-id="967242802">
                              <span class="ocf-value-color" style="background-color: #8293ca;"></span>

                              <span class="ocf-value-name">хамелеон</span>
                              <span class="ocf-value-append">
    <span class="ocf-value-count">10</span>
  </span>
                            </button>
                            <button type="button" id="ocf-v-40-2-3618649264-1" class="ocf-value ocf-checkbox" data-filter-key="40.2" data-value-id="3618649264">
                              <span class="ocf-value-color" style="background-color: #f00;"></span>

                              <span class="ocf-value-name">червоний</span>
                              <span class="ocf-value-append">
    <span class="ocf-value-count">29</span>
  </span>
                            </button>
                            <button type="button" id="ocf-v-40-2-1336033264-1" class="ocf-value ocf-checkbox" data-filter-key="40.2" data-value-id="1336033264">
                              <span class="ocf-value-color" style="background-color: #000;"></span>

                              <span class="ocf-value-name">чорний</span>
                              <span class="ocf-value-append">
    <span class="ocf-value-count">47</span>
  </span>
                            </button>          </div>

                        </div>

                      </div>
                    </div>
                  </div>
                </div>




                <div class="ocf-filter ocf-dropdown" id="ocf-filter-86-2-1">
                  <div class="ocf-filter-body">
                    <div class="ocf-filter-header" data-ocf="expand">
                      <span class="ocf-active-label"></span>

                      <span class="ocf-filter-name">Рулон, м.п.</span>

                      <span class="ocf-filter-header-append">

        <span class="ocf-filter-discard ocf-icon ocf-icon-16 ocf-minus-circle" data-ocf-discard="86.2"></span>

        <span class="ocf-plus-minus"></span>
      </span>
                    </div><!-- /.ocf-filter-header -->
                    <div class="ocf-filter-collapse ocf-collapse">




                      <div class="ocf-value-list">
                        <div class="ocf-value-list-body">

                          <button type="button" id="ocf-v-86-2-4291261012-1" class="ocf-value ocf-checkbox" data-filter-key="86.2" data-value-id="4291261012">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">5</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">7</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-86-2-4291261075-1" class="ocf-value ocf-checkbox" data-filter-key="86.2" data-value-id="4291261075">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">8</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">88</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-86-2-537791324-1" class="ocf-value ocf-checkbox" data-filter-key="86.2" data-value-id="537791324">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">15</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">5</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-86-2-1589606369-1" class="ocf-value ocf-checkbox" data-filter-key="86.2" data-value-id="1589606369">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">18</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">53</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-86-2-2068425744-1" class="ocf-value ocf-checkbox" data-filter-key="86.2" data-value-id="2068425744">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">20</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">48</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-86-2-4291261074-1" class="ocf-value ocf-checkbox" data-filter-key="86.2" data-value-id="4291261074">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">22</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">88</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-86-2-186865823-1" class="ocf-value ocf-checkbox" data-filter-key="86.2" data-value-id="186865823">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">25</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">209</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-86-2-1649581393-1" class="ocf-value ocf-checkbox" data-filter-key="86.2" data-value-id="1649581393">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">30</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">31</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-86-2-4291261055-1" class="ocf-value ocf-checkbox" data-filter-key="86.2" data-value-id="4291261055">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">48</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">16</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-86-2-872954583-1" class="ocf-value ocf-checkbox" data-filter-key="86.2" data-value-id="872954583">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">50</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">12</span>
  </span>
                          </button>          </div>


                      </div>
                    </div>
                  </div>
                </div>




                <div class="ocf-filter ocf-dropdown" id="ocf-filter-82-2-1">
                  <div class="ocf-filter-body">
                    <div class="ocf-filter-header" data-ocf="expand">
                      <span class="ocf-active-label"></span>

                      <span class="ocf-filter-name">Строк експлуатації</span>

                      <span class="ocf-filter-header-append">

        <span class="ocf-filter-discard ocf-icon ocf-icon-16 ocf-minus-circle" data-ocf-discard="82.2"></span>

        <span class="ocf-plus-minus"></span>
      </span>
                    </div><!-- /.ocf-filter-header -->
                    <div class="ocf-filter-collapse ocf-collapse">




                      <div class="ocf-value-list">
                        <div class="ocf-value-list-body">

                          <button type="button" id="ocf-v-82-2-3025868024-1" class="ocf-value ocf-checkbox" data-filter-key="82.2" data-value-id="3025868024">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">6 років</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">2</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-82-2-2405354113-1" class="ocf-value ocf-checkbox" data-filter-key="82.2" data-value-id="2405354113">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">близько 1 року</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">2</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-82-2-1375553005-1" class="ocf-value ocf-checkbox" data-filter-key="82.2" data-value-id="1375553005">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">до 10 років</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">65</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-82-2-3328332996-1" class="ocf-value ocf-checkbox" data-filter-key="82.2" data-value-id="3328332996">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">до 12 років</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">10</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-82-2-316554310-1" class="ocf-value ocf-checkbox" data-filter-key="82.2" data-value-id="316554310">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">до 3 років</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-82-2-3615032008-1" class="ocf-value ocf-checkbox" data-filter-key="82.2" data-value-id="3615032008">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">до 4 років</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">13</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-82-2-1896745340-1" class="ocf-value ocf-checkbox" data-filter-key="82.2" data-value-id="1896745340">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">до 5 років</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">194</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-82-2-1088820193-1" class="ocf-value ocf-checkbox" data-filter-key="82.2" data-value-id="1088820193">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">до 6 років</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">38</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-82-2-3868268629-1" class="ocf-value ocf-checkbox" data-filter-key="82.2" data-value-id="3868268629">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">до 7 років</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">84</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-82-2-282683580-1" class="ocf-value ocf-checkbox" data-filter-key="82.2" data-value-id="282683580">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">до 8 років</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">35</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-82-2-3064881928-1" class="ocf-value ocf-checkbox" data-filter-key="82.2" data-value-id="3064881928">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">до 9 років</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>          </div>


                      </div>
                    </div>
                  </div>
                </div>




                <div class="ocf-filter ocf-dropdown" id="ocf-filter-80-2-1">
                  <div class="ocf-filter-body">
                    <div class="ocf-filter-header" data-ocf="expand">
                      <span class="ocf-active-label"></span>

                      <span class="ocf-filter-name">Температура поверхні</span>

                      <span class="ocf-filter-header-append">

        <span class="ocf-filter-discard ocf-icon ocf-icon-16 ocf-minus-circle" data-ocf-discard="80.2"></span>

        <span class="ocf-plus-minus"></span>
      </span>
                    </div><!-- /.ocf-filter-header -->
                    <div class="ocf-filter-collapse ocf-collapse">




                      <div class="ocf-value-list">
                        <div class="ocf-value-list-body">

                          <button type="button" id="ocf-v-80-2-990950172-1" class="ocf-value ocf-checkbox" data-filter-key="80.2" data-value-id="990950172">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">від +10°C</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">2</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-80-2-3286201912-1" class="ocf-value ocf-checkbox" data-filter-key="80.2" data-value-id="3286201912">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">від +10°C до +16°C</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">109</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-80-2-1505776235-1" class="ocf-value ocf-checkbox" data-filter-key="80.2" data-value-id="1505776235">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">від +10°C до +38°C</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-80-2-214844206-1" class="ocf-value ocf-checkbox" data-filter-key="80.2" data-value-id="214844206">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">від +15°C</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-80-2-3000949858-1" class="ocf-value ocf-checkbox" data-filter-key="80.2" data-value-id="3000949858">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">від +15°C до +32°C</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">2</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-80-2-789177563-1" class="ocf-value ocf-checkbox" data-filter-key="80.2" data-value-id="789177563">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">від +15°C до +35°C</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-80-2-4077324742-1" class="ocf-value ocf-checkbox" data-filter-key="80.2" data-value-id="4077324742">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">від +17°C до +25°C</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-80-2-944014729-1" class="ocf-value ocf-checkbox" data-filter-key="80.2" data-value-id="944014729">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">від +18°C до +22°C</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">159</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-80-2-2091960780-1" class="ocf-value ocf-checkbox" data-filter-key="80.2" data-value-id="2091960780">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">від +20°C</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">8</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-80-2-3038645317-1" class="ocf-value ocf-checkbox" data-filter-key="80.2" data-value-id="3038645317">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">від +4°C до +35°C</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-80-2-1198838936-1" class="ocf-value ocf-checkbox" data-filter-key="80.2" data-value-id="1198838936">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">від +4°C до +38°C</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">3</span>
  </span>
                          </button>          </div>


                      </div>
                    </div>
                  </div>
                </div>




                <div class="ocf-filter ocf-dropdown" id="ocf-filter-79-2-1">
                  <div class="ocf-filter-body">
                    <div class="ocf-filter-header" data-ocf="expand">
                      <span class="ocf-active-label"></span>

                      <span class="ocf-filter-name">Товщина</span>

                      <span class="ocf-filter-header-append">

        <span class="ocf-filter-discard ocf-icon ocf-icon-16 ocf-minus-circle" data-ocf-discard="79.2"></span>

        <span class="ocf-plus-minus"></span>
      </span>
                    </div><!-- /.ocf-filter-header -->
                    <div class="ocf-filter-collapse ocf-collapse">




                      <div class="ocf-value-list">
                        <div class="ocf-value-list-body">

                          <button type="button" id="ocf-v-79-2-2369956832-1" class="ocf-value ocf-checkbox" data-filter-key="79.2" data-value-id="2369956832">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">50 мкр</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">5</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-79-2-373800497-1" class="ocf-value ocf-checkbox" data-filter-key="79.2" data-value-id="373800497">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">80 мкр</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">105</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-79-2-3673013935-1" class="ocf-value ocf-checkbox" data-filter-key="79.2" data-value-id="3673013935">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">90 мкр</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">159</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-79-2-1362899387-1" class="ocf-value ocf-checkbox" data-filter-key="79.2" data-value-id="1362899387">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">100 мкр</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">12</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-79-2-3437153448-1" class="ocf-value ocf-checkbox" data-filter-key="79.2" data-value-id="3437153448">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">100-140 мкр</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">2</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-79-2-3332579474-1" class="ocf-value ocf-checkbox" data-filter-key="79.2" data-value-id="3332579474">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">102 мкр</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">2</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-79-2-3484294786-1" class="ocf-value ocf-checkbox" data-filter-key="79.2" data-value-id="3484294786">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">115 мкр</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">2</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-79-2-135165174-1" class="ocf-value ocf-checkbox" data-filter-key="79.2" data-value-id="135165174">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">119 мкр</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">4</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-79-2-320419526-1" class="ocf-value ocf-checkbox" data-filter-key="79.2" data-value-id="320419526">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">120 мкр</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-79-2-557390674-1" class="ocf-value ocf-checkbox" data-filter-key="79.2" data-value-id="557390674">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">144 мкр</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">39</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-79-2-433864671-1" class="ocf-value ocf-checkbox" data-filter-key="79.2" data-value-id="433864671">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">150 мкр</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">51</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-79-2-1273237624-1" class="ocf-value ocf-checkbox" data-filter-key="79.2" data-value-id="1273237624">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">155 мкр</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-79-2-2538813500-1" class="ocf-value ocf-checkbox" data-filter-key="79.2" data-value-id="2538813500">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">160 мкр</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">2</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-79-2-3312129947-1" class="ocf-value ocf-checkbox" data-filter-key="79.2" data-value-id="3312129947">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">165 мкр</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-79-2-1543059618-1" class="ocf-value ocf-checkbox" data-filter-key="79.2" data-value-id="1543059618">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">170 мкр</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">2</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-79-2-1316170384-1" class="ocf-value ocf-checkbox" data-filter-key="79.2" data-value-id="1316170384">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">190 мкр</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-79-2-1756433790-1" class="ocf-value ocf-checkbox" data-filter-key="79.2" data-value-id="1756433790">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">200 мкр</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">10</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-79-2-714344963-1" class="ocf-value ocf-checkbox" data-filter-key="79.2" data-value-id="714344963">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">220 мкр</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">18</span>
  </span>
                          </button>          </div>


                      </div>
                    </div>
                  </div>
                </div>




                <div class="ocf-filter ocf-dropdown" id="ocf-filter-48-2-1">
                  <div class="ocf-filter-body">
                    <div class="ocf-filter-header" data-ocf="expand">
                      <span class="ocf-active-label"></span>

                      <span class="ocf-filter-name">Protective Liner</span>

                      <span class="ocf-filter-header-append">

        <span class="ocf-filter-discard ocf-icon ocf-icon-16 ocf-minus-circle" data-ocf-discard="48.2"></span>

        <span class="ocf-plus-minus"></span>
      </span>
                    </div><!-- /.ocf-filter-header -->
                    <div class="ocf-filter-collapse ocf-collapse">




                      <div class="ocf-value-list">
                        <div class="ocf-value-list-body">

                          <button type="button" id="ocf-v-48-2-3893068535-1" class="ocf-value ocf-checkbox" data-filter-key="48.2" data-value-id="3893068535">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">Polyethylene-coated paper</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-48-2-3628321987-1" class="ocf-value ocf-checkbox" data-filter-key="48.2" data-value-id="3628321987">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">ні</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">136</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-48-2-4190294876-1" class="ocf-value ocf-checkbox" data-filter-key="48.2" data-value-id="4190294876">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">так</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">173</span>
  </span>
                          </button>          </div>


                      </div>
                    </div>
                  </div>
                </div>




                <div class="ocf-filter ocf-dropdown" id="ocf-filter-76-2-1">
                  <div class="ocf-filter-body">
                    <div class="ocf-filter-header" data-ocf="expand">
                      <span class="ocf-active-label"></span>

                      <span class="ocf-filter-name">Матеріал</span>

                      <span class="ocf-filter-header-append">

        <span class="ocf-filter-discard ocf-icon ocf-icon-16 ocf-minus-circle" data-ocf-discard="76.2"></span>

        <span class="ocf-plus-minus"></span>
      </span>
                    </div><!-- /.ocf-filter-header -->
                    <div class="ocf-filter-collapse ocf-collapse">




                      <div class="ocf-value-list">
                        <div class="ocf-value-list-body">

                          <button type="button" id="ocf-v-76-2-3162498025-1" class="ocf-value ocf-checkbox" data-filter-key="76.2" data-value-id="3162498025">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">вініл</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">285</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-76-2-363803250-1" class="ocf-value ocf-checkbox" data-filter-key="76.2" data-value-id="363803250">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">пвх</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">116</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-76-2-1710936836-1" class="ocf-value ocf-checkbox" data-filter-key="76.2" data-value-id="1710936836">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">поліуретан</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">13</span>
  </span>
                          </button>          </div>


                      </div>
                    </div>
                  </div>
                </div>




                <div class="ocf-filter ocf-dropdown" id="ocf-filter-52-2-1">
                  <div class="ocf-filter-body">
                    <div class="ocf-filter-header" data-ocf="expand">
                      <span class="ocf-active-label"></span>

                      <span class="ocf-filter-name">Необхідність нагріву</span>

                      <span class="ocf-filter-header-append">

        <span class="ocf-filter-discard ocf-icon ocf-icon-16 ocf-minus-circle" data-ocf-discard="52.2"></span>

        <span class="ocf-plus-minus"></span>
      </span>
                    </div><!-- /.ocf-filter-header -->
                    <div class="ocf-filter-collapse ocf-collapse">




                      <div class="ocf-value-list">
                        <div class="ocf-value-list-body">

                          <button type="button" id="ocf-v-52-2-1593361480-1" class="ocf-value ocf-checkbox" data-filter-key="52.2" data-value-id="1593361480">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">ні</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-52-2-4264491918-1" class="ocf-value ocf-checkbox" data-filter-key="52.2" data-value-id="4264491918">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">так</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">56</span>
  </span>
                          </button>          </div>


                      </div>
                    </div>
                  </div>
                </div>




                <div class="ocf-filter ocf-dropdown" id="ocf-filter-37-2-1">
                  <div class="ocf-filter-body">
                    <div class="ocf-filter-header" data-ocf="expand">
                      <span class="ocf-active-label"></span>

                      <span class="ocf-filter-name">Призначення</span>

                      <span class="ocf-filter-header-append">

        <span class="ocf-filter-discard ocf-icon ocf-icon-16 ocf-minus-circle" data-ocf-discard="37.2"></span>

        <span class="ocf-plus-minus"></span>
      </span>
                    </div><!-- /.ocf-filter-header -->
                    <div class="ocf-filter-collapse ocf-collapse">




                      <div class="ocf-value-list">
                        <div class="ocf-value-list-body">

                          <button type="button" id="ocf-v-37-2-184593436-1" class="ocf-value ocf-checkbox" data-filter-key="37.2" data-value-id="184593436">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">антивандальна</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">2</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-37-2-991484711-1" class="ocf-value ocf-checkbox" data-filter-key="37.2" data-value-id="991484711">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">антигравійна</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">15</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-37-2-4174069027-1" class="ocf-value ocf-checkbox" data-filter-key="37.2" data-value-id="4174069027">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">декоративна</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">401</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-37-2-681045854-1" class="ocf-value ocf-checkbox" data-filter-key="37.2" data-value-id="681045854">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">тонувальна</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">27</span>
  </span>
                          </button>          </div>


                      </div>
                    </div>
                  </div>
                </div>




                <div class="ocf-filter ocf-dropdown" id="ocf-filter-60-2-1">
                  <div class="ocf-filter-body">
                    <div class="ocf-filter-header" data-ocf="expand">
                      <span class="ocf-active-label"></span>

                      <span class="ocf-filter-name">Світлопроникність</span>

                      <span class="ocf-filter-header-append">

        <span class="ocf-filter-discard ocf-icon ocf-icon-16 ocf-minus-circle" data-ocf-discard="60.2"></span>

        <span class="ocf-plus-minus"></span>
      </span>
                    </div><!-- /.ocf-filter-header -->
                    <div class="ocf-filter-collapse ocf-collapse">




                      <div class="ocf-value-list">
                        <div class="ocf-value-list-body">

                          <button type="button" id="ocf-v-60-2-3280879489-1" class="ocf-value ocf-checkbox" data-filter-key="60.2" data-value-id="3280879489">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">5%</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">3</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-60-2-1981825484-1" class="ocf-value ocf-checkbox" data-filter-key="60.2" data-value-id="1981825484">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">8%</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">2</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-60-2-868632715-1" class="ocf-value ocf-checkbox" data-filter-key="60.2" data-value-id="868632715">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">10%</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-60-2-1320271054-1" class="ocf-value ocf-checkbox" data-filter-key="60.2" data-value-id="1320271054">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">15%</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">2</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-60-2-830535378-1" class="ocf-value ocf-checkbox" data-filter-key="60.2" data-value-id="830535378">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">20%</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">5</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-60-2-447587601-1" class="ocf-value ocf-checkbox" data-filter-key="60.2" data-value-id="447587601">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">23%</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-60-2-1291256471-1" class="ocf-value ocf-checkbox" data-filter-key="60.2" data-value-id="1291256471">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">25%</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-60-2-809671909-1" class="ocf-value ocf-checkbox" data-filter-key="60.2" data-value-id="809671909">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">30%</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-60-2-41221735-1" class="ocf-value ocf-checkbox" data-filter-key="60.2" data-value-id="41221735">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">32%</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-60-2-1295345824-1" class="ocf-value ocf-checkbox" data-filter-key="60.2" data-value-id="1295345824">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">35%</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">3</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-60-2-890079840-1" class="ocf-value ocf-checkbox" data-filter-key="60.2" data-value-id="890079840">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">40%</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-60-2-886038615-1" class="ocf-value ocf-checkbox" data-filter-key="60.2" data-value-id="886038615">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">50%</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-60-2-927675449-1" class="ocf-value ocf-checkbox" data-filter-key="60.2" data-value-id="927675449">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">70%</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">3</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-60-2-776996216-1" class="ocf-value ocf-checkbox" data-filter-key="60.2" data-value-id="776996216">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">71%</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-60-2-1935074755-1" class="ocf-value ocf-checkbox" data-filter-key="60.2" data-value-id="1935074755">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">87%</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">2</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-60-2-1037375795-1" class="ocf-value ocf-checkbox" data-filter-key="60.2" data-value-id="1037375795">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">90%</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">2</span>
  </span>
                          </button>          </div>


                      </div>
                    </div>
                  </div>
                </div>




                <div class="ocf-filter ocf-dropdown" id="ocf-filter-77-2-1">
                  <div class="ocf-filter-body">
                    <div class="ocf-filter-header" data-ocf="expand">
                      <span class="ocf-active-label"></span>

                      <span class="ocf-filter-name">Серія</span>

                      <span class="ocf-filter-header-append">

        <span class="ocf-filter-discard ocf-icon ocf-icon-16 ocf-minus-circle" data-ocf-discard="77.2"></span>

        <span class="ocf-plus-minus"></span>
      </span>
                    </div><!-- /.ocf-filter-header -->
                    <div class="ocf-filter-collapse ocf-collapse">




                      <div class="ocf-value-list">
                        <div class="ocf-value-list-body">

                          <button type="button" id="ocf-v-77-2-1712980415-1" class="ocf-value ocf-checkbox" data-filter-key="77.2" data-value-id="1712980415">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">3M Automotive</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-77-2-1138822514-1" class="ocf-value ocf-checkbox" data-filter-key="77.2" data-value-id="1138822514">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">5G Constant Color Series</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">4</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-77-2-3908184965-1" class="ocf-value ocf-checkbox" data-filter-key="77.2" data-value-id="3908184965">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">5G Skin Care High Heat Insulation Series</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-77-2-3399226577-1" class="ocf-value ocf-checkbox" data-filter-key="77.2" data-value-id="3399226577">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">1080</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">72</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-77-2-3007360117-1" class="ocf-value ocf-checkbox" data-filter-key="77.2" data-value-id="3007360117">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">1089</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-77-2-3626613567-1" class="ocf-value ocf-checkbox" data-filter-key="77.2" data-value-id="3626613567">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">2080</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">86</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-77-2-1889331932-1" class="ocf-value ocf-checkbox" data-filter-key="77.2" data-value-id="1889331932">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">8900</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-77-2-1222677911-1" class="ocf-value ocf-checkbox" data-filter-key="77.2" data-value-id="1222677911">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">Bodyfence</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-77-2-440190307-1" class="ocf-value ocf-checkbox" data-filter-key="77.2" data-value-id="440190307">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">Color</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">5</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-77-2-3963846657-1" class="ocf-value ocf-checkbox" data-filter-key="77.2" data-value-id="3963846657">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">Commercial Graphics</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">7</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-77-2-2799889186-1" class="ocf-value ocf-checkbox" data-filter-key="77.2" data-value-id="2799889186">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">Crystalline</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">5</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-77-2-2398354367-1" class="ocf-value ocf-checkbox" data-filter-key="77.2" data-value-id="2398354367">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">Crystalline Series</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-77-2-3473225001-1" class="ocf-value ocf-checkbox" data-filter-key="77.2" data-value-id="3473225001">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">FX-HP</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">4</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-77-2-1988165346-1" class="ocf-value ocf-checkbox" data-filter-key="77.2" data-value-id="1988165346">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">Magnetron Double Silver Skin Care High Heat Insulation Series</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-77-2-4282639623-1" class="ocf-value ocf-checkbox" data-filter-key="77.2" data-value-id="4282639623">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">Magnetron Skin Care High Heat Insulation Series</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-77-2-204960888-1" class="ocf-value ocf-checkbox" data-filter-key="77.2" data-value-id="204960888">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">New Energy 5G High Heat Insulation Series</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-77-2-609157094-1" class="ocf-value ocf-checkbox" data-filter-key="77.2" data-value-id="609157094">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">PPF</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">6</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-77-2-2415174919-1" class="ocf-value ocf-checkbox" data-filter-key="77.2" data-value-id="2415174919">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">Shade</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">3</span>
  </span>
                          </button>          </div>


                      </div>
                    </div>
                  </div>
                </div>




                <div class="ocf-filter ocf-dropdown" id="ocf-filter-46-2-1">
                  <div class="ocf-filter-body">
                    <div class="ocf-filter-header" data-ocf="expand">
                      <span class="ocf-active-label"></span>

                      <span class="ocf-filter-name">Спосіб нанесення</span>

                      <span class="ocf-filter-header-append">

        <span class="ocf-filter-discard ocf-icon ocf-icon-16 ocf-minus-circle" data-ocf-discard="46.2"></span>

        <span class="ocf-plus-minus"></span>
      </span>
                    </div><!-- /.ocf-filter-header -->
                    <div class="ocf-filter-collapse ocf-collapse">




                      <div class="ocf-value-list">
                        <div class="ocf-value-list-body">

                          <button type="button" id="ocf-v-46-2-4020409676-1" class="ocf-value ocf-checkbox" data-filter-key="46.2" data-value-id="4020409676">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">вологий</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">13</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-46-2-797692996-1" class="ocf-value ocf-checkbox" data-filter-key="46.2" data-value-id="797692996">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">сухий</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">400</span>
  </span>
                          </button>          </div>


                      </div>
                    </div>
                  </div>
                </div>




                <div class="ocf-filter ocf-dropdown" id="ocf-filter-39-2-1">
                  <div class="ocf-filter-body">
                    <div class="ocf-filter-header" data-ocf="expand">
                      <span class="ocf-active-label"></span>

                      <span class="ocf-filter-name">Структура</span>

                      <span class="ocf-filter-header-append">

        <span class="ocf-filter-discard ocf-icon ocf-icon-16 ocf-minus-circle" data-ocf-discard="39.2"></span>

        <span class="ocf-plus-minus"></span>
      </span>
                    </div><!-- /.ocf-filter-header -->
                    <div class="ocf-filter-collapse ocf-collapse">




                      <div class="ocf-value-list">
                        <div class="ocf-value-list-body">

                          <button type="button" id="ocf-v-39-2-2911762373-1" class="ocf-value ocf-checkbox" data-filter-key="39.2" data-value-id="2911762373">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">глянцева</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">249</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-39-2-2156173017-1" class="ocf-value ocf-checkbox" data-filter-key="39.2" data-value-id="2156173017">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">матова</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">77</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-39-2-104821101-1" class="ocf-value ocf-checkbox" data-filter-key="39.2" data-value-id="104821101">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">сатинова</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">70</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-39-2-4120922725-1" class="ocf-value ocf-checkbox" data-filter-key="39.2" data-value-id="4120922725">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">структурна</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">19</span>
  </span>
                          </button>          </div>


                      </div>
                    </div>
                  </div>
                </div>




                <div class="ocf-filter ocf-dropdown" id="ocf-filter-53-2-1">
                  <div class="ocf-filter-body">
                    <div class="ocf-filter-header" data-ocf="expand">
                      <span class="ocf-active-label"></span>

                      <span class="ocf-filter-name">Температура експлуатації</span>

                      <span class="ocf-filter-header-append">

        <span class="ocf-filter-discard ocf-icon ocf-icon-16 ocf-minus-circle" data-ocf-discard="53.2"></span>

        <span class="ocf-plus-minus"></span>
      </span>
                    </div><!-- /.ocf-filter-header -->
                    <div class="ocf-filter-collapse ocf-collapse">




                      <div class="ocf-value-list">
                        <div class="ocf-value-list-body">

                          <button type="button" id="ocf-v-53-2-3368032136-1" class="ocf-value ocf-checkbox" data-filter-key="53.2" data-value-id="3368032136">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">від -20°C до +120°C</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">2</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-53-2-2084967861-1" class="ocf-value ocf-checkbox" data-filter-key="53.2" data-value-id="2084967861">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">від -40°C до +107°C</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">2</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-53-2-339480683-1" class="ocf-value ocf-checkbox" data-filter-key="53.2" data-value-id="339480683">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">від -40°C до +90°C</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">29</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-53-2-1922667821-1" class="ocf-value ocf-checkbox" data-filter-key="53.2" data-value-id="1922667821">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">від -50°C до +110°C</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">109</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-53-2-892989437-1" class="ocf-value ocf-checkbox" data-filter-key="53.2" data-value-id="892989437">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">від -50°C до +120°C</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">4</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-53-2-2915552131-1" class="ocf-value ocf-checkbox" data-filter-key="53.2" data-value-id="2915552131">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">від -50°C до +90°C</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-53-2-3272819126-1" class="ocf-value ocf-checkbox" data-filter-key="53.2" data-value-id="3272819126">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">від -53°C до +93°C</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">2</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-53-2-1481826631-1" class="ocf-value ocf-checkbox" data-filter-key="53.2" data-value-id="1481826631">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">від -54°C до +107°C</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">84</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-53-2-2632630797-1" class="ocf-value ocf-checkbox" data-filter-key="53.2" data-value-id="2632630797">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">від -55°C до +65°C</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-53-2-4226166998-1" class="ocf-value ocf-checkbox" data-filter-key="53.2" data-value-id="4226166998">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">від -60°C до +107°C</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">77</span>
  </span>
                          </button>          </div>


                      </div>
                    </div>
                  </div>
                </div>




                <div class="ocf-filter ocf-dropdown" id="ocf-filter-59-2-1">
                  <div class="ocf-filter-body">
                    <div class="ocf-filter-header" data-ocf="expand">
                      <span class="ocf-active-label"></span>

                      <span class="ocf-filter-name">Технологія виробництва</span>

                      <span class="ocf-filter-header-append">

        <span class="ocf-filter-discard ocf-icon ocf-icon-16 ocf-minus-circle" data-ocf-discard="59.2"></span>

        <span class="ocf-plus-minus"></span>
      </span>
                    </div><!-- /.ocf-filter-header -->
                    <div class="ocf-filter-collapse ocf-collapse">




                      <div class="ocf-value-list">
                        <div class="ocf-value-list-body">

                          <button type="button" id="ocf-v-59-2-2931280339-1" class="ocf-value ocf-checkbox" data-filter-key="59.2" data-value-id="2931280339">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">вініл</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">97</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-59-2-784695848-1" class="ocf-value ocf-checkbox" data-filter-key="59.2" data-value-id="784695848">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">лита плівка пвх</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">20</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-59-2-2115891095-1" class="ocf-value ocf-checkbox" data-filter-key="59.2" data-value-id="2115891095">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">литий вініл</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">275</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-59-2-3799582808-1" class="ocf-value ocf-checkbox" data-filter-key="59.2" data-value-id="3799582808">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">пвх</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">9</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-59-2-1425414317-1" class="ocf-value ocf-checkbox" data-filter-key="59.2" data-value-id="1425414317">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">поліуретан</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">12</span>
  </span>
                          </button>          </div>


                      </div>
                    </div>
                  </div>
                </div>




                <div class="ocf-filter ocf-dropdown" id="ocf-filter-38-2-1">
                  <div class="ocf-filter-body">
                    <div class="ocf-filter-header" data-ocf="expand">
                      <span class="ocf-active-label"></span>

                      <span class="ocf-filter-name">Тип</span>

                      <span class="ocf-filter-header-append">

        <span class="ocf-filter-discard ocf-icon ocf-icon-16 ocf-minus-circle" data-ocf-discard="38.2"></span>

        <span class="ocf-plus-minus"></span>
      </span>
                    </div><!-- /.ocf-filter-header -->
                    <div class="ocf-filter-collapse ocf-collapse">




                      <div class="ocf-value-list">
                        <div class="ocf-value-list-body">

                          <button type="button" id="ocf-v-38-2-816167911-1" class="ocf-value ocf-checkbox" data-filter-key="38.2" data-value-id="816167911">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">атермальна</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">24</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-38-2-1965124537-1" class="ocf-value ocf-checkbox" data-filter-key="38.2" data-value-id="1965124537">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">декоративна</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-38-2-3214384054-1" class="ocf-value ocf-checkbox" data-filter-key="38.2" data-value-id="3214384054">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">металізована</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">4</span>
  </span>
                          </button>          </div>


                      </div>
                    </div>
                  </div>
                </div>
              </div>    </div>
            <div class="ocf-footer">
              <div class="ocf-between">
                <button type="button" data-ocf-discard="*" class="ocf-btn ocf-btn-link ocf-disabled" disabled="disabled">Скасувати</button>

                <button type="button" class="ocf-btn ocf-disabled ocf-btn-block" data-ocf="button" data-loading-text="Завантаження..." disabled="disabled">Виберіть фільтри</button>

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
            <button class="ocf-btn ocf-search-btn-popover" data-ocf="button" data-loading-text="Завантаження...">Виберіть фільтри</button>
          </div>

          <script>
            +(function(global) {

              var startOCFilter = function() {
                var loadScript = function(url, callback) {
                  $.ajax({ url: url, dataType: 'script', success: callback, async: true });
                };

                var init = function() {
                  $('#ocf-module-1').ocfilter({
                    index: '1',

                    paramsIndex: 'ocf',

                    urlHost: 'https://wrap.shop/',
                    urlParams: '&index=1&layout=vertical&ocf_path=60&ocf_route=product/category&seo_url_enabled=1&ocf_layout_id=3',

                    params: '',

                    sepFilt: 'F',
                    sepFsrc: 'S',
                    sepVals: 'V',
                    sepSdot: 'D',
                    sepSneg: 'N',
                    sepSran: 'T',

                    position: 'left',
                    layout: 'vertical',
                    numeralLocale: 'uk-ua',
                    searchButton: 1,
                    showCounter: 1,
                    sliderInput: 1,
                    sliderPips: 1,
                    priceLogarithmic: 0,
                    lazyLoadFilters: 0,
                    lazyLoadValues: 0,

                    mobileMaxWidth: 767,
                    mobileRememberState: 0,

                    textLoad: '\u003Ci\u0020class\u003D\u0027fa\u0020fa\u002Drefresh\u0020fa\u002Dspin\u0027\u003E\u003C\/i\u003E\u0020\u0417\u0430\u0432\u0430\u043D\u0442\u0430\u0436\u0435\u043D\u043D\u044F..',
                    textSelect: '\u0412\u0438\u0431\u0435\u0440\u0456\u0442\u044C\u0020\u0444\u0456\u043B\u044C\u0442\u0440\u0438'
                  });
                };

                loadScript('catalog/view/javascript/ocfilter48/ocfilter.js?v=4.8.0.19.1', init);
              };

              var ready = function(fn) {
                if (global.readyState != 'loading') {
                  fn();
                } else {
                  global.addEventListener('DOMContentLoaded', fn);
                }
              };

              ready(function() { // DOM loaded
                if ('undefined' == typeof jQuery) {
                  console.error('OCFilter required jQuery');

                  return;
                }

                $(startOCFilter); // jQuery loaded
              });

            })(document);
          </script>
        </div><!-- /.ocf-container -->

      </div>
      <div class="category-right" id="content">

        <div class="category-right-top flex-justify" data-background="catalog/view/theme/wrapshop/image/icon/head-top.png" style="background-image: url(&quot;catalog/view/theme/wrapshop/image/icon/head-top.png&quot;);">
          <div class="category-sort flex-justify">
            <div class="label">Сортувати за:</div>
            <div class="category-sort-close button"><i class="fal fa-times"></i></div>
            <div class="list">

              <a href="https://wrap.shop/plivky/?sort=p.sort_order&amp;order=ASC" class="button active" title="Замовчуванням">Замовчуванням</a>







              <a href="https://wrap.shop/plivky/?sort=pd.name&amp;order=ASC" class="button" title="Алфавітом">Алфавітом</a>













              <a href="https://wrap.shop/plivky/?sort=p.price&amp;order=ASC" class="button" title="Зростанням ціни">Зростанням ціни</a>






              <a href="https://wrap.shop/plivky/?sort=p.price&amp;order=DESC" class="button" title="Спаданням ціни">Спаданням ціни</a>













              <a href="https://wrap.shop/plivky/?sort=rating&amp;order=ASC" class="button" title="Найпопулярнішими">Найпопулярнішими</a>



















              <a href="https://wrap.shop/plivky/?sort=p.date_available&amp;order=ASC" class="button" title="Датою додавання">Датою додавання</a>







            </div>
          </div>
          <div class="category-view flex-justify">
            <div class="button active" id="grid-view"><i class="fas fa-th"></i></div>
            <div class="button" id="list-view"><i class="fas fa-th-list"></i></div>
          </div>
        </div>

        <div class="category-button flex-justify">
          <div class="button-filtr category-filtr-open button" data-ocf="mobile"><i class="fal fa-filter"></i>Фільтри</div>
          <div class="button-sort category-sort-open button"><i class="fal fa-sort-alt"></i>Сортування</div>
        </div>
        <div class="category-products flex-wrap">

          <div class="category-products-item product-default product-layout product-grid" id="categoryProductsItem153">
            <div class="top flex-justify">
              <div class="sku">Код: 10530</div>
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
                <button type="button" class="button far fa-heart" title="В закладки" onclick="wishlist.add('153');"></button>
              </div>
            </div>
            <div class="sale statuses"><div class="category-status category-status-1 status-inline text rectangle " style=" color:#ffffff; background-color:#d04b4b;">







                Хіт продажів




              </div></div>
            <div class="image swiper swiper-initialized swiper-horizontal swiper-android swiper-backface-hidden">
              <i class="far fa-search-plus colord" data-src="/image/catalog/demo/syncms/56925315-6d1a-11ea-0a80-014a0025005b_0.png" data-fancybox="gallery153" data-caption="Плівка сатинова 3M 2080-S261 Satin Dark Gray"></i>
              <a href="https://wrap.shop/plivky/kolorovi-plivky/plivka-satynova-3m-2080-s261-satin-dark-gray" title="Плівка сатинова 3M 2080-S261 Satin Dark Gray" class="swiper-wrapper" id="swiper-wrapper-9f8bbc8369199f97" aria-live="polite">
                <div class="swiper-slide item flex-center swiper-slide-active" role="group" aria-label="1 / 5" style="display: flex; width: 241px;">
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/56925315-6d1a-11ea-0a80-014a0025005b_0-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/56925315-6d1a-11ea-0a80-014a0025005b_0-482x482.webp 2x" alt="Плівка сатинова 3M 2080-S261 Satin Dark Gray" title="Плівка сатинова 3M 2080-S261 Satin Dark Gray" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center swiper-slide-next" role="group" aria-label="2 / 5" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/56925315-6d1a-11ea-0a80-014a0025005b_1.png" data-fancybox="gallery153" data-caption="Плівка сатинова 3M 2080-S261 Satin Dark Gray"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/56925315-6d1a-11ea-0a80-014a0025005b_1-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/56925315-6d1a-11ea-0a80-014a0025005b_1-482x482.webp 2x" alt="Плівка сатинова 3M 2080-S261 Satin Dark Gray" title="Плівка сатинова 3M 2080-S261 Satin Dark Gray" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="3 / 5" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/56925315-6d1a-11ea-0a80-014a0025005b_2.png" data-fancybox="gallery153" data-caption="Плівка сатинова 3M 2080-S261 Satin Dark Gray"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/56925315-6d1a-11ea-0a80-014a0025005b_2-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/56925315-6d1a-11ea-0a80-014a0025005b_2-482x482.webp 2x" alt="Плівка сатинова 3M 2080-S261 Satin Dark Gray" title="Плівка сатинова 3M 2080-S261 Satin Dark Gray" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="4 / 5" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/56925315-6d1a-11ea-0a80-014a0025005b_3.png" data-fancybox="gallery153" data-caption="Плівка сатинова 3M 2080-S261 Satin Dark Gray"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/56925315-6d1a-11ea-0a80-014a0025005b_3-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/56925315-6d1a-11ea-0a80-014a0025005b_3-482x482.webp 2x" alt="Плівка сатинова 3M 2080-S261 Satin Dark Gray" title="Плівка сатинова 3M 2080-S261 Satin Dark Gray" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="5 / 5" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/56925315-6d1a-11ea-0a80-014a0025005b_4.png" data-fancybox="gallery153" data-caption="Плівка сатинова 3M 2080-S261 Satin Dark Gray"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/56925315-6d1a-11ea-0a80-014a0025005b_4-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/56925315-6d1a-11ea-0a80-014a0025005b_4-482x482.webp 2x" alt="Плівка сатинова 3M 2080-S261 Satin Dark Gray" title="Плівка сатинова 3M 2080-S261 Satin Dark Gray" width="200" height="200">
                </div>
              </a>
              <div class="swiper-button-next button" tabindex="0" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-9f8bbc8369199f97" aria-disabled="false"></div>
              <div class="swiper-button-prev button swiper-button-disabled" tabindex="-1" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-9f8bbc8369199f97" aria-disabled="true"></div>
              <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
            <div class="review list">
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
            <div class="center">
              <div class="category">Кольорові плівки</div>
              <a href="https://wrap.shop/plivky/kolorovi-plivky/plivka-satynova-3m-2080-s261-satin-dark-gray" title="Плівка сатинова 3M 2080-S261 Satin Dark Gray" class="name">Плівка сатинова 3M 2080-S261 Satin Dark Gray</a>
            </div>
            <div class="bottom flex-center">
              <div class="price">2604.00 ₴<span class="price-unit-xvr"></span></div>
              <button class="button colord remarketing_cart_button" onclick="cart.add('153', '0.100');" data-product_id="153"><i class="fas fa-chevron-right"></i>Купити</button>
            </div>
            <div class="params">
              <div class="item flex-column">Призначення <span class="label">декоративна</span></div>
              <div class="item flex-column">Структура <span class="label">сатинова</span></div>
            </div>
          </div>



          <div class="category-products-item product-default product-layout product-grid" id="categoryProductsItem152">
            <div class="top flex-justify">
              <div class="sku">Код: 10527</div>
              <div class="review grid">
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <div class="rating-result" style="width: 0%">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
              </div>
              <div class="wishlist">
                <button type="button" class="button far fa-heart" title="В закладки" onclick="wishlist.add('152');"></button>
              </div>
            </div>
            <div class="sale statuses"><div class="category-status category-status-1 status-inline text rectangle " style=" color:#ffffff; background-color:#d04b4b;">







                Хіт продажів




              </div></div>
            <div class="image swiper swiper-initialized swiper-horizontal swiper-android swiper-backface-hidden">
              <i class="far fa-search-plus colord" data-src="/image/catalog/demo/syncms/5529d80b-54f7-11eb-0a80-050f000057b8_0.png" data-fancybox="gallery152" data-caption="Плівка глянцева 3M 2080-G15 Bright Yellow"></i>
              <a href="https://wrap.shop/plivky/kolorovi-plivky/glyanceva-plivka-3m-2080-g15-bright-yellow" title="Плівка глянцева 3M 2080-G15 Bright Yellow" class="swiper-wrapper" id="swiper-wrapper-a3fd44ef355fe3f8" aria-live="polite">
                <div class="swiper-slide item flex-center swiper-slide-active" role="group" aria-label="1 / 2" style="display: flex; width: 241px;">
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/5529d80b-54f7-11eb-0a80-050f000057b8_0-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/5529d80b-54f7-11eb-0a80-050f000057b8_0-482x482.webp 2x" alt="Плівка глянцева 3M 2080-G15 Bright Yellow" title="Плівка глянцева 3M 2080-G15 Bright Yellow" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center swiper-slide-next" role="group" aria-label="2 / 2" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/5529d80b-54f7-11eb-0a80-050f000057b8_1.png" data-fancybox="gallery152" data-caption="Плівка глянцева 3M 2080-G15 Bright Yellow"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/5529d80b-54f7-11eb-0a80-050f000057b8_1-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/5529d80b-54f7-11eb-0a80-050f000057b8_1-482x482.webp 2x" alt="Плівка глянцева 3M 2080-G15 Bright Yellow" title="Плівка глянцева 3M 2080-G15 Bright Yellow" width="200" height="200">
                </div>
              </a>
              <div class="swiper-button-next button" tabindex="0" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-a3fd44ef355fe3f8" aria-disabled="false"></div>
              <div class="swiper-button-prev button swiper-button-disabled" tabindex="-1" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-a3fd44ef355fe3f8" aria-disabled="true"></div>
              <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
            <div class="review list">
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <div class="rating-result" style="width: 0%">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
              </div>
            </div>
            <div class="center">
              <div class="category">Кольорові плівки</div>
              <a href="https://wrap.shop/plivky/kolorovi-plivky/glyanceva-plivka-3m-2080-g15-bright-yellow" title="Плівка глянцева 3M 2080-G15 Bright Yellow" class="name">Плівка глянцева 3M 2080-G15 Bright Yellow</a>
            </div>
            <div class="bottom flex-center">
              <div class="price">2562.00 ₴<span class="price-unit-xvr"></span></div>
              <button class="button colord remarketing_cart_button" onclick="cart.add('152', '0.100');" data-product_id="152"><i class="fas fa-chevron-right"></i>Купити</button>
            </div>
            <div class="params">
              <div class="item flex-column">Призначення <span class="label">декоративна</span></div>
              <div class="item flex-column">Структура <span class="label">глянцева</span></div>
            </div>
          </div>



          <div class="category-products-item product-default product-layout product-grid" id="categoryProductsItem126">
            <div class="top flex-justify">
              <div class="sku">Код: 10528</div>
              <div class="review grid">
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <div class="rating-result" style="width: 0%">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
              </div>
              <div class="wishlist">
                <button type="button" class="button far fa-heart" title="В закладки" onclick="wishlist.add('126');"></button>
              </div>
            </div>
            <div class="sale statuses"><div class="category-status category-status-1 status-inline text rectangle " style=" color:#ffffff; background-color:#d04b4b;">







                Хіт продажів




              </div></div>
            <div class="image swiper swiper-initialized swiper-horizontal swiper-android swiper-backface-hidden">
              <i class="far fa-search-plus colord" data-src="/image/catalog/demo/syncms/000908b1-c4bc-11e9-9ff4-31500003ad3d_0.png" data-fancybox="gallery126" data-caption="Плівка глянцева 3M 2080-G12 Gloss Black"></i>
              <a href="https://wrap.shop/plivky/kolorovi-plivky/plivka-glyanceva-3m-2080-g12-gloss-black" title="Плівка глянцева 3M 2080-G12 Gloss Black" class="swiper-wrapper" id="swiper-wrapper-9a03105ee316adc53" aria-live="polite">
                <div class="swiper-slide item flex-center swiper-slide-active" role="group" aria-label="1 / 2" style="display: flex; width: 241px;">
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/000908b1-c4bc-11e9-9ff4-31500003ad3d_0-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/000908b1-c4bc-11e9-9ff4-31500003ad3d_0-482x482.webp 2x" alt="Плівка глянцева 3M 2080-G12 Gloss Black" title="Плівка глянцева 3M 2080-G12 Gloss Black" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center swiper-slide-next" role="group" aria-label="2 / 2" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/000908b1-c4bc-11e9-9ff4-31500003ad3d_1.png" data-fancybox="gallery126" data-caption="Плівка глянцева 3M 2080-G12 Gloss Black"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/000908b1-c4bc-11e9-9ff4-31500003ad3d_1-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/000908b1-c4bc-11e9-9ff4-31500003ad3d_1-482x482.webp 2x" alt="Плівка глянцева 3M 2080-G12 Gloss Black" title="Плівка глянцева 3M 2080-G12 Gloss Black" width="200" height="200">
                </div>
              </a>
              <div class="swiper-button-next button" tabindex="0" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-9a03105ee316adc53" aria-disabled="false"></div>
              <div class="swiper-button-prev button swiper-button-disabled" tabindex="-1" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-9a03105ee316adc53" aria-disabled="true"></div>
              <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
            <div class="review list">
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <div class="rating-result" style="width: 0%">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
              </div>
            </div>
            <div class="center">
              <div class="category">Кольорові плівки</div>
              <a href="https://wrap.shop/plivky/kolorovi-plivky/plivka-glyanceva-3m-2080-g12-gloss-black" title="Плівка глянцева 3M 2080-G12 Gloss Black" class="name">Плівка глянцева 3M 2080-G12 Gloss Black</a>
            </div>
            <div class="bottom flex-center">
              <div class="price">2562.00 ₴<span class="price-unit-xvr"></span></div>
              <button class="button colord remarketing_cart_button" onclick="cart.add('126', '0.100');" data-product_id="126"><i class="fas fa-chevron-right"></i>Купити</button>
            </div>
            <div class="params">
              <div class="item flex-column">Призначення <span class="label">декоративна</span></div>
              <div class="item flex-column">Структура <span class="label">глянцева</span></div>
            </div>
          </div>



          <div class="category-products-item product-default product-layout product-grid" id="categoryProductsItem520">
            <div class="top flex-justify">
              <div class="sku">Код: 10924</div>
              <div class="review grid">
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <div class="rating-result" style="width: 80%">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
              </div>
              <div class="wishlist">
                <button type="button" class="button far fa-heart" title="В закладки" onclick="wishlist.add('520');"></button>
              </div>
            </div>
            <div class="sale statuses"><div class="category-status category-status-1 status-inline text rectangle " style=" color:#ffffff; background-color:#d04b4b;">







                Хіт продажів




              </div></div>
            <div class="image swiper swiper-initialized swiper-horizontal swiper-android swiper-backface-hidden">
              <i class="far fa-search-plus colord" data-src="/image/catalog/demo/syncms/761abe09-f467-11e6-7a34-5acf002b6f8a_0.png" data-fancybox="gallery520" data-caption="Плівка матова KPMF Black Matte K89021"></i>
              <a href="https://wrap.shop/plivky/kolorovi-plivky/matova-plivka-kpmf-black-matte-k89021" title="Плівка матова KPMF Black Matte K89021" class="swiper-wrapper" id="swiper-wrapper-46c22074aec8f6f10" aria-live="polite">
                <div class="swiper-slide item flex-center swiper-slide-active" role="group" aria-label="1 / 1" style="display: flex; width: 241px;">
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/761abe09-f467-11e6-7a34-5acf002b6f8a_0-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/761abe09-f467-11e6-7a34-5acf002b6f8a_0-482x482.webp 2x" alt="Плівка матова KPMF Black Matte K89021" title="Плівка матова KPMF Black Matte K89021" width="200" height="200">
                </div>
              </a>
              <div class="swiper-button-next button swiper-button-disabled swiper-button-lock" tabindex="-1" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-46c22074aec8f6f10" aria-disabled="true"></div>
              <div class="swiper-button-prev button swiper-button-disabled swiper-button-lock" tabindex="-1" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-46c22074aec8f6f10" aria-disabled="true"></div>
              <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
            <div class="review list">
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <div class="rating-result" style="width: 80%">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
              </div>
            </div>
            <div class="center">
              <div class="category">Кольорові плівки</div>
              <a href="https://wrap.shop/plivky/kolorovi-plivky/matova-plivka-kpmf-black-matte-k89021" title="Плівка матова KPMF Black Matte K89021" class="name">Плівка матова KPMF Black Matte K89021</a>
            </div>
            <div class="bottom flex-center">
              <div class="price">1008.00 ₴<span class="price-unit-xvr"></span></div>
              <button class="button colord remarketing_cart_button" onclick="cart.add('520', '0.100');" data-product_id="520"><i class="fas fa-chevron-right"></i>Купити</button>
            </div>
            <div class="params">
              <div class="item flex-column">Призначення <span class="label">декоративна</span></div>
              <div class="item flex-column">Структура <span class="label">матова</span></div>
            </div>
          </div>



          <div class="category-products-item product-default product-layout product-grid" id="categoryProductsItem155">
            <div class="top flex-justify">
              <div class="sku">Код: 10529</div>
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
                <button type="button" class="button far fa-heart" title="В закладки" onclick="wishlist.add('155');"></button>
              </div>
            </div>
            <div class="sale statuses"><div class="category-status category-status-1 status-inline text rectangle " style=" color:#ffffff; background-color:#d04b4b;">







                Хіт продажів




              </div></div>
            <div class="image swiper swiper-initialized swiper-horizontal swiper-android swiper-backface-hidden">
              <i class="far fa-search-plus colord" data-src="/image/catalog/demo/syncms/5d0969c9-60b0-11eb-0a80-00ac00118f9f_0.png" data-fancybox="gallery155" data-caption="Плівка глянцева 3M 2080-G31 Gloss Storm Grey"></i>
              <a href="https://wrap.shop/plivky/kolorovi-plivky/glyanceva-plivka-3m-2080-g31-gloss-storm-grey" title="Плівка глянцева 3M 2080-G31 Gloss Storm Grey" class="swiper-wrapper" id="swiper-wrapper-418f7af4ee295b1e" aria-live="polite">
                <div class="swiper-slide item flex-center swiper-slide-active" role="group" aria-label="1 / 4" style="display: flex; width: 241px;">
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/5d0969c9-60b0-11eb-0a80-00ac00118f9f_0-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/5d0969c9-60b0-11eb-0a80-00ac00118f9f_0-482x482.webp 2x" alt="Плівка глянцева 3M 2080-G31 Gloss Storm Grey" title="Плівка глянцева 3M 2080-G31 Gloss Storm Grey" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center swiper-slide-next" role="group" aria-label="2 / 4" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/5d0969c9-60b0-11eb-0a80-00ac00118f9f_1.png" data-fancybox="gallery155" data-caption="Плівка глянцева 3M 2080-G31 Gloss Storm Grey"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/5d0969c9-60b0-11eb-0a80-00ac00118f9f_1-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/5d0969c9-60b0-11eb-0a80-00ac00118f9f_1-482x482.webp 2x" alt="Плівка глянцева 3M 2080-G31 Gloss Storm Grey" title="Плівка глянцева 3M 2080-G31 Gloss Storm Grey" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="3 / 4" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/5d0969c9-60b0-11eb-0a80-00ac00118f9f_2.png" data-fancybox="gallery155" data-caption="Плівка глянцева 3M 2080-G31 Gloss Storm Grey"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/5d0969c9-60b0-11eb-0a80-00ac00118f9f_2-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/5d0969c9-60b0-11eb-0a80-00ac00118f9f_2-482x482.webp 2x" alt="Плівка глянцева 3M 2080-G31 Gloss Storm Grey" title="Плівка глянцева 3M 2080-G31 Gloss Storm Grey" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="4 / 4" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/5d0969c9-60b0-11eb-0a80-00ac00118f9f_3.png" data-fancybox="gallery155" data-caption="Плівка глянцева 3M 2080-G31 Gloss Storm Grey"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/5d0969c9-60b0-11eb-0a80-00ac00118f9f_3-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/5d0969c9-60b0-11eb-0a80-00ac00118f9f_3-482x482.webp 2x" alt="Плівка глянцева 3M 2080-G31 Gloss Storm Grey" title="Плівка глянцева 3M 2080-G31 Gloss Storm Grey" width="200" height="200">
                </div>
              </a>
              <div class="swiper-button-next button" tabindex="0" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-418f7af4ee295b1e" aria-disabled="false"></div>
              <div class="swiper-button-prev button swiper-button-disabled" tabindex="-1" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-418f7af4ee295b1e" aria-disabled="true"></div>
              <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
            <div class="review list">
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
            <div class="center">
              <div class="category">Кольорові плівки</div>
              <a href="https://wrap.shop/plivky/kolorovi-plivky/glyanceva-plivka-3m-2080-g31-gloss-storm-grey" title="Плівка глянцева 3M 2080-G31 Gloss Storm Grey" class="name">Плівка глянцева 3M 2080-G31 Gloss Storm Grey</a>
            </div>
            <div class="bottom flex-center">
              <div class="price">2562.00 ₴<span class="price-unit-xvr"></span></div>
              <button class="button colord remarketing_cart_button" onclick="cart.add('155', '0.100');" data-product_id="155"><i class="fas fa-chevron-right"></i>Купити</button>
            </div>
            <div class="params">
              <div class="item flex-column">Призначення <span class="label">декоративна</span></div>
              <div class="item flex-column">Структура <span class="label">глянцева</span></div>
            </div>
          </div>

          <a href="https://wrap.shop/plivky/kolorovi-plivky/?ocf=F1S0V11" title="3m color" class="category-products-item product-default product-layout banner product-grid">
            <img class="vertical" data-src="/image/catalog/category/webp/banners catalog/1-3m-vert.webp" alt="3m color" title="3m color" src="/image/catalog/category/webp/banners catalog/1-3m-vert.webp">
            <img class="gorizont" data-src="/image/catalog/category/webp/banners catalog/1-3m-gor.webp" alt="3m color" title="3m color">
          </a>


          <div class="category-products-item product-default product-layout product-grid" id="categoryProductsItem149">
            <div class="top flex-justify">
              <div class="sku">Код: 10574</div>
              <div class="review grid">
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <div class="rating-result" style="width: 0%">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
              </div>
              <div class="wishlist">
                <button type="button" class="button far fa-heart" title="В закладки" onclick="wishlist.add('149');"></button>
              </div>
            </div>
            <div class="sale statuses"><div class="category-status category-status-1 status-inline text rectangle " style=" color:#ffffff; background-color:#d04b4b;">







                Хіт продажів




              </div></div>
            <div class="image swiper swiper-initialized swiper-horizontal swiper-android swiper-backface-hidden">
              <i class="far fa-search-plus colord" data-src="/image/catalog/demo/syncms/52700fed-429e-11ea-0a80-03cf00099954_0.png" data-fancybox="gallery149" data-caption="Плівка матова 3M 2080-S12 Satin Black"></i>
              <a href="https://wrap.shop/plivky/kolorovi-plivky/plivka-matova-3m-2080-s12-satin-black" title="Плівка матова 3M 2080-S12 Satin Black" class="swiper-wrapper" id="swiper-wrapper-e5a8768814616192" aria-live="polite">
                <div class="swiper-slide item flex-center swiper-slide-active" role="group" aria-label="1 / 6" style="display: flex; width: 241px;">
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/52700fed-429e-11ea-0a80-03cf00099954_0-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/52700fed-429e-11ea-0a80-03cf00099954_0-482x482.webp 2x" alt="Плівка матова 3M 2080-S12 Satin Black" title="Плівка матова 3M 2080-S12 Satin Black" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center swiper-slide-next" role="group" aria-label="2 / 6" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/52700fed-429e-11ea-0a80-03cf00099954_1.png" data-fancybox="gallery149" data-caption="Плівка матова 3M 2080-S12 Satin Black"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/52700fed-429e-11ea-0a80-03cf00099954_1-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/52700fed-429e-11ea-0a80-03cf00099954_1-482x482.webp 2x" alt="Плівка матова 3M 2080-S12 Satin Black" title="Плівка матова 3M 2080-S12 Satin Black" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="3 / 6" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/52700fed-429e-11ea-0a80-03cf00099954_2.png" data-fancybox="gallery149" data-caption="Плівка матова 3M 2080-S12 Satin Black"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/52700fed-429e-11ea-0a80-03cf00099954_2-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/52700fed-429e-11ea-0a80-03cf00099954_2-482x482.webp 2x" alt="Плівка матова 3M 2080-S12 Satin Black" title="Плівка матова 3M 2080-S12 Satin Black" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="4 / 6" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/52700fed-429e-11ea-0a80-03cf00099954_3.png" data-fancybox="gallery149" data-caption="Плівка матова 3M 2080-S12 Satin Black"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/52700fed-429e-11ea-0a80-03cf00099954_3-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/52700fed-429e-11ea-0a80-03cf00099954_3-482x482.webp 2x" alt="Плівка матова 3M 2080-S12 Satin Black" title="Плівка матова 3M 2080-S12 Satin Black" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="5 / 6" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/52700fed-429e-11ea-0a80-03cf00099954_4.png" data-fancybox="gallery149" data-caption="Плівка матова 3M 2080-S12 Satin Black"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/52700fed-429e-11ea-0a80-03cf00099954_4-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/52700fed-429e-11ea-0a80-03cf00099954_4-482x482.webp 2x" alt="Плівка матова 3M 2080-S12 Satin Black" title="Плівка матова 3M 2080-S12 Satin Black" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="6 / 6" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/52700fed-429e-11ea-0a80-03cf00099954_5.png" data-fancybox="gallery149" data-caption="Плівка матова 3M 2080-S12 Satin Black"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/52700fed-429e-11ea-0a80-03cf00099954_5-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/52700fed-429e-11ea-0a80-03cf00099954_5-482x482.webp 2x" alt="Плівка матова 3M 2080-S12 Satin Black" title="Плівка матова 3M 2080-S12 Satin Black" width="200" height="200">
                </div>
              </a>
              <div class="swiper-button-next button" tabindex="0" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-e5a8768814616192" aria-disabled="false"></div>
              <div class="swiper-button-prev button swiper-button-disabled" tabindex="-1" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-e5a8768814616192" aria-disabled="true"></div>
              <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
            <div class="review list">
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <div class="rating-result" style="width: 0%">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
              </div>
            </div>
            <div class="center">
              <div class="category">Кольорові плівки</div>
              <a href="https://wrap.shop/plivky/kolorovi-plivky/plivka-matova-3m-2080-s12-satin-black" title="Плівка матова 3M 2080-S12 Satin Black" class="name">Плівка матова 3M 2080-S12 Satin Black</a>
            </div>
            <div class="bottom flex-center">
              <div class="price">2562.00 ₴<span class="price-unit-xvr"></span></div>
              <button class="button colord remarketing_cart_button" onclick="cart.add('149', '0.100');" data-product_id="149"><i class="fas fa-chevron-right"></i>Купити</button>
            </div>
            <div class="params">
              <div class="item flex-column">Призначення <span class="label">декоративна</span></div>
              <div class="item flex-column">Структура <span class="label">сатинова</span></div>
            </div>
          </div>



          <div class="category-products-item product-default product-layout product-grid" id="categoryProductsItem175">
            <div class="top flex-justify">
              <div class="sku">Код: 10563</div>
              <div class="review grid">
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <div class="rating-result" style="width: 0%">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
              </div>
              <div class="wishlist">
                <button type="button" class="button far fa-heart" title="В закладки" onclick="wishlist.add('175');"></button>
              </div>
            </div>
            <div class="sale statuses"><div class="category-status category-status-1 status-inline text rectangle " style=" color:#ffffff; background-color:#d04b4b;">







                Хіт продажів




              </div></div>
            <div class="image swiper swiper-initialized swiper-horizontal swiper-android swiper-backface-hidden">
              <i class="far fa-search-plus colord" data-src="/image/catalog/demo/syncms/c32f7da4-5f2d-11eb-0a80-010b00005b57_0.png" data-fancybox="gallery175" data-caption="Плівка сатинова 3M 2080-SP273 Vampire Red"></i>
              <a href="https://wrap.shop/plivky/kolorovi-plivky/plivka-3m-2080-sp273-vampire-red" title="Плівка сатинова 3M 2080-SP273 Vampire Red" class="swiper-wrapper" id="swiper-wrapper-93bf36bce783f906" aria-live="polite">
                <div class="swiper-slide item flex-center swiper-slide-active" role="group" aria-label="1 / 4" style="display: flex; width: 241px;">
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/c32f7da4-5f2d-11eb-0a80-010b00005b57_0-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/c32f7da4-5f2d-11eb-0a80-010b00005b57_0-482x482.webp 2x" alt="Плівка сатинова 3M 2080-SP273 Vampire Red" title="Плівка сатинова 3M 2080-SP273 Vampire Red" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center swiper-slide-next" role="group" aria-label="2 / 4" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/c32f7da4-5f2d-11eb-0a80-010b00005b57_1.png" data-fancybox="gallery175" data-caption="Плівка сатинова 3M 2080-SP273 Vampire Red"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/c32f7da4-5f2d-11eb-0a80-010b00005b57_1-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/c32f7da4-5f2d-11eb-0a80-010b00005b57_1-482x482.webp 2x" alt="Плівка сатинова 3M 2080-SP273 Vampire Red" title="Плівка сатинова 3M 2080-SP273 Vampire Red" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="3 / 4" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/c32f7da4-5f2d-11eb-0a80-010b00005b57_2.png" data-fancybox="gallery175" data-caption="Плівка сатинова 3M 2080-SP273 Vampire Red"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/c32f7da4-5f2d-11eb-0a80-010b00005b57_2-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/c32f7da4-5f2d-11eb-0a80-010b00005b57_2-482x482.webp 2x" alt="Плівка сатинова 3M 2080-SP273 Vampire Red" title="Плівка сатинова 3M 2080-SP273 Vampire Red" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="4 / 4" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/c32f7da4-5f2d-11eb-0a80-010b00005b57_3.png" data-fancybox="gallery175" data-caption="Плівка сатинова 3M 2080-SP273 Vampire Red"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/c32f7da4-5f2d-11eb-0a80-010b00005b57_3-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/c32f7da4-5f2d-11eb-0a80-010b00005b57_3-482x482.webp 2x" alt="Плівка сатинова 3M 2080-SP273 Vampire Red" title="Плівка сатинова 3M 2080-SP273 Vampire Red" width="200" height="200">
                </div>
              </a>
              <div class="swiper-button-next button" tabindex="0" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-93bf36bce783f906" aria-disabled="false"></div>
              <div class="swiper-button-prev button swiper-button-disabled" tabindex="-1" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-93bf36bce783f906" aria-disabled="true"></div>
              <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
            <div class="review list">
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <div class="rating-result" style="width: 0%">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
              </div>
            </div>
            <div class="center">
              <div class="category">Кольорові плівки</div>
              <a href="https://wrap.shop/plivky/kolorovi-plivky/plivka-3m-2080-sp273-vampire-red" title="Плівка сатинова 3M 2080-SP273 Vampire Red" class="name">Плівка сатинова 3M 2080-SP273 Vampire Red</a>
            </div>
            <div class="bottom flex-center">
              <div class="price">3024.00 ₴<span class="price-unit-xvr"></span></div>
              <button class="button colord remarketing_cart_button" onclick="cart.add('175', '0.100');" data-product_id="175"><i class="fas fa-chevron-right"></i>Купити</button>
            </div>
            <div class="params">
              <div class="item flex-column">Призначення <span class="label">декоративна</span></div>
              <div class="item flex-column">Структура <span class="label">сатинова</span></div>
            </div>
          </div>



          <div class="category-products-item product-default product-layout product-grid" id="categoryProductsItem387">
            <div class="top flex-justify">
              <div class="sku">Код: 10975</div>
              <div class="review grid">
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <div class="rating-result" style="width: 0%">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
              </div>
              <div class="wishlist">
                <button type="button" class="button far fa-heart" title="В закладки" onclick="wishlist.add('387');"></button>
              </div>
            </div>
            <div class="sale statuses"><div class="category-status category-status-1 status-inline text rectangle " style=" color:#ffffff; background-color:#d04b4b;">







                Хіт продажів




              </div></div>
            <div class="image swiper swiper-initialized swiper-horizontal swiper-android swiper-backface-hidden">
              <i class="far fa-search-plus colord" data-src="/image/catalog/demo/syncms/19e16494-20cc-11ee-0a80-10fe00142a7c_0.png" data-fancybox="gallery387" data-caption="Плівка глянцева Kybertane Roma Beige KYBC0026"></i>
              <a href="https://wrap.shop/plivky/kolorovi-plivky/glyanceva-plivka-kybertane-roma-beige-kybc0026" title="Плівка глянцева Kybertane Roma Beige KYBC0026" class="swiper-wrapper" id="swiper-wrapper-0753d10cea78aa6b2" aria-live="polite">
                <div class="swiper-slide item flex-center swiper-slide-active" role="group" aria-label="1 / 4" style="display: flex; width: 241px;">
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/19e16494-20cc-11ee-0a80-10fe00142a7c_0-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/19e16494-20cc-11ee-0a80-10fe00142a7c_0-482x482.webp 2x" alt="Плівка глянцева Kybertane Roma Beige KYBC0026" title="Плівка глянцева Kybertane Roma Beige KYBC0026" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center swiper-slide-next" role="group" aria-label="2 / 4" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/19e16494-20cc-11ee-0a80-10fe00142a7c_1.png" data-fancybox="gallery387" data-caption="Плівка глянцева Kybertane Roma Beige KYBC0026"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/19e16494-20cc-11ee-0a80-10fe00142a7c_1-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/19e16494-20cc-11ee-0a80-10fe00142a7c_1-482x482.webp 2x" alt="Плівка глянцева Kybertane Roma Beige KYBC0026" title="Плівка глянцева Kybertane Roma Beige KYBC0026" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="3 / 4" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/19e16494-20cc-11ee-0a80-10fe00142a7c_2.png" data-fancybox="gallery387" data-caption="Плівка глянцева Kybertane Roma Beige KYBC0026"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/19e16494-20cc-11ee-0a80-10fe00142a7c_2-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/19e16494-20cc-11ee-0a80-10fe00142a7c_2-482x482.webp 2x" alt="Плівка глянцева Kybertane Roma Beige KYBC0026" title="Плівка глянцева Kybertane Roma Beige KYBC0026" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="4 / 4" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/19e16494-20cc-11ee-0a80-10fe00142a7c_3.png" data-fancybox="gallery387" data-caption="Плівка глянцева Kybertane Roma Beige KYBC0026"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/19e16494-20cc-11ee-0a80-10fe00142a7c_3-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/19e16494-20cc-11ee-0a80-10fe00142a7c_3-482x482.webp 2x" alt="Плівка глянцева Kybertane Roma Beige KYBC0026" title="Плівка глянцева Kybertane Roma Beige KYBC0026" width="200" height="200">
                </div>
              </a>
              <div class="swiper-button-next button" tabindex="0" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-0753d10cea78aa6b2" aria-disabled="false"></div>
              <div class="swiper-button-prev button swiper-button-disabled" tabindex="-1" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-0753d10cea78aa6b2" aria-disabled="true"></div>
              <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
            <div class="review list">
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <div class="rating-result" style="width: 0%">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
              </div>
            </div>
            <div class="center">
              <div class="category">Кольорові плівки</div>
              <a href="https://wrap.shop/plivky/kolorovi-plivky/glyanceva-plivka-kybertane-roma-beige-kybc0026" title="Плівка глянцева Kybertane Roma Beige KYBC0026" class="name">Плівка глянцева Kybertane Roma Beige KYBC0026</a>
            </div>
            <div class="bottom flex-center">
              <div class="price">2100.00 ₴<span class="price-unit-xvr"></span></div>
              <button class="button colord remarketing_cart_button" onclick="cart.add('387', '0.100');" data-product_id="387"><i class="fas fa-chevron-right"></i>Купити</button>
            </div>
            <div class="params">
              <div class="item flex-column">Призначення <span class="label">декоративна</span></div>
              <div class="item flex-column">Структура <span class="label">глянцева</span></div>
            </div>
          </div>



          <div class="category-products-item product-default product-layout product-grid" id="categoryProductsItem401">
            <div class="top flex-justify">
              <div class="sku">Код: 10966</div>
              <div class="review grid">
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <div class="rating-result" style="width: 0%">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
              </div>
              <div class="wishlist">
                <button type="button" class="button far fa-heart" title="В закладки" onclick="wishlist.add('401');"></button>
              </div>
            </div>
            <div class="sale statuses"><div class="category-status category-status-1 status-inline text rectangle " style=" color:#ffffff; background-color:#d04b4b;">







                Хіт продажів




              </div></div>
            <div class="image swiper swiper-initialized swiper-horizontal swiper-android swiper-backface-hidden">
              <i class="far fa-search-plus colord" data-src="/image/catalog/demo/syncms/64214cf3-14e6-11ee-0a80-06310010e02b_0.png" data-fancybox="gallery401" data-caption="Плівка глянцева Kybertane Gulfstream Aqua KYBC0712"></i>
              <a href="https://wrap.shop/plivky/kolorovi-plivky/glyanceva-plivka-kybertane-gulfstream-aqua-kybc0712" title="Плівка глянцева Kybertane Gulfstream Aqua KYBC0712" class="swiper-wrapper" id="swiper-wrapper-d4d292a57bc77ebf" aria-live="polite">
                <div class="swiper-slide item flex-center swiper-slide-active" role="group" aria-label="1 / 4" style="display: flex; width: 241px;">
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/64214cf3-14e6-11ee-0a80-06310010e02b_0-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/64214cf3-14e6-11ee-0a80-06310010e02b_0-482x482.webp 2x" alt="Плівка глянцева Kybertane Gulfstream Aqua KYBC0712" title="Плівка глянцева Kybertane Gulfstream Aqua KYBC0712" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center swiper-slide-next" role="group" aria-label="2 / 4" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/64214cf3-14e6-11ee-0a80-06310010e02b_1.png" data-fancybox="gallery401" data-caption="Плівка глянцева Kybertane Gulfstream Aqua KYBC0712"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/64214cf3-14e6-11ee-0a80-06310010e02b_1-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/64214cf3-14e6-11ee-0a80-06310010e02b_1-482x482.webp 2x" alt="Плівка глянцева Kybertane Gulfstream Aqua KYBC0712" title="Плівка глянцева Kybertane Gulfstream Aqua KYBC0712" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="3 / 4" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/64214cf3-14e6-11ee-0a80-06310010e02b_2.png" data-fancybox="gallery401" data-caption="Плівка глянцева Kybertane Gulfstream Aqua KYBC0712"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/64214cf3-14e6-11ee-0a80-06310010e02b_2-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/64214cf3-14e6-11ee-0a80-06310010e02b_2-482x482.webp 2x" alt="Плівка глянцева Kybertane Gulfstream Aqua KYBC0712" title="Плівка глянцева Kybertane Gulfstream Aqua KYBC0712" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="4 / 4" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/64214cf3-14e6-11ee-0a80-06310010e02b_3.png" data-fancybox="gallery401" data-caption="Плівка глянцева Kybertane Gulfstream Aqua KYBC0712"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/64214cf3-14e6-11ee-0a80-06310010e02b_3-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/64214cf3-14e6-11ee-0a80-06310010e02b_3-482x482.webp 2x" alt="Плівка глянцева Kybertane Gulfstream Aqua KYBC0712" title="Плівка глянцева Kybertane Gulfstream Aqua KYBC0712" width="200" height="200">
                </div>
              </a>
              <div class="swiper-button-next button" tabindex="0" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-d4d292a57bc77ebf" aria-disabled="false"></div>
              <div class="swiper-button-prev button swiper-button-disabled" tabindex="-1" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-d4d292a57bc77ebf" aria-disabled="true"></div>
              <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
            <div class="review list">
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <div class="rating-result" style="width: 0%">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
              </div>
            </div>
            <div class="center">
              <div class="category">Кольорові плівки</div>
              <a href="https://wrap.shop/plivky/kolorovi-plivky/glyanceva-plivka-kybertane-gulfstream-aqua-kybc0712" title="Плівка глянцева Kybertane Gulfstream Aqua KYBC0712" class="name">Плівка глянцева Kybertane Gulfstream Aqua KYBC0712</a>
            </div>
            <div class="bottom flex-center">
              <div class="price">2100.00 ₴<span class="price-unit-xvr"></span></div>
              <button class="button colord remarketing_cart_button" onclick="cart.add('401', '0.100');" data-product_id="401"><i class="fas fa-chevron-right"></i>Купити</button>
            </div>
            <div class="params">
              <div class="item flex-column">Призначення <span class="label">декоративна</span></div>
              <div class="item flex-column">Структура <span class="label">глянцева</span></div>
            </div>
          </div>



          <div class="category-products-item product-default product-layout product-grid" id="categoryProductsItem389">
            <div class="top flex-justify">
              <div class="sku">Код: 10944</div>
              <div class="review grid">
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <div class="rating-result" style="width: 0%">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
              </div>
              <div class="wishlist">
                <button type="button" class="button far fa-heart" title="В закладки" onclick="wishlist.add('389');"></button>
              </div>
            </div>
            <div class="sale statuses"></div>
            <div class="image swiper swiper-initialized swiper-horizontal swiper-android swiper-backface-hidden">
              <i class="far fa-search-plus colord" data-src="/image/catalog/demo/syncms/307b327d-14e6-11ee-0a80-0d9400100636_0.png" data-fancybox="gallery389" data-caption="Плівка глянцева Kybertane Stellar Gray KYBC0102"></i>
              <a href="https://wrap.shop/plivky/kolorovi-plivky/glyanceva-plivka-kybertane-stellar-gray-kybc0102" title="Плівка глянцева Kybertane Stellar Gray KYBC0102" class="swiper-wrapper" id="swiper-wrapper-3f2c4051c942097f" aria-live="polite">
                <div class="swiper-slide item flex-center swiper-slide-active" role="group" aria-label="1 / 2" style="display: flex; width: 241px;">
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/307b327d-14e6-11ee-0a80-0d9400100636_0-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/307b327d-14e6-11ee-0a80-0d9400100636_0-482x482.webp 2x" alt="Плівка глянцева Kybertane Stellar Gray KYBC0102" title="Плівка глянцева Kybertane Stellar Gray KYBC0102" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center swiper-slide-next" role="group" aria-label="2 / 2" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/307b327d-14e6-11ee-0a80-0d9400100636_1.png" data-fancybox="gallery389" data-caption="Плівка глянцева Kybertane Stellar Gray KYBC0102"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/307b327d-14e6-11ee-0a80-0d9400100636_1-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/307b327d-14e6-11ee-0a80-0d9400100636_1-482x482.webp 2x" alt="Плівка глянцева Kybertane Stellar Gray KYBC0102" title="Плівка глянцева Kybertane Stellar Gray KYBC0102" width="200" height="200">
                </div>
              </a>
              <div class="swiper-button-next button" tabindex="0" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-3f2c4051c942097f" aria-disabled="false"></div>
              <div class="swiper-button-prev button swiper-button-disabled" tabindex="-1" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-3f2c4051c942097f" aria-disabled="true"></div>
              <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
            <div class="review list">
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <div class="rating-result" style="width: 0%">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
              </div>
            </div>
            <div class="center">
              <div class="category">Кольорові плівки</div>
              <a href="https://wrap.shop/plivky/kolorovi-plivky/glyanceva-plivka-kybertane-stellar-gray-kybc0102" title="Плівка глянцева Kybertane Stellar Gray KYBC0102" class="name">Плівка глянцева Kybertane Stellar Gray KYBC0102</a>
            </div>
            <div class="bottom flex-center">
              <div class="price">2100.00 ₴<span class="price-unit-xvr"></span></div>
              <button class="button colord remarketing_cart_button" onclick="cart.add('389', '0.100');" data-product_id="389"><i class="fas fa-chevron-right"></i>Купити</button>
            </div>
            <div class="params">
              <div class="item flex-column">Призначення <span class="label">декоративна</span></div>
              <div class="item flex-column">Структура <span class="label">глянцева</span></div>
            </div>
          </div>

          <a href="https://wrap.shop/plivky/zahysni-plivky/?ocf=F1S0V44" title="kybertane ppf" class="category-products-item product-default product-layout banner product-grid">
            <img class="vertical" data-src="/image/catalog/category/webp/banners catalog/3-kyb-ppf-vert.webp" alt="kybertane ppf" title="kybertane ppf">
            <img class="gorizont" data-src="/image/catalog/category/webp/banners catalog/3-kyb-ppf-gor.webp" alt="kybertane ppf" title="kybertane ppf">
          </a>


          <div class="category-products-item product-default product-layout product-grid" id="categoryProductsItem390">
            <div class="top flex-justify">
              <div class="sku">Код: 10955</div>
              <div class="review grid">
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <div class="rating-result" style="width: 0%">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
              </div>
              <div class="wishlist">
                <button type="button" class="button far fa-heart" title="В закладки" onclick="wishlist.add('390');"></button>
              </div>
            </div>
            <div class="sale statuses"></div>
            <div class="image swiper swiper-initialized swiper-horizontal swiper-android swiper-backface-hidden">
              <i class="far fa-search-plus colord" data-src="/image/catalog/demo/syncms/309ff1ef-215d-11ee-0a80-010c001f7846_0.png" data-fancybox="gallery390" data-caption="Плівка глянцева Kybertane Lemon Sunshine KYBC0538"></i>
              <a href="https://wrap.shop/plivky/kolorovi-plivky/glyanceva-plivka-kybertane-lemon-sunshine-kybc0538" title="Плівка глянцева Kybertane Lemon Sunshine KYBC0538" class="swiper-wrapper" id="swiper-wrapper-3f52102d86c039a78" aria-live="polite">
                <div class="swiper-slide item flex-center swiper-slide-active" role="group" aria-label="1 / 2" style="display: flex; width: 241px;">
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/309ff1ef-215d-11ee-0a80-010c001f7846_0-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/309ff1ef-215d-11ee-0a80-010c001f7846_0-482x482.webp 2x" alt="Плівка глянцева Kybertane Lemon Sunshine KYBC0538" title="Плівка глянцева Kybertane Lemon Sunshine KYBC0538" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center swiper-slide-next" role="group" aria-label="2 / 2" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/309ff1ef-215d-11ee-0a80-010c001f7846_1.png" data-fancybox="gallery390" data-caption="Плівка глянцева Kybertane Lemon Sunshine KYBC0538"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/309ff1ef-215d-11ee-0a80-010c001f7846_1-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/309ff1ef-215d-11ee-0a80-010c001f7846_1-482x482.webp 2x" alt="Плівка глянцева Kybertane Lemon Sunshine KYBC0538" title="Плівка глянцева Kybertane Lemon Sunshine KYBC0538" width="200" height="200">
                </div>
              </a>
              <div class="swiper-button-next button" tabindex="0" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-3f52102d86c039a78" aria-disabled="false"></div>
              <div class="swiper-button-prev button swiper-button-disabled" tabindex="-1" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-3f52102d86c039a78" aria-disabled="true"></div>
              <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
            <div class="review list">
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <div class="rating-result" style="width: 0%">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
              </div>
            </div>
            <div class="center">
              <div class="category">Кольорові плівки</div>
              <a href="https://wrap.shop/plivky/kolorovi-plivky/glyanceva-plivka-kybertane-lemon-sunshine-kybc0538" title="Плівка глянцева Kybertane Lemon Sunshine KYBC0538" class="name">Плівка глянцева Kybertane Lemon Sunshine KYBC0538</a>
            </div>
            <div class="bottom flex-center">
              <div class="price">2100.00 ₴<span class="price-unit-xvr"></span></div>
              <button class="button colord remarketing_cart_button" onclick="cart.add('390', '0.100');" data-product_id="390"><i class="fas fa-chevron-right"></i>Купити</button>
            </div>
            <div class="params">
              <div class="item flex-column">Призначення <span class="label">декоративна</span></div>
              <div class="item flex-column">Структура <span class="label">глянцева</span></div>
            </div>
          </div>



          <div class="category-products-item product-default product-layout product-grid" id="categoryProductsItem431">
            <div class="top flex-justify">
              <div class="sku">Код: 11060</div>
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
                <button type="button" class="button far fa-heart" title="В закладки" onclick="wishlist.add('431');"></button>
              </div>
            </div>
            <div class="sale statuses"><div class="category-status category-status-1 status-inline text rectangle " style=" color:#ffffff; background-color:#d04b4b;">







                Хіт продажів




              </div></div>
            <div class="image swiper swiper-initialized swiper-horizontal swiper-android swiper-backface-hidden">
              <i class="far fa-search-plus colord" data-src="/image/catalog/demo/syncms/a01eb08f-f91a-11ec-0a80-03bc0009a61f_0.png" data-fancybox="gallery431" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,53 м"></i>
              <a href="https://wrap.shop/plivky/zahysni-plivky/antygravijna-glyanceva-plivka-kybertane-ppf-ultra-gloss" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,53 м" class="swiper-wrapper" id="swiper-wrapper-85107576ab46a7e44" aria-live="polite">
                <div class="swiper-slide item flex-center swiper-slide-active" role="group" aria-label="1 / 9" style="display: flex; width: 241px;">
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/a01eb08f-f91a-11ec-0a80-03bc0009a61f_0-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/a01eb08f-f91a-11ec-0a80-03bc0009a61f_0-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,53 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,53 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center swiper-slide-next" role="group" aria-label="2 / 9" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/a01eb08f-f91a-11ec-0a80-03bc0009a61f_1.png" data-fancybox="gallery431" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,53 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/a01eb08f-f91a-11ec-0a80-03bc0009a61f_1-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/a01eb08f-f91a-11ec-0a80-03bc0009a61f_1-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,53 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,53 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="3 / 9" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/a01eb08f-f91a-11ec-0a80-03bc0009a61f_2.png" data-fancybox="gallery431" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,53 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/a01eb08f-f91a-11ec-0a80-03bc0009a61f_2-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/a01eb08f-f91a-11ec-0a80-03bc0009a61f_2-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,53 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,53 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="4 / 9" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/a01eb08f-f91a-11ec-0a80-03bc0009a61f_3.png" data-fancybox="gallery431" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,53 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/a01eb08f-f91a-11ec-0a80-03bc0009a61f_3-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/a01eb08f-f91a-11ec-0a80-03bc0009a61f_3-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,53 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,53 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="5 / 9" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/a01eb08f-f91a-11ec-0a80-03bc0009a61f_4.png" data-fancybox="gallery431" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,53 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/a01eb08f-f91a-11ec-0a80-03bc0009a61f_4-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/a01eb08f-f91a-11ec-0a80-03bc0009a61f_4-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,53 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,53 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="6 / 9" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/a01eb08f-f91a-11ec-0a80-03bc0009a61f_5.png" data-fancybox="gallery431" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,53 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/a01eb08f-f91a-11ec-0a80-03bc0009a61f_5-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/a01eb08f-f91a-11ec-0a80-03bc0009a61f_5-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,53 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,53 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="7 / 9" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/a01eb08f-f91a-11ec-0a80-03bc0009a61f_6.png" data-fancybox="gallery431" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,53 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/a01eb08f-f91a-11ec-0a80-03bc0009a61f_6-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/a01eb08f-f91a-11ec-0a80-03bc0009a61f_6-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,53 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,53 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="8 / 9" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/a01eb08f-f91a-11ec-0a80-03bc0009a61f_7.png" data-fancybox="gallery431" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,53 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/a01eb08f-f91a-11ec-0a80-03bc0009a61f_7-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/a01eb08f-f91a-11ec-0a80-03bc0009a61f_7-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,53 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,53 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="9 / 9" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/a01eb08f-f91a-11ec-0a80-03bc0009a61f_8.png" data-fancybox="gallery431" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,53 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/a01eb08f-f91a-11ec-0a80-03bc0009a61f_8-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/a01eb08f-f91a-11ec-0a80-03bc0009a61f_8-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,53 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,53 м" width="200" height="200">
                </div>
              </a>
              <div class="swiper-button-next button" tabindex="0" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-85107576ab46a7e44" aria-disabled="false"></div>
              <div class="swiper-button-prev button swiper-button-disabled" tabindex="-1" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-85107576ab46a7e44" aria-disabled="true"></div>
              <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
            <div class="review list">
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
            <div class="center">
              <div class="category">Захисні плівки</div>
              <a href="https://wrap.shop/plivky/zahysni-plivky/antygravijna-glyanceva-plivka-kybertane-ppf-ultra-gloss" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,53 м" class="name">Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,53 м</a>
            </div>
            <div class="bottom flex-center">
              <div class="price">2940.00 ₴<span class="price-unit-xvr"></span></div>
              <button class="button colord remarketing_cart_button" onclick="cart.add('431', '0.100');" data-product_id="431"><i class="fas fa-chevron-right"></i>Купити</button>
            </div>
            <div class="params">
              <div class="item flex-column">Призначення <span class="label">антигравійна</span></div>
              <div class="item flex-column">Структура <span class="label">глянцева</span></div>
            </div>
          </div>



          <div class="category-products-item product-default product-layout product-grid" id="categoryProductsItem392">
            <div class="top flex-justify">
              <div class="sku">Код: 10958</div>
              <div class="review grid">
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <div class="rating-result" style="width: 0%">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
              </div>
              <div class="wishlist">
                <button type="button" class="button far fa-heart" title="В закладки" onclick="wishlist.add('392');"></button>
              </div>
            </div>
            <div class="sale statuses"></div>
            <div class="image swiper swiper-initialized swiper-horizontal swiper-android swiper-backface-hidden">
              <i class="far fa-search-plus colord" data-src="/image/catalog/demo/syncms/3fd70ecc-20c5-11ee-0a80-08c7001471c3_0.png" data-fancybox="gallery392" data-caption="Плівка глянцева Kybertane Enchanted Emerald KYBC0613"></i>
              <a href="https://wrap.shop/plivky/kolorovi-plivky/glyanceva-plivka-kybertane-enchanted-emerald-kybc0613" title="Плівка глянцева Kybertane Enchanted Emerald KYBC0613" class="swiper-wrapper" id="swiper-wrapper-16785dd9345689d7" aria-live="polite">
                <div class="swiper-slide item flex-center swiper-slide-active" role="group" aria-label="1 / 4" style="display: flex; width: 241px;">
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/3fd70ecc-20c5-11ee-0a80-08c7001471c3_0-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/3fd70ecc-20c5-11ee-0a80-08c7001471c3_0-482x482.webp 2x" alt="Плівка глянцева Kybertane Enchanted Emerald KYBC0613" title="Плівка глянцева Kybertane Enchanted Emerald KYBC0613" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center swiper-slide-next" role="group" aria-label="2 / 4" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/3fd70ecc-20c5-11ee-0a80-08c7001471c3_1.png" data-fancybox="gallery392" data-caption="Плівка глянцева Kybertane Enchanted Emerald KYBC0613"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/3fd70ecc-20c5-11ee-0a80-08c7001471c3_1-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/3fd70ecc-20c5-11ee-0a80-08c7001471c3_1-482x482.webp 2x" alt="Плівка глянцева Kybertane Enchanted Emerald KYBC0613" title="Плівка глянцева Kybertane Enchanted Emerald KYBC0613" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="3 / 4" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/3fd70ecc-20c5-11ee-0a80-08c7001471c3_2.png" data-fancybox="gallery392" data-caption="Плівка глянцева Kybertane Enchanted Emerald KYBC0613"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/3fd70ecc-20c5-11ee-0a80-08c7001471c3_2-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/3fd70ecc-20c5-11ee-0a80-08c7001471c3_2-482x482.webp 2x" alt="Плівка глянцева Kybertane Enchanted Emerald KYBC0613" title="Плівка глянцева Kybertane Enchanted Emerald KYBC0613" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="4 / 4" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/3fd70ecc-20c5-11ee-0a80-08c7001471c3_3.png" data-fancybox="gallery392" data-caption="Плівка глянцева Kybertane Enchanted Emerald KYBC0613"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/3fd70ecc-20c5-11ee-0a80-08c7001471c3_3-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/3fd70ecc-20c5-11ee-0a80-08c7001471c3_3-482x482.webp 2x" alt="Плівка глянцева Kybertane Enchanted Emerald KYBC0613" title="Плівка глянцева Kybertane Enchanted Emerald KYBC0613" width="200" height="200">
                </div>
              </a>
              <div class="swiper-button-next button" tabindex="0" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-16785dd9345689d7" aria-disabled="false"></div>
              <div class="swiper-button-prev button swiper-button-disabled" tabindex="-1" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-16785dd9345689d7" aria-disabled="true"></div>
              <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
            <div class="review list">
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <div class="rating-result" style="width: 0%">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
              </div>
            </div>
            <div class="center">
              <div class="category">Кольорові плівки</div>
              <a href="https://wrap.shop/plivky/kolorovi-plivky/glyanceva-plivka-kybertane-enchanted-emerald-kybc0613" title="Плівка глянцева Kybertane Enchanted Emerald KYBC0613" class="name">Плівка глянцева Kybertane Enchanted Emerald KYBC0613</a>
            </div>
            <div class="bottom flex-center">
              <div class="price">2100.00 ₴<span class="price-unit-xvr"></span></div>
              <button class="button colord remarketing_cart_button" onclick="cart.add('392', '0.100');" data-product_id="392"><i class="fas fa-chevron-right"></i>Купити</button>
            </div>
            <div class="params">
              <div class="item flex-column">Призначення <span class="label">декоративна</span></div>
              <div class="item flex-column">Структура <span class="label">глянцева</span></div>
            </div>
          </div>



          <div class="category-products-item product-default product-layout product-grid" id="categoryProductsItem407">
            <div class="top flex-justify">
              <div class="sku">Код: 10940</div>
              <div class="review grid">
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <div class="rating-result" style="width: 0%">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
              </div>
              <div class="wishlist">
                <button type="button" class="button far fa-heart" title="В закладки" onclick="wishlist.add('407');"></button>
              </div>
            </div>
            <div class="sale statuses"></div>
            <div class="image swiper swiper-initialized swiper-horizontal swiper-android swiper-backface-hidden">
              <i class="far fa-search-plus colord" data-src="/image/catalog/demo/syncms/7d062437-14e6-11ee-0a80-0d9400101117_0.png" data-fancybox="gallery407" data-caption="Плівка глянцева Kybertane Soulfire Plum KYBC0831"></i>
              <a href="https://wrap.shop/plivky/kolorovi-plivky/glyanceva-plivka-kybertane-soulfire-plum-kybc0831" title="Плівка глянцева Kybertane Soulfire Plum KYBC0831" class="swiper-wrapper" id="swiper-wrapper-aaefb34c1050f1211" aria-live="polite">
                <div class="swiper-slide item flex-center swiper-slide-active" role="group" aria-label="1 / 2" style="display: flex; width: 241px;">
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/7d062437-14e6-11ee-0a80-0d9400101117_0-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/7d062437-14e6-11ee-0a80-0d9400101117_0-482x482.webp 2x" alt="Плівка глянцева Kybertane Soulfire Plum KYBC0831" title="Плівка глянцева Kybertane Soulfire Plum KYBC0831" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center swiper-slide-next" role="group" aria-label="2 / 2" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/7d062437-14e6-11ee-0a80-0d9400101117_1.png" data-fancybox="gallery407" data-caption="Плівка глянцева Kybertane Soulfire Plum KYBC0831"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/7d062437-14e6-11ee-0a80-0d9400101117_1-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/7d062437-14e6-11ee-0a80-0d9400101117_1-482x482.webp 2x" alt="Плівка глянцева Kybertane Soulfire Plum KYBC0831" title="Плівка глянцева Kybertane Soulfire Plum KYBC0831" width="200" height="200">
                </div>
              </a>
              <div class="swiper-button-next button" tabindex="0" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-aaefb34c1050f1211" aria-disabled="false"></div>
              <div class="swiper-button-prev button swiper-button-disabled" tabindex="-1" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-aaefb34c1050f1211" aria-disabled="true"></div>
              <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
            <div class="review list">
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <div class="rating-result" style="width: 0%">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
              </div>
            </div>
            <div class="center">
              <div class="category">Кольорові плівки</div>
              <a href="https://wrap.shop/plivky/kolorovi-plivky/glyanceva-plivka-kybertane-soulfire-plum-kybc0831" title="Плівка глянцева Kybertane Soulfire Plum KYBC0831" class="name">Плівка глянцева Kybertane Soulfire Plum KYBC0831</a>
            </div>
            <div class="bottom flex-center">
              <div class="price">2100.00 ₴<span class="price-unit-xvr"></span></div>
              <button class="button colord remarketing_cart_button" onclick="cart.add('407', '0.100');" data-product_id="407"><i class="fas fa-chevron-right"></i>Купити</button>
            </div>
            <div class="params">
              <div class="item flex-column">Призначення <span class="label">декоративна</span></div>
              <div class="item flex-column">Структура <span class="label">глянцева</span></div>
            </div>
          </div>



          <div class="category-products-item product-default product-layout product-grid" id="categoryProductsItem432">
            <div class="top flex-justify">
              <div class="sku">Код: 11059</div>
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
                <button type="button" class="button far fa-heart" title="В закладки" onclick="wishlist.add('432');"></button>
              </div>
            </div>
            <div class="sale statuses"><div class="category-status category-status-1 status-inline text rectangle " style=" color:#ffffff; background-color:#d04b4b;">







                Хіт продажів




              </div></div>
            <div class="image swiper swiper-initialized swiper-horizontal swiper-android swiper-backface-hidden">
              <i class="far fa-search-plus colord" data-src="/image/catalog/demo/syncms/e359162a-0538-11ea-0a80-01b70006656a_0.png" data-fancybox="gallery432" data-caption="Плівка антигравійна сатинова Kybertane PPF Deep Satin 1,53 м"></i>
              <a href="https://wrap.shop/plivky/zahysni-plivky/antygravijna-satynova-plivka-kybertane-ppf-deep-satin" title="Плівка антигравійна сатинова Kybertane PPF Deep Satin 1,53 м" class="swiper-wrapper" id="swiper-wrapper-8684beecb1b70a96" aria-live="polite">
                <div class="swiper-slide item flex-center swiper-slide-active" role="group" aria-label="1 / 8" style="display: flex; width: 241px;">
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/e359162a-0538-11ea-0a80-01b70006656a_0-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/e359162a-0538-11ea-0a80-01b70006656a_0-482x482.webp 2x" alt="Плівка антигравійна сатинова Kybertane PPF Deep Satin 1,53 м" title="Плівка антигравійна сатинова Kybertane PPF Deep Satin 1,53 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center swiper-slide-next" role="group" aria-label="2 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/e359162a-0538-11ea-0a80-01b70006656a_1.png" data-fancybox="gallery432" data-caption="Плівка антигравійна сатинова Kybertane PPF Deep Satin 1,53 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/e359162a-0538-11ea-0a80-01b70006656a_1-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/e359162a-0538-11ea-0a80-01b70006656a_1-482x482.webp 2x" alt="Плівка антигравійна сатинова Kybertane PPF Deep Satin 1,53 м" title="Плівка антигравійна сатинова Kybertane PPF Deep Satin 1,53 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="3 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/e359162a-0538-11ea-0a80-01b70006656a_2.png" data-fancybox="gallery432" data-caption="Плівка антигравійна сатинова Kybertane PPF Deep Satin 1,53 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/e359162a-0538-11ea-0a80-01b70006656a_2-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/e359162a-0538-11ea-0a80-01b70006656a_2-482x482.webp 2x" alt="Плівка антигравійна сатинова Kybertane PPF Deep Satin 1,53 м" title="Плівка антигравійна сатинова Kybertane PPF Deep Satin 1,53 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="4 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/e359162a-0538-11ea-0a80-01b70006656a_3.png" data-fancybox="gallery432" data-caption="Плівка антигравійна сатинова Kybertane PPF Deep Satin 1,53 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/e359162a-0538-11ea-0a80-01b70006656a_3-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/e359162a-0538-11ea-0a80-01b70006656a_3-482x482.webp 2x" alt="Плівка антигравійна сатинова Kybertane PPF Deep Satin 1,53 м" title="Плівка антигравійна сатинова Kybertane PPF Deep Satin 1,53 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="5 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/e359162a-0538-11ea-0a80-01b70006656a_4.png" data-fancybox="gallery432" data-caption="Плівка антигравійна сатинова Kybertane PPF Deep Satin 1,53 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/e359162a-0538-11ea-0a80-01b70006656a_4-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/e359162a-0538-11ea-0a80-01b70006656a_4-482x482.webp 2x" alt="Плівка антигравійна сатинова Kybertane PPF Deep Satin 1,53 м" title="Плівка антигравійна сатинова Kybertane PPF Deep Satin 1,53 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="6 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/e359162a-0538-11ea-0a80-01b70006656a_5.png" data-fancybox="gallery432" data-caption="Плівка антигравійна сатинова Kybertane PPF Deep Satin 1,53 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/e359162a-0538-11ea-0a80-01b70006656a_5-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/e359162a-0538-11ea-0a80-01b70006656a_5-482x482.webp 2x" alt="Плівка антигравійна сатинова Kybertane PPF Deep Satin 1,53 м" title="Плівка антигравійна сатинова Kybertane PPF Deep Satin 1,53 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="7 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/e359162a-0538-11ea-0a80-01b70006656a_6.png" data-fancybox="gallery432" data-caption="Плівка антигравійна сатинова Kybertane PPF Deep Satin 1,53 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/e359162a-0538-11ea-0a80-01b70006656a_6-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/e359162a-0538-11ea-0a80-01b70006656a_6-482x482.webp 2x" alt="Плівка антигравійна сатинова Kybertane PPF Deep Satin 1,53 м" title="Плівка антигравійна сатинова Kybertane PPF Deep Satin 1,53 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="8 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/e359162a-0538-11ea-0a80-01b70006656a_7.png" data-fancybox="gallery432" data-caption="Плівка антигравійна сатинова Kybertane PPF Deep Satin 1,53 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/e359162a-0538-11ea-0a80-01b70006656a_7-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/e359162a-0538-11ea-0a80-01b70006656a_7-482x482.webp 2x" alt="Плівка антигравійна сатинова Kybertane PPF Deep Satin 1,53 м" title="Плівка антигравійна сатинова Kybertane PPF Deep Satin 1,53 м" width="200" height="200">
                </div>
              </a>
              <div class="swiper-button-next button" tabindex="0" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-8684beecb1b70a96" aria-disabled="false"></div>
              <div class="swiper-button-prev button swiper-button-disabled" tabindex="-1" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-8684beecb1b70a96" aria-disabled="true"></div>
              <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
            <div class="review list">
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
            <div class="center">
              <div class="category">Захисні плівки</div>
              <a href="https://wrap.shop/plivky/zahysni-plivky/antygravijna-satynova-plivka-kybertane-ppf-deep-satin" title="Плівка антигравійна сатинова Kybertane PPF Deep Satin 1,53 м" class="name">Плівка антигравійна сатинова Kybertane PPF Deep Satin 1,53 м</a>
            </div>
            <div class="bottom flex-center">
              <div class="price">3360.00 ₴<span class="price-unit-xvr"></span></div>
              <button class="button colord remarketing_cart_button" onclick="cart.add('432', '0.100');" data-product_id="432"><i class="fas fa-chevron-right"></i>Купити</button>
            </div>
            <div class="params">
              <div class="item flex-column">Призначення <span class="label">антигравійна</span></div>
              <div class="item flex-column">Структура <span class="label">сатинова</span></div>
            </div>
          </div>

          <a href="https://wrap.shop/plivky/kolorovi-plivky/?ocf=F1S0V44" title="kybertane color" class="category-products-item product-default product-layout banner product-grid">
            <img class="vertical" data-src="/image/catalog/category/webp/banners catalog/2-kyb-col-vert.webp" alt="kybertane color" title="kybertane color">
            <img class="gorizont" data-src="/image/catalog/category/webp/banners catalog/2-kyb-gor.webp" alt="kybertane color" title="kybertane color">
          </a>


          <div class="category-products-item product-default product-layout product-grid" id="categoryProductsItem399">
            <div class="top flex-justify">
              <div class="sku">Код: 10978</div>
              <div class="review grid">
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <div class="rating-result" style="width: 0%">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
              </div>
              <div class="wishlist">
                <button type="button" class="button far fa-heart" title="В закладки" onclick="wishlist.add('399');"></button>
              </div>
            </div>
            <div class="sale statuses"><div class="category-status category-status-1 status-inline text rectangle " style=" color:#ffffff; background-color:#d04b4b;">







                Хіт продажів




              </div></div>
            <div class="image swiper swiper-initialized swiper-horizontal swiper-android swiper-backface-hidden">
              <i class="far fa-search-plus colord" data-src="/image/catalog/demo/syncms/60ddd3c5-20c1-11ee-0a80-045a0012ed53_0.png" data-fancybox="gallery399" data-caption="Плівка глянцева Kybertane Marina Twilight KYBC1709"></i>
              <a href="https://wrap.shop/plivky/kolorovi-plivky/glyanceva-plivka-kybertane-marina-twilight-kybc1709" title="Плівка глянцева Kybertane Marina Twilight KYBC1709" class="swiper-wrapper" id="swiper-wrapper-9124ab2cf3301624" aria-live="polite">
                <div class="swiper-slide item flex-center swiper-slide-active" role="group" aria-label="1 / 2" style="display: flex; width: 241px;">
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/60ddd3c5-20c1-11ee-0a80-045a0012ed53_0-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/60ddd3c5-20c1-11ee-0a80-045a0012ed53_0-482x482.webp 2x" alt="Плівка глянцева Kybertane Marina Twilight KYBC1709" title="Плівка глянцева Kybertane Marina Twilight KYBC1709" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center swiper-slide-next" role="group" aria-label="2 / 2" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/60ddd3c5-20c1-11ee-0a80-045a0012ed53_1.png" data-fancybox="gallery399" data-caption="Плівка глянцева Kybertane Marina Twilight KYBC1709"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/60ddd3c5-20c1-11ee-0a80-045a0012ed53_1-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/60ddd3c5-20c1-11ee-0a80-045a0012ed53_1-482x482.webp 2x" alt="Плівка глянцева Kybertane Marina Twilight KYBC1709" title="Плівка глянцева Kybertane Marina Twilight KYBC1709" width="200" height="200">
                </div>
              </a>
              <div class="swiper-button-next button" tabindex="0" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-9124ab2cf3301624" aria-disabled="false"></div>
              <div class="swiper-button-prev button swiper-button-disabled" tabindex="-1" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-9124ab2cf3301624" aria-disabled="true"></div>
              <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
            <div class="review list">
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <div class="rating-result" style="width: 0%">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
              </div>
            </div>
            <div class="center">
              <div class="category">Кольорові плівки</div>
              <a href="https://wrap.shop/plivky/kolorovi-plivky/glyanceva-plivka-kybertane-marina-twilight-kybc1709" title="Плівка глянцева Kybertane Marina Twilight KYBC1709" class="name">Плівка глянцева Kybertane Marina Twilight KYBC1709</a>
            </div>
            <div class="bottom flex-center">
              <div class="price">2310.00 ₴<span class="price-unit-xvr"></span></div>
              <button class="button colord remarketing_cart_button" onclick="cart.add('399', '0.100');" data-product_id="399"><i class="fas fa-chevron-right"></i>Купити</button>
            </div>
            <div class="params">
              <div class="item flex-column">Призначення <span class="label">декоративна</span></div>
              <div class="item flex-column">Структура <span class="label">глянцева</span></div>
            </div>
          </div>



          <div class="category-products-item product-default product-layout product-grid" id="categoryProductsItem519">
            <div class="top flex-justify">
              <div class="sku">Код: 11020</div>
              <div class="review grid">
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <div class="rating-result" style="width: 0%">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
              </div>
              <div class="wishlist">
                <button type="button" class="button far fa-heart" title="В закладки" onclick="wishlist.add('519');"></button>
              </div>
            </div>
            <div class="sale statuses"></div>
            <div class="image swiper swiper-initialized swiper-horizontal swiper-android swiper-backface-hidden">
              <i class="far fa-search-plus colord" data-src="/image/catalog/demo/syncms/28f8e7de-96ed-11e8-9107-504800216358_0.png" data-fancybox="gallery519" data-caption="Плівка антигравійна глянцева Kybertane PPF Black Gloss"></i>
              <a href="https://wrap.shop/plivky/zahysni-plivky/antygravijna-glyanceva-plivka-kybertane-ppf-black-gloss" title="Плівка антигравійна глянцева Kybertane PPF Black Gloss" class="swiper-wrapper" id="swiper-wrapper-c2a2951676075ff4" aria-live="polite">
                <div class="swiper-slide item flex-center swiper-slide-active" role="group" aria-label="1 / 1" style="display: flex; width: 241px;">
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/28f8e7de-96ed-11e8-9107-504800216358_0-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/28f8e7de-96ed-11e8-9107-504800216358_0-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Black Gloss" title="Плівка антигравійна глянцева Kybertane PPF Black Gloss" width="200" height="200">
                </div>
              </a>
              <div class="swiper-button-next button swiper-button-disabled swiper-button-lock" tabindex="-1" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-c2a2951676075ff4" aria-disabled="true"></div>
              <div class="swiper-button-prev button swiper-button-disabled swiper-button-lock" tabindex="-1" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-c2a2951676075ff4" aria-disabled="true"></div>
              <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
            <div class="review list">
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <div class="rating-result" style="width: 0%">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
              </div>
            </div>
            <div class="center">
              <div class="category">Захисні плівки</div>
              <a href="https://wrap.shop/plivky/zahysni-plivky/antygravijna-glyanceva-plivka-kybertane-ppf-black-gloss" title="Плівка антигравійна глянцева Kybertane PPF Black Gloss" class="name">Плівка антигравійна глянцева Kybertane PPF Black Gloss</a>
            </div>
            <div class="bottom flex-center">
              <div class="price">4116.00 ₴<span class="price-unit-xvr"></span></div>
              <button class="button colord remarketing_cart_button" onclick="cart.add('519', '0.100');" data-product_id="519"><i class="fas fa-chevron-right"></i>Купити</button>
            </div>
            <div class="params">
              <div class="item flex-column">Призначення <span class="label">антигравійна</span></div>
              <div class="item flex-column">Структура <span class="label">глянцева</span></div>
            </div>
          </div>



          <div class="category-products-item product-default product-layout product-grid" id="categoryProductsItem64">
            <div class="top flex-justify">
              <div class="sku">Код: 10456</div>
              <div class="review grid">
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <div class="rating-result" style="width: 0%">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
              </div>
              <div class="wishlist">
                <button type="button" class="button far fa-heart" title="В закладки" onclick="wishlist.add('64');"></button>
              </div>
            </div>
            <div class="sale statuses"></div>
            <div class="image swiper swiper-initialized swiper-horizontal swiper-android swiper-backface-hidden">
              <i class="far fa-search-plus colord" data-src="/image/catalog/demo/syncms/149e9c6c-f248-11e6-7a31-d0fd00006ead_0.png" data-fancybox="gallery64" data-caption="Плівка 3M 1080-CF201 Carbon Fiber Anthracite CFS201"></i>
              <a href="https://wrap.shop/plivky/kolorovi-plivky/plivka-3m-1080-cf201-carbon-fiber-anthracite-cfs201" title="Плівка 3M 1080-CF201 Carbon Fiber Anthracite CFS201" class="swiper-wrapper" id="swiper-wrapper-d3ab514322baea06" aria-live="polite">
                <div class="swiper-slide item flex-center swiper-slide-active" role="group" aria-label="1 / 1" style="display: flex; width: 241px;">
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/149e9c6c-f248-11e6-7a31-d0fd00006ead_0-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/149e9c6c-f248-11e6-7a31-d0fd00006ead_0-482x482.webp 2x" alt="Плівка 3M 1080-CF201 Carbon Fiber Anthracite CFS201" title="Плівка 3M 1080-CF201 Carbon Fiber Anthracite CFS201" width="200" height="200">
                </div>
              </a>
              <div class="swiper-button-next button swiper-button-disabled swiper-button-lock" tabindex="-1" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-d3ab514322baea06" aria-disabled="true"></div>
              <div class="swiper-button-prev button swiper-button-disabled swiper-button-lock" tabindex="-1" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-d3ab514322baea06" aria-disabled="true"></div>
              <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
            <div class="review list">
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <div class="rating-result" style="width: 0%">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
              </div>
            </div>
            <div class="center">
              <div class="category">Кольорові плівки</div>
              <a href="https://wrap.shop/plivky/kolorovi-plivky/plivka-3m-1080-cf201-carbon-fiber-anthracite-cfs201" title="Плівка 3M 1080-CF201 Carbon Fiber Anthracite CFS201" class="name">Плівка 3M 1080-CF201 Carbon Fiber Anthracite CFS201</a>
            </div>
            <div class="bottom flex-center">
              <div class="price">4452.00 ₴<span class="price-unit-xvr"></span></div>
              <button class="button colord remarketing_cart_button" onclick="ocdw_in_stock_alert_open({a:this,b:'64',c:'2'})" data-product_id="64"><i class="fas fa-bell"></i><span class="hidden-xs hidden-sm hidden-md"> Повідомити</span></button>
            </div>
            <div class="params">
              <div class="item flex-column">Призначення <span class="label">декоративна</span></div>
              <div class="item flex-column">Структура <span class="label">структурна</span></div>
            </div>
          </div>



          <div class="category-products-item product-default product-layout product-grid" id="categoryProductsItem779">
            <div class="top flex-justify">
              <div class="sku">Код: 10626</div>
              <div class="review grid">
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <div class="rating-result" style="width: 0%">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
              </div>
              <div class="wishlist">
                <button type="button" class="button far fa-heart" title="В закладки" onclick="wishlist.add('779');"></button>
              </div>
            </div>
            <div class="sale statuses"></div>
            <div class="image swiper swiper-initialized swiper-horizontal swiper-android swiper-backface-hidden">
              <i class="far fa-search-plus colord" data-src="/image/catalog/demo/syncms/146f6ecc-778b-11e7-7a69-9711000717b5_0.png" data-fancybox="gallery779" data-caption="Плівка антивандальна 3М Scotchshield Automotive Security CLEAR SAS"></i>
              <a href="https://wrap.shop/plivky/plivka-dlya-avtomobilnyh-vikon/antyvandalna-3m/antivandalna-plvka-3m-scotchshield-auto-security-clear-sas" title="Плівка антивандальна 3М Scotchshield Automotive Security CLEAR SAS" class="swiper-wrapper" id="swiper-wrapper-8128726cf810be291" aria-live="polite">
                <div class="swiper-slide item flex-center swiper-slide-active" role="group" aria-label="1 / 1" style="display: flex; width: 241px;">
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/146f6ecc-778b-11e7-7a69-9711000717b5_0-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/146f6ecc-778b-11e7-7a69-9711000717b5_0-482x482.webp 2x" alt="Плівка антивандальна 3М Scotchshield Automotive Security CLEAR SAS" title="Плівка антивандальна 3М Scotchshield Automotive Security CLEAR SAS" width="200" height="200">
                </div>
              </a>
              <div class="swiper-button-next button swiper-button-disabled swiper-button-lock" tabindex="-1" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-8128726cf810be291" aria-disabled="true"></div>
              <div class="swiper-button-prev button swiper-button-disabled swiper-button-lock" tabindex="-1" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-8128726cf810be291" aria-disabled="true"></div>
              <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
            <div class="review list">
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <div class="rating-result" style="width: 0%">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
              </div>
            </div>
            <div class="center">
              <div class="category">Антивандальна 3M</div>
              <a href="https://wrap.shop/plivky/plivka-dlya-avtomobilnyh-vikon/antyvandalna-3m/antivandalna-plvka-3m-scotchshield-auto-security-clear-sas" title="Плівка антивандальна 3М Scotchshield Automotive Security CLEAR SAS" class="name">Плівка антивандальна 3М Scotchshield Automotive Security CLEAR SAS</a>
            </div>
            <div class="bottom flex-center">
              <div class="price">1008.00 ₴<span class="price-unit-xvr"></span></div>
              <button class="button colord remarketing_cart_button" onclick="cart.add('779', '1');" data-product_id="779"><i class="fas fa-chevron-right"></i>Купити</button>
            </div>
            <div class="params">
              <div class="item flex-column">Призначення <span class="label">антивандальна</span></div>
              <div class="item flex-column">Тип <span class="label">атермальна</span></div>
            </div>
          </div>



          <div class="category-products-item product-default product-layout product-grid" id="categoryProductsItem783">
            <div class="top flex-justify">
              <div class="sku">Код: 10622</div>
              <div class="review grid">
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <div class="rating-result" style="width: 0%">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
              </div>
              <div class="wishlist">
                <button type="button" class="button far fa-heart" title="В закладки" onclick="wishlist.add('783');"></button>
              </div>
            </div>
            <div class="sale statuses"></div>
            <div class="image swiper swiper-initialized swiper-horizontal swiper-android swiper-backface-hidden">
              <i class="far fa-search-plus colord" data-src="/image/catalog/demo/syncms/5645c403-d14b-11e8-9109-f8fc0009eb48_0.png" data-fancybox="gallery783" data-caption="Плівка антивандальна плівка 3М Scotchshield Automotive Security 20%"></i>
              <a href="https://wrap.shop/plivky/plivka-dlya-avtomobilnyh-vikon/antyvandalna-3m/antivandalna-plvka-3m-scotchshield-auto-security-20" title="Плівка антивандальна плівка 3М Scotchshield Automotive Security 20%" class="swiper-wrapper" id="swiper-wrapper-3eaf10c3dbb42a321" aria-live="polite">
                <div class="swiper-slide item flex-center swiper-slide-active" role="group" aria-label="1 / 1" style="display: flex; width: 241px;">
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/5645c403-d14b-11e8-9109-f8fc0009eb48_0-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/5645c403-d14b-11e8-9109-f8fc0009eb48_0-482x482.webp 2x" alt="Плівка антивандальна плівка 3М Scotchshield Automotive Security 20%" title="Плівка антивандальна плівка 3М Scotchshield Automotive Security 20%" width="200" height="200">
                </div>
              </a>
              <div class="swiper-button-next button swiper-button-disabled swiper-button-lock" tabindex="-1" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-3eaf10c3dbb42a321" aria-disabled="true"></div>
              <div class="swiper-button-prev button swiper-button-disabled swiper-button-lock" tabindex="-1" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-3eaf10c3dbb42a321" aria-disabled="true"></div>
              <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
            <div class="review list">
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <div class="rating-result" style="width: 0%">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
              </div>
            </div>
            <div class="center">
              <div class="category">Антивандальна 3M</div>
              <a href="https://wrap.shop/plivky/plivka-dlya-avtomobilnyh-vikon/antyvandalna-3m/antivandalna-plvka-3m-scotchshield-auto-security-20" title="Плівка антивандальна плівка 3М Scotchshield Automotive Security 20%" class="name">Плівка антивандальна плівка 3М Scotchshield Automotive Security 20%</a>
            </div>
            <div class="bottom flex-center">
              <div class="price">1344.00 ₴<span class="price-unit-xvr"></span></div>
              <button class="button colord remarketing_cart_button" onclick="cart.add('783', '1');" data-product_id="783"><i class="fas fa-chevron-right"></i>Купити</button>
            </div>
            <div class="params">
              <div class="item flex-column">Призначення <span class="label">антивандальна</span></div>
              <div class="item flex-column">Тип <span class="label">атермальна</span></div>
            </div>
          </div>

          <a href="https://wrap.shop/instrumenty-rozhidnyky/?ocf=F1S0V80" title="Yellotools" class="category-products-item product-default product-layout banner product-grid">
            <img class="vertical" data-src="/image/catalog/category/webp/banners catalog/4-yello-vert.webp" alt="Yellotools" title="Yellotools">
            <img class="gorizont" data-src="/image/catalog/category/webp/banners catalog/4-yello-gor.webp" alt="Yellotools" title="Yellotools">
          </a>


          <div class="category-products-item product-default product-layout product-grid" id="categoryProductsItem339">
            <div class="top flex-justify">
              <div class="sku">Код: 10845</div>
              <div class="review grid">
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <div class="rating-result" style="width: 0%">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
              </div>
              <div class="wishlist">
                <button type="button" class="button far fa-heart" title="В закладки" onclick="wishlist.add('339');"></button>
              </div>
            </div>
            <div class="sale statuses"></div>
            <div class="image swiper swiper-initialized swiper-horizontal swiper-android swiper-backface-hidden">
              <i class="far fa-search-plus colord" data-src="/image/catalog/demo/syncms/e6d9000d-8eca-11ea-0a80-016b001aa6e9_0.jpg" data-fancybox="gallery339" data-caption="Плівка антигравійна глянцева Hexis BodyfenceX self healing Gloss 1,52 м"></i>
              <a href="https://wrap.shop/plivky/zahysni-plivky/plivka-antygravijna-glyanceva-hexis-bodyfencex-self-healing-gloss-1-52-m" title="Плівка антигравійна глянцева Hexis BodyfenceX self healing Gloss 1,52 м" class="swiper-wrapper" id="swiper-wrapper-e10b2596b03e6f9110" aria-live="polite">
                <div class="swiper-slide item flex-center swiper-slide-active" role="group" aria-label="1 / 1" style="display: flex; width: 241px;">
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/e6d9000d-8eca-11ea-0a80-016b001aa6e9_0-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/e6d9000d-8eca-11ea-0a80-016b001aa6e9_0-482x482.webp 2x" alt="Плівка антигравійна глянцева Hexis BodyfenceX self healing Gloss 1,52 м" title="Плівка антигравійна глянцева Hexis BodyfenceX self healing Gloss 1,52 м" width="200" height="200">
                </div>
              </a>
              <div class="swiper-button-next button swiper-button-disabled swiper-button-lock" tabindex="-1" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-e10b2596b03e6f9110" aria-disabled="true"></div>
              <div class="swiper-button-prev button swiper-button-disabled swiper-button-lock" tabindex="-1" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-e10b2596b03e6f9110" aria-disabled="true"></div>
              <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
            <div class="review list">
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <div class="rating-result" style="width: 0%">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
              </div>
            </div>
            <div class="center">
              <div class="category">Захисні плівки</div>
              <a href="https://wrap.shop/plivky/zahysni-plivky/plivka-antygravijna-glyanceva-hexis-bodyfencex-self-healing-gloss-1-52-m" title="Плівка антигравійна глянцева Hexis BodyfenceX self healing Gloss 1,52 м" class="name">Плівка антигравійна глянцева Hexis BodyfenceX self healing Gloss 1,52 м</a>
            </div>
            <div class="bottom flex-center">
              <div class="price">3402.00 ₴<span class="price-unit-xvr"></span></div>
              <button class="button colord remarketing_cart_button" onclick="cart.add('339', '1');" data-product_id="339"><i class="fas fa-chevron-right"></i>Купити</button>
            </div>
            <div class="params">
              <div class="item flex-column">Призначення <span class="label">антигравійна</span></div>
              <div class="item flex-column">Структура <span class="label">глянцева</span></div>
            </div>
          </div>



          <div class="category-products-item product-default product-layout product-grid" id="categoryProductsItem430">
            <div class="top flex-justify">
              <div class="sku">Код: 11058</div>
              <div class="review grid">
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <div class="rating-result" style="width: 0%">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
              </div>
              <div class="wishlist">
                <button type="button" class="button far fa-heart" title="В закладки" onclick="wishlist.add('430');"></button>
              </div>
            </div>
            <div class="sale statuses"></div>
            <div class="image swiper swiper-initialized swiper-horizontal swiper-android swiper-backface-hidden">
              <i class="far fa-search-plus colord" data-src="/image/catalog/demo/syncms/97317d03-b43c-11e9-9ff4-34e800043814_0.png" data-fancybox="gallery430" data-caption="Плівка антигравійна глянцева Kybertane PPF Tint Dark 30%"></i>
              <a href="https://wrap.shop/plivky/zahysni-plivky/plivka-antygravijna-glyanceva-kybertane-ppf-tint-dark-30" title="Плівка антигравійна глянцева Kybertane PPF Tint Dark 30%" class="swiper-wrapper" id="swiper-wrapper-8927318e973f42c3" aria-live="polite">
                <div class="swiper-slide item flex-center swiper-slide-active" role="group" aria-label="1 / 1" style="display: flex; width: 241px;">
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/97317d03-b43c-11e9-9ff4-34e800043814_0-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/97317d03-b43c-11e9-9ff4-34e800043814_0-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Tint Dark 30%" title="Плівка антигравійна глянцева Kybertane PPF Tint Dark 30%" width="200" height="200">
                </div>
              </a>
              <div class="swiper-button-next button swiper-button-disabled swiper-button-lock" tabindex="-1" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-8927318e973f42c3" aria-disabled="true"></div>
              <div class="swiper-button-prev button swiper-button-disabled swiper-button-lock" tabindex="-1" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-8927318e973f42c3" aria-disabled="true"></div>
              <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
            <div class="review list">
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <div class="rating-result" style="width: 0%">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
              </div>
            </div>
            <div class="center">
              <div class="category">Захисні плівки</div>
              <a href="https://wrap.shop/plivky/zahysni-plivky/plivka-antygravijna-glyanceva-kybertane-ppf-tint-dark-30" title="Плівка антигравійна глянцева Kybertane PPF Tint Dark 30%" class="name">Плівка антигравійна глянцева Kybertane PPF Tint Dark 30%</a>
            </div>
            <div class="bottom flex-center">
              <div class="price">1470.00 ₴<span class="price-unit-xvr"></span></div>
              <button class="button colord remarketing_cart_button" onclick="cart.add('430', '0.100');" data-product_id="430"><i class="fas fa-chevron-right"></i>Купити</button>
            </div>
            <div class="params">
              <div class="item flex-column">Призначення <span class="label">антигравійна</span></div>
              <div class="item flex-column">Структура <span class="label">глянцева</span></div>
            </div>
          </div>



          <div class="category-products-item product-default product-layout product-grid" id="categoryProductsItem429">
            <div class="top flex-justify">
              <div class="sku">Код: 11057</div>
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
                <button type="button" class="button far fa-heart" title="В закладки" onclick="wishlist.add('429');"></button>
              </div>
            </div>
            <div class="sale statuses"></div>
            <div class="image swiper swiper-initialized swiper-horizontal swiper-android swiper-backface-hidden">
              <i class="far fa-search-plus colord" data-src="/image/catalog/demo/syncms/71c0cd8f-b43c-11e9-912f-f3d40003df3a_0.png" data-fancybox="gallery429" data-caption="Плівка антигравійна глянцева Kybertane PPF Tint Grey 50%"></i>
              <a href="https://wrap.shop/plivky/zahysni-plivky/plivka-antygravijna-glyanceva-kybertane-ppf-tint-grey-50" title="Плівка антигравійна глянцева Kybertane PPF Tint Grey 50%" class="swiper-wrapper" id="swiper-wrapper-ee6eedfbbe173b52" aria-live="polite">
                <div class="swiper-slide item flex-center swiper-slide-active" role="group" aria-label="1 / 1" style="display: flex; width: 241px;">
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/71c0cd8f-b43c-11e9-912f-f3d40003df3a_0-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/71c0cd8f-b43c-11e9-912f-f3d40003df3a_0-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Tint Grey 50%" title="Плівка антигравійна глянцева Kybertane PPF Tint Grey 50%" width="200" height="200">
                </div>
              </a>
              <div class="swiper-button-next button swiper-button-disabled swiper-button-lock" tabindex="-1" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-ee6eedfbbe173b52" aria-disabled="true"></div>
              <div class="swiper-button-prev button swiper-button-disabled swiper-button-lock" tabindex="-1" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-ee6eedfbbe173b52" aria-disabled="true"></div>
              <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
            <div class="review list">
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
            <div class="center">
              <div class="category">Захисні плівки</div>
              <a href="https://wrap.shop/plivky/zahysni-plivky/plivka-antygravijna-glyanceva-kybertane-ppf-tint-grey-50" title="Плівка антигравійна глянцева Kybertane PPF Tint Grey 50%" class="name">Плівка антигравійна глянцева Kybertane PPF Tint Grey 50%</a>
            </div>
            <div class="bottom flex-center">
              <div class="price">1470.00 ₴<span class="price-unit-xvr"></span></div>
              <button class="button colord remarketing_cart_button" onclick="cart.add('429', '0.100');" data-product_id="429"><i class="fas fa-chevron-right"></i>Купити</button>
            </div>
            <div class="params">
              <div class="item flex-column">Призначення <span class="label">антигравійна</span></div>
              <div class="item flex-column">Структура <span class="label">глянцева</span></div>
            </div>
          </div>



          <div class="category-products-item product-default product-layout product-grid" id="categoryProductsItem544">
            <div class="top flex-justify">
              <div class="sku">Код: 13272</div>
              <div class="review grid">
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <div class="rating-result" style="width: 0%">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
              </div>
              <div class="wishlist">
                <button type="button" class="button far fa-heart" title="В закладки" onclick="wishlist.add('544');"></button>
              </div>
            </div>
            <div class="sale statuses"></div>
            <div class="image swiper swiper-initialized swiper-horizontal swiper-android swiper-backface-hidden">
              <i class="far fa-search-plus colord" data-src="/image/catalog/demo/syncms/4bf443ac-87b5-11ee-0a80-13d70040a2dd_0.png" data-fancybox="gallery544" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,3 м"></i>
              <a href="https://wrap.shop/plivky/zahysni-plivky/antigravyna-glyanceva-plvka-kybertane-ppf-ultra-gloss-03m" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,3 м" class="swiper-wrapper" id="swiper-wrapper-1a8d659ab1f2fd87" aria-live="polite">
                <div class="swiper-slide item flex-center swiper-slide-active" role="group" aria-label="1 / 8" style="display: flex; width: 241px;">
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/4bf443ac-87b5-11ee-0a80-13d70040a2dd_0-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/4bf443ac-87b5-11ee-0a80-13d70040a2dd_0-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,3 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,3 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center swiper-slide-next" role="group" aria-label="2 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/4bf443ac-87b5-11ee-0a80-13d70040a2dd_1.png" data-fancybox="gallery544" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,3 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/4bf443ac-87b5-11ee-0a80-13d70040a2dd_1-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/4bf443ac-87b5-11ee-0a80-13d70040a2dd_1-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,3 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,3 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="3 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/4bf443ac-87b5-11ee-0a80-13d70040a2dd_2.png" data-fancybox="gallery544" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,3 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/4bf443ac-87b5-11ee-0a80-13d70040a2dd_2-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/4bf443ac-87b5-11ee-0a80-13d70040a2dd_2-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,3 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,3 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="4 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/4bf443ac-87b5-11ee-0a80-13d70040a2dd_3.png" data-fancybox="gallery544" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,3 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/4bf443ac-87b5-11ee-0a80-13d70040a2dd_3-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/4bf443ac-87b5-11ee-0a80-13d70040a2dd_3-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,3 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,3 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="5 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/4bf443ac-87b5-11ee-0a80-13d70040a2dd_4.png" data-fancybox="gallery544" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,3 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/4bf443ac-87b5-11ee-0a80-13d70040a2dd_4-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/4bf443ac-87b5-11ee-0a80-13d70040a2dd_4-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,3 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,3 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="6 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/4bf443ac-87b5-11ee-0a80-13d70040a2dd_5.png" data-fancybox="gallery544" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,3 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/4bf443ac-87b5-11ee-0a80-13d70040a2dd_5-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/4bf443ac-87b5-11ee-0a80-13d70040a2dd_5-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,3 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,3 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="7 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/4bf443ac-87b5-11ee-0a80-13d70040a2dd_6.png" data-fancybox="gallery544" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,3 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/4bf443ac-87b5-11ee-0a80-13d70040a2dd_6-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/4bf443ac-87b5-11ee-0a80-13d70040a2dd_6-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,3 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,3 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="8 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/4bf443ac-87b5-11ee-0a80-13d70040a2dd_7.png" data-fancybox="gallery544" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,3 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/4bf443ac-87b5-11ee-0a80-13d70040a2dd_7-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/4bf443ac-87b5-11ee-0a80-13d70040a2dd_7-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,3 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,3 м" width="200" height="200">
                </div>
              </a>
              <div class="swiper-button-next button" tabindex="0" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-1a8d659ab1f2fd87" aria-disabled="false"></div>
              <div class="swiper-button-prev button swiper-button-disabled" tabindex="-1" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-1a8d659ab1f2fd87" aria-disabled="true"></div>
              <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
            <div class="review list">
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <div class="rating-result" style="width: 0%">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
              </div>
            </div>
            <div class="center">
              <div class="category">Захисні плівки</div>
              <a href="https://wrap.shop/plivky/zahysni-plivky/antigravyna-glyanceva-plvka-kybertane-ppf-ultra-gloss-03m" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,3 м" class="name">Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,3 м</a>
            </div>
            <div class="bottom flex-center">
              <div class="price">630.00 ₴<span class="price-unit-xvr"></span></div>
              <button class="button colord remarketing_cart_button" onclick="cart.add('544', '0.100');" data-product_id="544"><i class="fas fa-chevron-right"></i>Купити</button>
            </div>
            <div class="params">
              <div class="item flex-column">Призначення <span class="label">антигравійна</span></div>
              <div class="item flex-column">Структура <span class="label">глянцева</span></div>
            </div>
          </div>



          <div class="category-products-item product-default product-layout product-grid" id="categoryProductsItem543">
            <div class="top flex-justify">
              <div class="sku">Код: 13271</div>
              <div class="review grid">
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <div class="rating-result" style="width: 0%">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
              </div>
              <div class="wishlist">
                <button type="button" class="button far fa-heart" title="В закладки" onclick="wishlist.add('543');"></button>
              </div>
            </div>
            <div class="sale statuses"></div>
            <div class="image swiper swiper-initialized swiper-horizontal swiper-android swiper-backface-hidden">
              <i class="far fa-search-plus colord" data-src="/image/catalog/demo/syncms/3f085a54-87b5-11ee-0a80-106a0044405e_0.png" data-fancybox="gallery543" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,61 м"></i>
              <a href="https://wrap.shop/plivky/zahysni-plivky/plivka-antygravijna-glyanceva-kybertane-ppf-ultra-gloss-0-61-m" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,61 м" class="swiper-wrapper" id="swiper-wrapper-03f1620d4e27bb69" aria-live="polite">
                <div class="swiper-slide item flex-center swiper-slide-active" role="group" aria-label="1 / 8" style="display: flex; width: 241px;">
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/3f085a54-87b5-11ee-0a80-106a0044405e_0-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/3f085a54-87b5-11ee-0a80-106a0044405e_0-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,61 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,61 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center swiper-slide-next" role="group" aria-label="2 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/3f085a54-87b5-11ee-0a80-106a0044405e_1.png" data-fancybox="gallery543" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,61 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/3f085a54-87b5-11ee-0a80-106a0044405e_1-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/3f085a54-87b5-11ee-0a80-106a0044405e_1-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,61 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,61 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="3 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/3f085a54-87b5-11ee-0a80-106a0044405e_2.png" data-fancybox="gallery543" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,61 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/3f085a54-87b5-11ee-0a80-106a0044405e_2-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/3f085a54-87b5-11ee-0a80-106a0044405e_2-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,61 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,61 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="4 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/3f085a54-87b5-11ee-0a80-106a0044405e_3.png" data-fancybox="gallery543" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,61 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/3f085a54-87b5-11ee-0a80-106a0044405e_3-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/3f085a54-87b5-11ee-0a80-106a0044405e_3-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,61 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,61 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="5 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/3f085a54-87b5-11ee-0a80-106a0044405e_4.png" data-fancybox="gallery543" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,61 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/3f085a54-87b5-11ee-0a80-106a0044405e_4-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/3f085a54-87b5-11ee-0a80-106a0044405e_4-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,61 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,61 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="6 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/3f085a54-87b5-11ee-0a80-106a0044405e_5.png" data-fancybox="gallery543" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,61 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/3f085a54-87b5-11ee-0a80-106a0044405e_5-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/3f085a54-87b5-11ee-0a80-106a0044405e_5-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,61 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,61 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="7 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/3f085a54-87b5-11ee-0a80-106a0044405e_6.png" data-fancybox="gallery543" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,61 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/3f085a54-87b5-11ee-0a80-106a0044405e_6-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/3f085a54-87b5-11ee-0a80-106a0044405e_6-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,61 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,61 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="8 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/3f085a54-87b5-11ee-0a80-106a0044405e_7.png" data-fancybox="gallery543" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,61 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/3f085a54-87b5-11ee-0a80-106a0044405e_7-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/3f085a54-87b5-11ee-0a80-106a0044405e_7-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,61 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,61 м" width="200" height="200">
                </div>
              </a>
              <div class="swiper-button-next button" tabindex="0" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-03f1620d4e27bb69" aria-disabled="false"></div>
              <div class="swiper-button-prev button swiper-button-disabled" tabindex="-1" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-03f1620d4e27bb69" aria-disabled="true"></div>
              <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
            <div class="review list">
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <div class="rating-result" style="width: 0%">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
              </div>
            </div>
            <div class="center">
              <div class="category">Захисні плівки</div>
              <a href="https://wrap.shop/plivky/zahysni-plivky/plivka-antygravijna-glyanceva-kybertane-ppf-ultra-gloss-0-61-m" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,61 м" class="name">Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,61 м</a>
            </div>
            <div class="bottom flex-center">
              <div class="price">1260.00 ₴<span class="price-unit-xvr"></span></div>
              <button class="button colord remarketing_cart_button" onclick="cart.add('543', '0.100');" data-product_id="543"><i class="fas fa-chevron-right"></i>Купити</button>
            </div>
            <div class="params">
              <div class="item flex-column">Призначення <span class="label">антигравійна</span></div>
              <div class="item flex-column">Структура <span class="label">глянцева</span></div>
            </div>
          </div>

          <a href="https://wrap.shop/merch/" title="merch" class="category-products-item product-default product-layout banner product-grid">
            <img class="vertical" data-src="/image/catalog/category/webp/banners catalog/5-merch-vert.webp" alt="merch" title="merch">
            <img class="gorizont" data-src="/image/catalog/category/webp/banners catalog/5-merch-gor.webp" alt="merch" title="merch">
          </a>


          <div class="category-products-item product-default product-layout product-grid" id="categoryProductsItem542">
            <div class="top flex-justify">
              <div class="sku">Код: 13270</div>
              <div class="review grid">
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <div class="rating-result" style="width: 0%">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
              </div>
              <div class="wishlist">
                <button type="button" class="button far fa-heart" title="В закладки" onclick="wishlist.add('542');"></button>
              </div>
            </div>
            <div class="sale statuses"></div>
            <div class="image swiper swiper-initialized swiper-horizontal swiper-android swiper-backface-hidden">
              <i class="far fa-search-plus colord" data-src="/image/catalog/demo/syncms/295ee8e6-87b5-11ee-0a80-05910043c5a3_0.png" data-fancybox="gallery542" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,76 м"></i>
              <a href="https://wrap.shop/plivky/zahysni-plivky/plivka-antygravijna-glyanceva-kybertane-ppf-ultra-gloss-0-76-m" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,76 м" class="swiper-wrapper" id="swiper-wrapper-4ba654b8a08ca3a9" aria-live="polite">
                <div class="swiper-slide item flex-center swiper-slide-active" role="group" aria-label="1 / 8" style="display: flex; width: 241px;">
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/295ee8e6-87b5-11ee-0a80-05910043c5a3_0-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/295ee8e6-87b5-11ee-0a80-05910043c5a3_0-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,76 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,76 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center swiper-slide-next" role="group" aria-label="2 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/295ee8e6-87b5-11ee-0a80-05910043c5a3_1.png" data-fancybox="gallery542" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,76 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/295ee8e6-87b5-11ee-0a80-05910043c5a3_1-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/295ee8e6-87b5-11ee-0a80-05910043c5a3_1-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,76 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,76 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="3 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/295ee8e6-87b5-11ee-0a80-05910043c5a3_2.png" data-fancybox="gallery542" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,76 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/295ee8e6-87b5-11ee-0a80-05910043c5a3_2-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/295ee8e6-87b5-11ee-0a80-05910043c5a3_2-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,76 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,76 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="4 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/295ee8e6-87b5-11ee-0a80-05910043c5a3_3.png" data-fancybox="gallery542" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,76 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/295ee8e6-87b5-11ee-0a80-05910043c5a3_3-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/295ee8e6-87b5-11ee-0a80-05910043c5a3_3-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,76 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,76 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="5 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/295ee8e6-87b5-11ee-0a80-05910043c5a3_4.png" data-fancybox="gallery542" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,76 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/295ee8e6-87b5-11ee-0a80-05910043c5a3_4-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/295ee8e6-87b5-11ee-0a80-05910043c5a3_4-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,76 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,76 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="6 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/295ee8e6-87b5-11ee-0a80-05910043c5a3_5.png" data-fancybox="gallery542" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,76 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/295ee8e6-87b5-11ee-0a80-05910043c5a3_5-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/295ee8e6-87b5-11ee-0a80-05910043c5a3_5-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,76 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,76 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="7 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/295ee8e6-87b5-11ee-0a80-05910043c5a3_6.png" data-fancybox="gallery542" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,76 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/295ee8e6-87b5-11ee-0a80-05910043c5a3_6-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/295ee8e6-87b5-11ee-0a80-05910043c5a3_6-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,76 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,76 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="8 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/295ee8e6-87b5-11ee-0a80-05910043c5a3_7.png" data-fancybox="gallery542" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,76 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/295ee8e6-87b5-11ee-0a80-05910043c5a3_7-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/295ee8e6-87b5-11ee-0a80-05910043c5a3_7-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,76 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,76 м" width="200" height="200">
                </div>
              </a>
              <div class="swiper-button-next button" tabindex="0" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-4ba654b8a08ca3a9" aria-disabled="false"></div>
              <div class="swiper-button-prev button swiper-button-disabled" tabindex="-1" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-4ba654b8a08ca3a9" aria-disabled="true"></div>
              <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
            <div class="review list">
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <div class="rating-result" style="width: 0%">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
              </div>
            </div>
            <div class="center">
              <div class="category">Захисні плівки</div>
              <a href="https://wrap.shop/plivky/zahysni-plivky/plivka-antygravijna-glyanceva-kybertane-ppf-ultra-gloss-0-76-m" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,76 м" class="name">Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,76 м</a>
            </div>
            <div class="bottom flex-center">
              <div class="price">1470.00 ₴<span class="price-unit-xvr"></span></div>
              <button class="button colord remarketing_cart_button" onclick="cart.add('542', '0.100');" data-product_id="542"><i class="fas fa-chevron-right"></i>Купити</button>
            </div>
            <div class="params">
              <div class="item flex-column">Призначення <span class="label">антигравійна</span></div>
              <div class="item flex-column">Структура <span class="label">глянцева</span></div>
            </div>
          </div>



          <div class="category-products-item product-default product-layout product-grid" id="categoryProductsItem541">
            <div class="top flex-justify">
              <div class="sku">Код: 13269</div>
              <div class="review grid">
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <div class="rating-result" style="width: 0%">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
              </div>
              <div class="wishlist">
                <button type="button" class="button far fa-heart" title="В закладки" onclick="wishlist.add('541');"></button>
              </div>
            </div>
            <div class="sale statuses"></div>
            <div class="image swiper swiper-initialized swiper-horizontal swiper-android swiper-backface-hidden">
              <i class="far fa-search-plus colord" data-src="/image/catalog/demo/syncms/10eea3ab-87b5-11ee-0a80-05910043c27f_0.png" data-fancybox="gallery541" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,9 м"></i>
              <a href="https://wrap.shop/plivky/zahysni-plivky/plivka-antygravijna-glyanceva-kybertane-ppf-ultra-gloss-0-9-m" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,9 м" class="swiper-wrapper" id="swiper-wrapper-8ead598ba139f16a" aria-live="polite">
                <div class="swiper-slide item flex-center swiper-slide-active" role="group" aria-label="1 / 8" style="display: flex; width: 241px;">
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/10eea3ab-87b5-11ee-0a80-05910043c27f_0-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/10eea3ab-87b5-11ee-0a80-05910043c27f_0-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,9 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,9 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center swiper-slide-next" role="group" aria-label="2 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/10eea3ab-87b5-11ee-0a80-05910043c27f_1.png" data-fancybox="gallery541" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,9 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/10eea3ab-87b5-11ee-0a80-05910043c27f_1-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/10eea3ab-87b5-11ee-0a80-05910043c27f_1-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,9 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,9 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="3 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/10eea3ab-87b5-11ee-0a80-05910043c27f_2.png" data-fancybox="gallery541" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,9 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/10eea3ab-87b5-11ee-0a80-05910043c27f_2-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/10eea3ab-87b5-11ee-0a80-05910043c27f_2-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,9 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,9 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="4 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/10eea3ab-87b5-11ee-0a80-05910043c27f_3.png" data-fancybox="gallery541" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,9 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/10eea3ab-87b5-11ee-0a80-05910043c27f_3-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/10eea3ab-87b5-11ee-0a80-05910043c27f_3-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,9 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,9 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="5 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/10eea3ab-87b5-11ee-0a80-05910043c27f_4.png" data-fancybox="gallery541" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,9 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/10eea3ab-87b5-11ee-0a80-05910043c27f_4-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/10eea3ab-87b5-11ee-0a80-05910043c27f_4-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,9 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,9 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="6 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/10eea3ab-87b5-11ee-0a80-05910043c27f_5.png" data-fancybox="gallery541" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,9 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/10eea3ab-87b5-11ee-0a80-05910043c27f_5-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/10eea3ab-87b5-11ee-0a80-05910043c27f_5-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,9 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,9 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="7 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/10eea3ab-87b5-11ee-0a80-05910043c27f_6.png" data-fancybox="gallery541" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,9 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/10eea3ab-87b5-11ee-0a80-05910043c27f_6-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/10eea3ab-87b5-11ee-0a80-05910043c27f_6-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,9 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,9 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="8 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/10eea3ab-87b5-11ee-0a80-05910043c27f_7.png" data-fancybox="gallery541" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,9 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/10eea3ab-87b5-11ee-0a80-05910043c27f_7-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/10eea3ab-87b5-11ee-0a80-05910043c27f_7-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,9 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,9 м" width="200" height="200">
                </div>
              </a>
              <div class="swiper-button-next button" tabindex="0" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-8ead598ba139f16a" aria-disabled="false"></div>
              <div class="swiper-button-prev button swiper-button-disabled" tabindex="-1" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-8ead598ba139f16a" aria-disabled="true"></div>
              <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
            <div class="review list">
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <div class="rating-result" style="width: 0%">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
              </div>
            </div>
            <div class="center">
              <div class="category">Захисні плівки</div>
              <a href="https://wrap.shop/plivky/zahysni-plivky/plivka-antygravijna-glyanceva-kybertane-ppf-ultra-gloss-0-9-m" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,9 м" class="name">Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 0,9 м</a>
            </div>
            <div class="bottom flex-center">
              <div class="price">1974.00 ₴<span class="price-unit-xvr"></span></div>
              <button class="button colord remarketing_cart_button" onclick="cart.add('541', '0.100');" data-product_id="541"><i class="fas fa-chevron-right"></i>Купити</button>
            </div>
            <div class="params">
              <div class="item flex-column">Призначення <span class="label">антигравійна</span></div>
              <div class="item flex-column">Структура <span class="label">глянцева</span></div>
            </div>
          </div>



          <div class="category-products-item product-default product-layout product-grid" id="categoryProductsItem545">
            <div class="top flex-justify">
              <div class="sku">Код: 13268</div>
              <div class="review grid">
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <div class="rating-result" style="width: 0%">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
              </div>
              <div class="wishlist">
                <button type="button" class="button far fa-heart" title="В закладки" onclick="wishlist.add('545');"></button>
              </div>
            </div>
            <div class="sale statuses"></div>
            <div class="image swiper swiper-initialized swiper-horizontal swiper-android swiper-backface-hidden">
              <i class="far fa-search-plus colord" data-src="/image/catalog/demo/syncms/f58a0578-87b4-11ee-0a80-098d00425b0c_0.png" data-fancybox="gallery545" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,22 м"></i>
              <a href="https://wrap.shop/plivky/zahysni-plivky/plivka-antygravijna-glyanceva-kybertane-ppf-ultra-gloss-1-22-m" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,22 м" class="swiper-wrapper" id="swiper-wrapper-6978dec1f2ada6109" aria-live="polite">
                <div class="swiper-slide item flex-center swiper-slide-active" role="group" aria-label="1 / 8" style="display: flex; width: 241px;">
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/f58a0578-87b4-11ee-0a80-098d00425b0c_0-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/f58a0578-87b4-11ee-0a80-098d00425b0c_0-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,22 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,22 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center swiper-slide-next" role="group" aria-label="2 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/f58a0578-87b4-11ee-0a80-098d00425b0c_1.png" data-fancybox="gallery545" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,22 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/f58a0578-87b4-11ee-0a80-098d00425b0c_1-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/f58a0578-87b4-11ee-0a80-098d00425b0c_1-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,22 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,22 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="3 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/f58a0578-87b4-11ee-0a80-098d00425b0c_2.png" data-fancybox="gallery545" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,22 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/f58a0578-87b4-11ee-0a80-098d00425b0c_2-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/f58a0578-87b4-11ee-0a80-098d00425b0c_2-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,22 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,22 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="4 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/f58a0578-87b4-11ee-0a80-098d00425b0c_3.png" data-fancybox="gallery545" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,22 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/f58a0578-87b4-11ee-0a80-098d00425b0c_3-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/f58a0578-87b4-11ee-0a80-098d00425b0c_3-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,22 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,22 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="5 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/f58a0578-87b4-11ee-0a80-098d00425b0c_4.png" data-fancybox="gallery545" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,22 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/f58a0578-87b4-11ee-0a80-098d00425b0c_4-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/f58a0578-87b4-11ee-0a80-098d00425b0c_4-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,22 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,22 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="6 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/f58a0578-87b4-11ee-0a80-098d00425b0c_5.png" data-fancybox="gallery545" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,22 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/f58a0578-87b4-11ee-0a80-098d00425b0c_5-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/f58a0578-87b4-11ee-0a80-098d00425b0c_5-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,22 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,22 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="7 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/f58a0578-87b4-11ee-0a80-098d00425b0c_6.png" data-fancybox="gallery545" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,22 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/f58a0578-87b4-11ee-0a80-098d00425b0c_6-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/f58a0578-87b4-11ee-0a80-098d00425b0c_6-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,22 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,22 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="8 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/f58a0578-87b4-11ee-0a80-098d00425b0c_7.png" data-fancybox="gallery545" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,22 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/f58a0578-87b4-11ee-0a80-098d00425b0c_7-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/f58a0578-87b4-11ee-0a80-098d00425b0c_7-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,22 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,22 м" width="200" height="200">
                </div>
              </a>
              <div class="swiper-button-next button" tabindex="0" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-6978dec1f2ada6109" aria-disabled="false"></div>
              <div class="swiper-button-prev button swiper-button-disabled" tabindex="-1" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-6978dec1f2ada6109" aria-disabled="true"></div>
              <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
            <div class="review list">
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <div class="rating-result" style="width: 0%">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
              </div>
            </div>
            <div class="center">
              <div class="category">Захисні плівки</div>
              <a href="https://wrap.shop/plivky/zahysni-plivky/plivka-antygravijna-glyanceva-kybertane-ppf-ultra-gloss-1-22-m" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,22 м" class="name">Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,22 м</a>
            </div>
            <div class="bottom flex-center">
              <div class="price">2352.00 ₴<span class="price-unit-xvr"></span></div>
              <button class="button colord remarketing_cart_button" onclick="cart.add('545', '0.100');" data-product_id="545"><i class="fas fa-chevron-right"></i>Купити</button>
            </div>
            <div class="params">
              <div class="item flex-column">Призначення <span class="label">антигравійна</span></div>
              <div class="item flex-column">Структура <span class="label">глянцева</span></div>
            </div>
          </div>



          <div class="category-products-item product-default product-layout product-grid" id="categoryProductsItem608">
            <div class="top flex-justify">
              <div class="sku">Код: 13346</div>
              <div class="review grid">
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <div class="rating-result" style="width: 0%">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
              </div>
              <div class="wishlist">
                <button type="button" class="button far fa-heart" title="В закладки" onclick="wishlist.add('608');"></button>
              </div>
            </div>
            <div class="sale statuses"></div>
            <div class="image swiper swiper-initialized swiper-horizontal swiper-android swiper-backface-hidden">
              <i class="far fa-search-plus colord" data-src="/image/catalog/demo/syncms/30b635e2-ae13-11ee-0a80-0dfd006638b9_0.png" data-fancybox="gallery608" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,83 м"></i>
              <a href="https://wrap.shop/plivky/zahysni-plivky/plivka-antygravijna-glyanceva-kybertane-ppf-ultra-gloss-1-83-m" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,83 м" class="swiper-wrapper" id="swiper-wrapper-df862b1ff258a010f" aria-live="polite">
                <div class="swiper-slide item flex-center swiper-slide-active" role="group" aria-label="1 / 8" style="display: flex; width: 241px;">
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/30b635e2-ae13-11ee-0a80-0dfd006638b9_0-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/30b635e2-ae13-11ee-0a80-0dfd006638b9_0-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,83 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,83 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center swiper-slide-next" role="group" aria-label="2 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/30b635e2-ae13-11ee-0a80-0dfd006638b9_1.png" data-fancybox="gallery608" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,83 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/30b635e2-ae13-11ee-0a80-0dfd006638b9_1-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/30b635e2-ae13-11ee-0a80-0dfd006638b9_1-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,83 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,83 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="3 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/30b635e2-ae13-11ee-0a80-0dfd006638b9_2.png" data-fancybox="gallery608" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,83 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/30b635e2-ae13-11ee-0a80-0dfd006638b9_2-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/30b635e2-ae13-11ee-0a80-0dfd006638b9_2-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,83 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,83 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="4 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/30b635e2-ae13-11ee-0a80-0dfd006638b9_3.png" data-fancybox="gallery608" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,83 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/30b635e2-ae13-11ee-0a80-0dfd006638b9_3-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/30b635e2-ae13-11ee-0a80-0dfd006638b9_3-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,83 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,83 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="5 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/30b635e2-ae13-11ee-0a80-0dfd006638b9_4.png" data-fancybox="gallery608" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,83 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/30b635e2-ae13-11ee-0a80-0dfd006638b9_4-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/30b635e2-ae13-11ee-0a80-0dfd006638b9_4-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,83 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,83 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="6 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/30b635e2-ae13-11ee-0a80-0dfd006638b9_5.png" data-fancybox="gallery608" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,83 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/30b635e2-ae13-11ee-0a80-0dfd006638b9_5-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/30b635e2-ae13-11ee-0a80-0dfd006638b9_5-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,83 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,83 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="7 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/30b635e2-ae13-11ee-0a80-0dfd006638b9_6.png" data-fancybox="gallery608" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,83 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/30b635e2-ae13-11ee-0a80-0dfd006638b9_6-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/30b635e2-ae13-11ee-0a80-0dfd006638b9_6-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,83 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,83 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="8 / 8" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/30b635e2-ae13-11ee-0a80-0dfd006638b9_7.png" data-fancybox="gallery608" data-caption="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,83 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/30b635e2-ae13-11ee-0a80-0dfd006638b9_7-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/30b635e2-ae13-11ee-0a80-0dfd006638b9_7-482x482.webp 2x" alt="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,83 м" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,83 м" width="200" height="200">
                </div>
              </a>
              <div class="swiper-button-next button" tabindex="0" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-df862b1ff258a010f" aria-disabled="false"></div>
              <div class="swiper-button-prev button swiper-button-disabled" tabindex="-1" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-df862b1ff258a010f" aria-disabled="true"></div>
              <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
            <div class="review list">
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <div class="rating-result" style="width: 0%">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
              </div>
            </div>
            <div class="center">
              <div class="category">Захисні плівки</div>
              <a href="https://wrap.shop/plivky/zahysni-plivky/plivka-antygravijna-glyanceva-kybertane-ppf-ultra-gloss-1-83-m" title="Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,83 м" class="name">Плівка антигравійна глянцева Kybertane PPF Ultra Gloss 1,83 м</a>
            </div>
            <div class="bottom flex-center">
              <div class="price">3780.00 ₴<span class="price-unit-xvr"></span></div>
              <button class="button colord remarketing_cart_button" onclick="cart.add('608', '0.100');" data-product_id="608"><i class="fas fa-chevron-right"></i>Купити</button>
            </div>
            <div class="params">
              <div class="item flex-column">Призначення <span class="label">антигравійна</span></div>
              <div class="item flex-column">Структура <span class="label">глянцева</span></div>
            </div>
          </div>



          <div class="category-products-item product-default product-layout product-grid" id="categoryProductsItem548">
            <div class="top flex-justify">
              <div class="sku">Код: 13275</div>
              <div class="review grid">
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <i class="fal fa-star"></i>
                <div class="rating-result" style="width: 0%">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
              </div>
              <div class="wishlist">
                <button type="button" class="button far fa-heart" title="В закладки" onclick="wishlist.add('548');"></button>
              </div>
            </div>
            <div class="sale statuses"></div>
            <div class="image swiper swiper-initialized swiper-horizontal swiper-android swiper-backface-hidden">
              <i class="far fa-search-plus colord" data-src="/image/catalog/demo/syncms/710c6453-89fc-11ee-0a80-0c51001faedf_0.png" data-fancybox="gallery548" data-caption="Плівка антигравійна сатинова Kybertane PPF Deep Satin 0,76 м"></i>
              <a href="https://wrap.shop/plivky/zahysni-plivky/antygravijna-satynova-plivka-kybertane-ppf-deep-satin-0-76-m" title="Плівка антигравійна сатинова Kybertane PPF Deep Satin 0,76 м" class="swiper-wrapper" id="swiper-wrapper-9bf611e47a5ecdcc" aria-live="polite">
                <div class="swiper-slide item flex-center swiper-slide-active" role="group" aria-label="1 / 7" style="display: flex; width: 241px;">
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/710c6453-89fc-11ee-0a80-0c51001faedf_0-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/710c6453-89fc-11ee-0a80-0c51001faedf_0-482x482.webp 2x" alt="Плівка антигравійна сатинова Kybertane PPF Deep Satin 0,76 м" title="Плівка антигравійна сатинова Kybertane PPF Deep Satin 0,76 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center swiper-slide-next" role="group" aria-label="2 / 7" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/710c6453-89fc-11ee-0a80-0c51001faedf_1.png" data-fancybox="gallery548" data-caption="Плівка антигравійна сатинова Kybertane PPF Deep Satin 0,76 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/710c6453-89fc-11ee-0a80-0c51001faedf_1-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/710c6453-89fc-11ee-0a80-0c51001faedf_1-482x482.webp 2x" alt="Плівка антигравійна сатинова Kybertane PPF Deep Satin 0,76 м" title="Плівка антигравійна сатинова Kybertane PPF Deep Satin 0,76 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="3 / 7" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/710c6453-89fc-11ee-0a80-0c51001faedf_2.png" data-fancybox="gallery548" data-caption="Плівка антигравійна сатинова Kybertane PPF Deep Satin 0,76 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/710c6453-89fc-11ee-0a80-0c51001faedf_2-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/710c6453-89fc-11ee-0a80-0c51001faedf_2-482x482.webp 2x" alt="Плівка антигравійна сатинова Kybertane PPF Deep Satin 0,76 м" title="Плівка антигравійна сатинова Kybertane PPF Deep Satin 0,76 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="4 / 7" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/710c6453-89fc-11ee-0a80-0c51001faedf_3.png" data-fancybox="gallery548" data-caption="Плівка антигравійна сатинова Kybertane PPF Deep Satin 0,76 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/710c6453-89fc-11ee-0a80-0c51001faedf_3-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/710c6453-89fc-11ee-0a80-0c51001faedf_3-482x482.webp 2x" alt="Плівка антигравійна сатинова Kybertane PPF Deep Satin 0,76 м" title="Плівка антигравійна сатинова Kybertane PPF Deep Satin 0,76 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="5 / 7" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/710c6453-89fc-11ee-0a80-0c51001faedf_4.png" data-fancybox="gallery548" data-caption="Плівка антигравійна сатинова Kybertane PPF Deep Satin 0,76 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/710c6453-89fc-11ee-0a80-0c51001faedf_4-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/710c6453-89fc-11ee-0a80-0c51001faedf_4-482x482.webp 2x" alt="Плівка антигравійна сатинова Kybertane PPF Deep Satin 0,76 м" title="Плівка антигравійна сатинова Kybertane PPF Deep Satin 0,76 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="6 / 7" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/710c6453-89fc-11ee-0a80-0c51001faedf_5.png" data-fancybox="gallery548" data-caption="Плівка антигравійна сатинова Kybertane PPF Deep Satin 0,76 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/710c6453-89fc-11ee-0a80-0c51001faedf_5-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/710c6453-89fc-11ee-0a80-0c51001faedf_5-482x482.webp 2x" alt="Плівка антигравійна сатинова Kybertane PPF Deep Satin 0,76 м" title="Плівка антигравійна сатинова Kybertane PPF Deep Satin 0,76 м" width="200" height="200">
                </div>
                <div class="swiper-slide item flex-center" role="group" aria-label="7 / 7" style="display: flex; width: 241px;">
                  <div class="hide" data-src="/image/catalog/demo/syncms/710c6453-89fc-11ee-0a80-0c51001faedf_6.png" data-fancybox="gallery548" data-caption="Плівка антигравійна сатинова Kybertane PPF Deep Satin 0,76 м"></div>
                  <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/710c6453-89fc-11ee-0a80-0c51001faedf_6-241x241.webp" srcset="https://wrap.shop/image/cachewebp/catalog/demo/syncms/710c6453-89fc-11ee-0a80-0c51001faedf_6-482x482.webp 2x" alt="Плівка антигравійна сатинова Kybertane PPF Deep Satin 0,76 м" title="Плівка антигравійна сатинова Kybertane PPF Deep Satin 0,76 м" width="200" height="200">
                </div>
              </a>
              <div class="swiper-button-next button" tabindex="0" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-9bf611e47a5ecdcc" aria-disabled="false"></div>
              <div class="swiper-button-prev button swiper-button-disabled" tabindex="-1" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-9bf611e47a5ecdcc" aria-disabled="true"></div>
              <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
            <div class="review list">
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <i class="fal fa-star"></i>
              <div class="rating-result" style="width: 0%">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
              </div>
            </div>
            <div class="center">
              <div class="category">Захисні плівки</div>
              <a href="https://wrap.shop/plivky/zahysni-plivky/antygravijna-satynova-plivka-kybertane-ppf-deep-satin-0-76-m" title="Плівка антигравійна сатинова Kybertane PPF Deep Satin 0,76 м" class="name">Плівка антигравійна сатинова Kybertane PPF Deep Satin 0,76 м</a>
            </div>
            <div class="bottom flex-center">
              <div class="price">1764.00 ₴<span class="price-unit-xvr"></span></div>
              <button class="button colord remarketing_cart_button" onclick="cart.add('548', '0.100');" data-product_id="548"><i class="fas fa-chevron-right"></i>Купити</button>
            </div>
            <div class="params">
              <div class="item flex-column">Призначення <span class="label">антигравійна</span></div>
              <div class="item flex-column">Структура <span class="label">сатинова</span></div>
            </div>
          </div>

          <a href="https://wrap.shop/tyuning/" title="tuning" class="category-products-item product-default product-layout banner product-grid">
            <img class="vertical" data-src="/image/catalog/category/webp/banners catalog/6-tun-vert.webp" alt="tuning" title="tuning">
            <img class="gorizont" data-src="/image/catalog/category/webp/banners catalog/6-tun-gor.webp" alt="tuning" title="tuning">
          </a>



        </div>

        <div class="category-bottom flex-center"><div id="ss_showmore" class="category-loadmore"><div class="colord lloading" data-loader="arrow-circle"></div><div type="button" class="button" data-all_page="15" data-start="1" data-product_total="447 " data-limit="30">Показати наступні<i class="fas fa-chevron-down"></i></div></div>
          <div class="category-pagination"><ul class="flex-center pagination"><li class="active"><span>1</span></li><li><a href="https://wrap.shop/plivky/?page=2">2</a></li><li><a href="https://wrap.shop/plivky/?page=3">3</a></li><li><a href="https://wrap.shop/plivky/?page=4">4</a></li><li><a href="https://wrap.shop/plivky/?page=5">5</a></li><li><a href="https://wrap.shop/plivky/?page=6">6</a></li><li><a href="https://wrap.shop/plivky/?page=7">7</a></li><li><a href="https://wrap.shop/plivky/?page=8">8</a></li><li><a href="https://wrap.shop/plivky/?page=9">9</a></li><li class="item-next hide"><a href="https://wrap.shop/plivky/?page=2">&gt;</a></li><li class="item-last hide"><a href="https://wrap.shop/plivky/?page=15">&gt;|</a></li></ul></div>
        </div>


      </div>
    </div>
  </section>


@endsection
