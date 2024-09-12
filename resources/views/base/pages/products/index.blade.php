@extends('base.layouts.app')


@section('content')

  @push('styles')
    <link rel="stylesheet" href="{{mix('build/css/all-dark.css')}}">
    <link rel="stylesheet" href="{{mix('build/css/style-category-dark.css')}}">
  @endpush

  <section class="category-page row">
    <div class="category-top"
         style="background-image: url(&quot;https://wrap.shop/image/catalog/category/webp/cat/fon_wrap_webp.webp&quot;);"
         data-background="https://wrap.shop/image/catalog/category/webp/cat/fon_wrap_webp.webp">
      <nav class="category-breadcrumbs">
        <ul class="flex-center">
          <li><a href="https://wrap.shop/" title="Головна" class="button"><i class="far fa-chevron-left"></i>Головна</a>
          </li>
        </ul>
      </nav>
      <h1 class="title">Плівки</h1>

      <nav class="category-child-nav">
        <ul>
          <li><a href="https://wrap.shop/plivky/kolorovi-plivky/" title="Кольорові плівки" class="flex-center">Кольорові
              плівки</a></li>
          <li><a href="https://wrap.shop/plivky/zahysni-plivky/" title="Захисні плівки" class="flex-center">Захисні
              плівки</a></li>
          <li><a href="https://wrap.shop/plivky/plivka-dlya-avtomobilnyh-vikon/" title="Плівка для автомобільних вікон"
                 class="flex-center">Плівка для автомобільних вікон</a></li>
          <li><a href="https://wrap.shop/plivky/gotovi-dyzajny-ta-plivka-dlya-druku/"
                 title="Готові дизайни та плівка для друку" class="flex-center">Готові дизайни та плівка для друку</a>
          </li>
          <li><a href="https://wrap.shop/plivky/lekala-dlya-salona/" title="Лекала для салона" class="flex-center">Лекала
              для салона</a></li>
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
        <div class="ocf-container ocf-category-60 ocf-theme-light ocf-mobile-1 ocf-mobile-left ocf-vertical ocf-left"
             id="ocf-module-1">
          <link rel="stylesheet" href="">

          <div class="ocf-content">
            <div class="ocf-header">
              Фільтри


              <button type="button" data-ocf="mobile" class="ocf-close-mobile" aria-label="Close filter"><i
                  class="fal fa-times"></i></button>
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
                        <input type="number" name="ocf[2-0-1][min]" min="336" max="5586" value="336"
                               class="ocf-form-control ocf-grouping ocf-min" id="ocf-input-min-2-0-1"
                               data-filter-key="2.0" autocomplete="off" aria-label="Ціна">
                        <input type="number" name="ocf[2-0-1][max]" min="336" max="5586" value="5586"
                               class="ocf-form-control ocf-grouping ocf-max" id="ocf-input-max-2-0-1"
                               data-filter-key="2.0" autocomplete="off" aria-label="Ціна">
                      </div>

                      <div class="ocf-value-list">
                        <div class="ocf-value-list-body">

                          <button type="button" id="ocf-v-2-0-330-1700-1" class="ocf-value ocf-radio"
                                  data-filter-key="2.0" data-value-id="330-1700">
                            <span class="ocf-value-input ocf-value-input-radio"></span>

                            <span class="ocf-value-name">330 - 1700 ₴</span>
                          </button>
                          <button type="button" id="ocf-v-2-0-1700-3000-1" class="ocf-value ocf-radio"
                                  data-filter-key="2.0" data-value-id="1700-3000">
                            <span class="ocf-value-input ocf-value-input-radio"></span>

                            <span class="ocf-value-name">1700 - 3000 ₴</span>
                          </button>
                          <button type="button" id="ocf-v-2-0-3000-4300-1" class="ocf-value ocf-radio"
                                  data-filter-key="2.0" data-value-id="3000-4300">
                            <span class="ocf-value-input ocf-value-input-radio"></span>

                            <span class="ocf-value-name">3000 - 4300 ₴</span>
                          </button>
                          <button type="button" id="ocf-v-2-0-4300-5600-1" class="ocf-value ocf-radio"
                                  data-filter-key="2.0" data-value-id="4300-5600">
                            <span class="ocf-value-input ocf-value-input-radio"></span>

                            <span class="ocf-value-name">4300 - 5600 ₴</span>
                          </button>
                        </div>


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

                          <button type="button" id="ocf-v-1-0-11-1" class="ocf-value ocf-checkbox" data-filter-key="1.0"
                                  data-value-id="11">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">3M</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">189</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-1-0-47-1" class="ocf-value ocf-checkbox" data-filter-key="1.0"
                                  data-value-id="47">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">Avery</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">109</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-1-0-51-1" class="ocf-value ocf-checkbox" data-filter-key="1.0"
                                  data-value-id="51">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">Hexis</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">21</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-1-0-45-1" class="ocf-value ocf-checkbox" data-filter-key="1.0"
                                  data-value-id="45">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">Inozetek</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">39</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-1-0-55-1" class="ocf-value ocf-checkbox" data-filter-key="1.0"
                                  data-value-id="55">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">KPMF</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">8</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-1-0-44-1" class="ocf-value ocf-checkbox" data-filter-key="1.0"
                                  data-value-id="44">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">Kybertane</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">72</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-1-0-32-1" class="ocf-value ocf-checkbox" data-filter-key="1.0"
                                  data-value-id="32">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">Madico</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">2</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-1-0-53-1" class="ocf-value ocf-checkbox" data-filter-key="1.0"
                                  data-value-id="53">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">Omega</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">2</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-1-0-56-1" class="ocf-value ocf-checkbox" data-filter-key="1.0"
                                  data-value-id="56">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">Oracal</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">4</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-1-0-54-1" class="ocf-value ocf-checkbox" data-filter-key="1.0"
                                  data-value-id="54">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">TeckWrap</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                        </div>


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

                            <button type="button" id="ocf-v-40-2-1225646050-1" class="ocf-value ocf-checkbox"
                                    data-filter-key="40.2" data-value-id="1225646050">
                              <span class="ocf-value-color" style="background-color: #fff;"></span>

                              <span class="ocf-value-name">білий</span>
                              <span class="ocf-value-append">
    <span class="ocf-value-count">31</span>
  </span>
                            </button>
                            <button type="button" id="ocf-v-40-2-1718022153-1" class="ocf-value ocf-checkbox"
                                    data-filter-key="40.2" data-value-id="1718022153">
                              <span class="ocf-value-color" style="background-color: #16bcb4;"></span>

                              <span class="ocf-value-name">бірюзовий</span>
                              <span class="ocf-value-append">
    <span class="ocf-value-count">8</span>
  </span>
                            </button>
                            <button type="button" id="ocf-v-40-2-2469220262-1" class="ocf-value ocf-checkbox"
                                    data-filter-key="40.2" data-value-id="2469220262">
                              <span class="ocf-value-color" style="background-color: #fbad82;"></span>

                              <span class="ocf-value-name">бежевий</span>
                              <span class="ocf-value-append">
    <span class="ocf-value-count">7</span>
  </span>
                            </button>
                            <button type="button" id="ocf-v-40-2-4223283398-1" class="ocf-value ocf-checkbox"
                                    data-filter-key="40.2" data-value-id="4223283398">
                              <span class="ocf-value-color" style="background-color: #00aeef;"></span>

                              <span class="ocf-value-name">блакитний</span>
                              <span class="ocf-value-append">
    <span class="ocf-value-count">16</span>
  </span>
                            </button>
                            <button type="button" id="ocf-v-40-2-4164180966-1" class="ocf-value ocf-checkbox"
                                    data-filter-key="40.2" data-value-id="4164180966">
                              <span class="ocf-value-color" style="background-color: #7a0026;"></span>

                              <span class="ocf-value-name">бордовий</span>
                              <span class="ocf-value-append">
    <span class="ocf-value-count">6</span>
  </span>
                            </button>
                            <button type="button" id="ocf-v-40-2-3003181703-1" class="ocf-value ocf-checkbox"
                                    data-filter-key="40.2" data-value-id="3003181703">
                              <span class="ocf-value-color" style="background-color: #ff0;"></span>

                              <span class="ocf-value-name">жовтий</span>
                              <span class="ocf-value-append">
    <span class="ocf-value-count">17</span>
  </span>
                            </button>
                            <button type="button" id="ocf-v-40-2-1087889661-1" class="ocf-value ocf-checkbox"
                                    data-filter-key="40.2" data-value-id="1087889661">
                              <span class="ocf-value-color" style="background-color: #045f20;"></span>

                              <span class="ocf-value-name">зелений</span>
                              <span class="ocf-value-append">
    <span class="ocf-value-count">39</span>
  </span>
                            </button>
                            <button type="button" id="ocf-v-40-2-1952877575-1" class="ocf-value ocf-checkbox"
                                    data-filter-key="40.2" data-value-id="1952877575">
                              <span class="ocf-value-color" style="background-color: #fdc68c;"></span>

                              <span class="ocf-value-name">золотистий</span>
                              <span class="ocf-value-append">
    <span class="ocf-value-count">12</span>
  </span>
                            </button>
                            <button type="button" id="ocf-v-40-2-2661832362-1" class="ocf-value ocf-checkbox"
                                    data-filter-key="40.2" data-value-id="2661832362">
                              <span class="ocf-value-color" style="background-color: #7c4900;"></span>

                              <span class="ocf-value-name">коричневий</span>
                              <span class="ocf-value-append">
    <span class="ocf-value-count">9</span>
  </span>
                            </button>
                            <button type="button" id="ocf-v-40-2-1310327613-1" class="ocf-value ocf-checkbox"
                                    data-filter-key="40.2" data-value-id="1310327613">
                              <span class="ocf-value-input ocf-value-input-checkbox"></span>

                              <span class="ocf-value-name">персиковий</span>
                              <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                            </button>
                            <button type="button" id="ocf-v-40-2-2827293321-1" class="ocf-value ocf-checkbox"
                                    data-filter-key="40.2" data-value-id="2827293321">
                              <span class="ocf-value-color" style="background-color: #f16c4d;"></span>

                              <span class="ocf-value-name">помаранчевий</span>
                              <span class="ocf-value-append">
    <span class="ocf-value-count">18</span>
  </span>
                            </button>
                            <button type="button" id="ocf-v-40-2-3801111943-1" class="ocf-value ocf-checkbox"
                                    data-filter-key="40.2" data-value-id="3801111943">
                              <span class="ocf-value-color" style="background-color: #ed008c;"></span>

                              <span class="ocf-value-name">рожевий</span>
                              <span class="ocf-value-append">
    <span class="ocf-value-count">10</span>
  </span>
                            </button>
                            <button type="button" id="ocf-v-40-2-2505256508-1" class="ocf-value ocf-checkbox"
                                    data-filter-key="40.2" data-value-id="2505256508">
                              <span class="ocf-value-color" style="background-color: #c2c2c2;"></span>

                              <span class="ocf-value-name">сірий</span>
                              <span class="ocf-value-append">
    <span class="ocf-value-count">68</span>
  </span>
                            </button>
                            <button type="button" id="ocf-v-40-2-1747800260-1" class="ocf-value ocf-checkbox"
                                    data-filter-key="40.2" data-value-id="1747800260">
                              <span class="ocf-value-color" style="background-color: #0f0;"></span>

                              <span class="ocf-value-name">салатовий</span>
                              <span class="ocf-value-append">
    <span class="ocf-value-count">7</span>
  </span>
                            </button>
                            <button type="button" id="ocf-v-40-2-4177059010-1" class="ocf-value ocf-checkbox"
                                    data-filter-key="40.2" data-value-id="4177059010">
                              <span class="ocf-value-color" style="background-color: #00f;"></span>

                              <span class="ocf-value-name">синій</span>
                              <span class="ocf-value-append">
    <span class="ocf-value-count">51</span>
  </span>
                            </button>
                            <button type="button" id="ocf-v-40-2-323495583-1" class="ocf-value ocf-checkbox"
                                    data-filter-key="40.2" data-value-id="323495583">
                              <span class="ocf-value-color" style="background-color: #acacac;"></span>

                              <span class="ocf-value-name">сріблястий</span>
                              <span class="ocf-value-append">
    <span class="ocf-value-count">6</span>
  </span>
                            </button>
                            <button type="button" id="ocf-v-40-2-953699298-1" class="ocf-value ocf-checkbox"
                                    data-filter-key="40.2" data-value-id="953699298">
                              <span class="ocf-value-color" style="background-color: #855fa8;"></span>

                              <span class="ocf-value-name">фіолетовий</span>
                              <span class="ocf-value-append">
    <span class="ocf-value-count">10</span>
  </span>
                            </button>
                            <button type="button" id="ocf-v-40-2-1770768745-1" class="ocf-value ocf-checkbox"
                                    data-filter-key="40.2" data-value-id="1770768745">
                              <span class="ocf-value-color" style="background-color: #005824;"></span>

                              <span class="ocf-value-name">хакі</span>
                              <span class="ocf-value-append">
    <span class="ocf-value-count">7</span>
  </span>
                            </button>
                            <button type="button" id="ocf-v-40-2-967242802-1" class="ocf-value ocf-checkbox"
                                    data-filter-key="40.2" data-value-id="967242802">
                              <span class="ocf-value-color" style="background-color: #8293ca;"></span>

                              <span class="ocf-value-name">хамелеон</span>
                              <span class="ocf-value-append">
    <span class="ocf-value-count">10</span>
  </span>
                            </button>
                            <button type="button" id="ocf-v-40-2-3618649264-1" class="ocf-value ocf-checkbox"
                                    data-filter-key="40.2" data-value-id="3618649264">
                              <span class="ocf-value-color" style="background-color: #f00;"></span>

                              <span class="ocf-value-name">червоний</span>
                              <span class="ocf-value-append">
    <span class="ocf-value-count">29</span>
  </span>
                            </button>
                            <button type="button" id="ocf-v-40-2-1336033264-1" class="ocf-value ocf-checkbox"
                                    data-filter-key="40.2" data-value-id="1336033264">
                              <span class="ocf-value-color" style="background-color: #000;"></span>

                              <span class="ocf-value-name">чорний</span>
                              <span class="ocf-value-append">
    <span class="ocf-value-count">47</span>
  </span>
                            </button>
                          </div>

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

                          <button type="button" id="ocf-v-86-2-4291261012-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="86.2" data-value-id="4291261012">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">5</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">7</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-86-2-4291261075-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="86.2" data-value-id="4291261075">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">8</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">88</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-86-2-537791324-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="86.2" data-value-id="537791324">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">15</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">5</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-86-2-1589606369-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="86.2" data-value-id="1589606369">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">18</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">53</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-86-2-2068425744-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="86.2" data-value-id="2068425744">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">20</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">48</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-86-2-4291261074-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="86.2" data-value-id="4291261074">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">22</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">88</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-86-2-186865823-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="86.2" data-value-id="186865823">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">25</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">209</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-86-2-1649581393-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="86.2" data-value-id="1649581393">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">30</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">31</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-86-2-4291261055-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="86.2" data-value-id="4291261055">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">48</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">16</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-86-2-872954583-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="86.2" data-value-id="872954583">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">50</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">12</span>
  </span>
                          </button>
                        </div>


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

                          <button type="button" id="ocf-v-82-2-3025868024-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="82.2" data-value-id="3025868024">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">6 років</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">2</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-82-2-2405354113-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="82.2" data-value-id="2405354113">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">близько 1 року</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">2</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-82-2-1375553005-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="82.2" data-value-id="1375553005">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">до 10 років</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">65</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-82-2-3328332996-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="82.2" data-value-id="3328332996">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">до 12 років</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">10</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-82-2-316554310-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="82.2" data-value-id="316554310">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">до 3 років</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-82-2-3615032008-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="82.2" data-value-id="3615032008">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">до 4 років</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">13</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-82-2-1896745340-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="82.2" data-value-id="1896745340">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">до 5 років</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">194</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-82-2-1088820193-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="82.2" data-value-id="1088820193">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">до 6 років</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">38</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-82-2-3868268629-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="82.2" data-value-id="3868268629">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">до 7 років</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">84</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-82-2-282683580-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="82.2" data-value-id="282683580">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">до 8 років</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">35</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-82-2-3064881928-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="82.2" data-value-id="3064881928">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">до 9 років</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                        </div>


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

                          <button type="button" id="ocf-v-80-2-990950172-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="80.2" data-value-id="990950172">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">від +10°C</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">2</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-80-2-3286201912-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="80.2" data-value-id="3286201912">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">від +10°C до +16°C</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">109</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-80-2-1505776235-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="80.2" data-value-id="1505776235">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">від +10°C до +38°C</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-80-2-214844206-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="80.2" data-value-id="214844206">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">від +15°C</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-80-2-3000949858-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="80.2" data-value-id="3000949858">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">від +15°C до +32°C</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">2</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-80-2-789177563-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="80.2" data-value-id="789177563">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">від +15°C до +35°C</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-80-2-4077324742-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="80.2" data-value-id="4077324742">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">від +17°C до +25°C</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-80-2-944014729-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="80.2" data-value-id="944014729">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">від +18°C до +22°C</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">159</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-80-2-2091960780-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="80.2" data-value-id="2091960780">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">від +20°C</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">8</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-80-2-3038645317-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="80.2" data-value-id="3038645317">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">від +4°C до +35°C</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-80-2-1198838936-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="80.2" data-value-id="1198838936">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">від +4°C до +38°C</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">3</span>
  </span>
                          </button>
                        </div>


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

                          <button type="button" id="ocf-v-79-2-2369956832-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="79.2" data-value-id="2369956832">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">50 мкр</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">5</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-79-2-373800497-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="79.2" data-value-id="373800497">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">80 мкр</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">105</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-79-2-3673013935-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="79.2" data-value-id="3673013935">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">90 мкр</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">159</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-79-2-1362899387-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="79.2" data-value-id="1362899387">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">100 мкр</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">12</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-79-2-3437153448-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="79.2" data-value-id="3437153448">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">100-140 мкр</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">2</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-79-2-3332579474-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="79.2" data-value-id="3332579474">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">102 мкр</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">2</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-79-2-3484294786-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="79.2" data-value-id="3484294786">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">115 мкр</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">2</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-79-2-135165174-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="79.2" data-value-id="135165174">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">119 мкр</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">4</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-79-2-320419526-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="79.2" data-value-id="320419526">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">120 мкр</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-79-2-557390674-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="79.2" data-value-id="557390674">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">144 мкр</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">39</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-79-2-433864671-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="79.2" data-value-id="433864671">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">150 мкр</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">51</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-79-2-1273237624-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="79.2" data-value-id="1273237624">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">155 мкр</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-79-2-2538813500-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="79.2" data-value-id="2538813500">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">160 мкр</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">2</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-79-2-3312129947-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="79.2" data-value-id="3312129947">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">165 мкр</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-79-2-1543059618-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="79.2" data-value-id="1543059618">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">170 мкр</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">2</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-79-2-1316170384-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="79.2" data-value-id="1316170384">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">190 мкр</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-79-2-1756433790-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="79.2" data-value-id="1756433790">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">200 мкр</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">10</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-79-2-714344963-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="79.2" data-value-id="714344963">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">220 мкр</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">18</span>
  </span>
                          </button>
                        </div>


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

                          <button type="button" id="ocf-v-48-2-3893068535-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="48.2" data-value-id="3893068535">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">Polyethylene-coated paper</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-48-2-3628321987-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="48.2" data-value-id="3628321987">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">ні</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">136</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-48-2-4190294876-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="48.2" data-value-id="4190294876">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">так</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">173</span>
  </span>
                          </button>
                        </div>


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

                          <button type="button" id="ocf-v-76-2-3162498025-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="76.2" data-value-id="3162498025">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">вініл</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">285</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-76-2-363803250-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="76.2" data-value-id="363803250">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">пвх</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">116</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-76-2-1710936836-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="76.2" data-value-id="1710936836">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">поліуретан</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">13</span>
  </span>
                          </button>
                        </div>


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

                          <button type="button" id="ocf-v-52-2-1593361480-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="52.2" data-value-id="1593361480">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">ні</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-52-2-4264491918-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="52.2" data-value-id="4264491918">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">так</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">56</span>
  </span>
                          </button>
                        </div>


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

                          <button type="button" id="ocf-v-37-2-184593436-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="37.2" data-value-id="184593436">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">антивандальна</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">2</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-37-2-991484711-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="37.2" data-value-id="991484711">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">антигравійна</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">15</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-37-2-4174069027-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="37.2" data-value-id="4174069027">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">декоративна</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">401</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-37-2-681045854-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="37.2" data-value-id="681045854">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">тонувальна</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">27</span>
  </span>
                          </button>
                        </div>


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

                          <button type="button" id="ocf-v-60-2-3280879489-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="60.2" data-value-id="3280879489">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">5%</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">3</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-60-2-1981825484-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="60.2" data-value-id="1981825484">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">8%</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">2</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-60-2-868632715-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="60.2" data-value-id="868632715">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">10%</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-60-2-1320271054-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="60.2" data-value-id="1320271054">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">15%</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">2</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-60-2-830535378-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="60.2" data-value-id="830535378">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">20%</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">5</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-60-2-447587601-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="60.2" data-value-id="447587601">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">23%</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-60-2-1291256471-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="60.2" data-value-id="1291256471">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">25%</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-60-2-809671909-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="60.2" data-value-id="809671909">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">30%</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-60-2-41221735-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="60.2" data-value-id="41221735">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">32%</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-60-2-1295345824-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="60.2" data-value-id="1295345824">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">35%</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">3</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-60-2-890079840-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="60.2" data-value-id="890079840">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">40%</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-60-2-886038615-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="60.2" data-value-id="886038615">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">50%</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-60-2-927675449-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="60.2" data-value-id="927675449">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">70%</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">3</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-60-2-776996216-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="60.2" data-value-id="776996216">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">71%</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-60-2-1935074755-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="60.2" data-value-id="1935074755">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">87%</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">2</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-60-2-1037375795-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="60.2" data-value-id="1037375795">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">90%</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">2</span>
  </span>
                          </button>
                        </div>


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

                          <button type="button" id="ocf-v-77-2-1712980415-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="77.2" data-value-id="1712980415">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">3M Automotive</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-77-2-1138822514-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="77.2" data-value-id="1138822514">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">5G Constant Color Series</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">4</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-77-2-3908184965-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="77.2" data-value-id="3908184965">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">5G Skin Care High Heat Insulation Series</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-77-2-3399226577-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="77.2" data-value-id="3399226577">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">1080</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">72</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-77-2-3007360117-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="77.2" data-value-id="3007360117">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">1089</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-77-2-3626613567-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="77.2" data-value-id="3626613567">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">2080</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">86</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-77-2-1889331932-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="77.2" data-value-id="1889331932">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">8900</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-77-2-1222677911-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="77.2" data-value-id="1222677911">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">Bodyfence</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-77-2-440190307-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="77.2" data-value-id="440190307">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">Color</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">5</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-77-2-3963846657-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="77.2" data-value-id="3963846657">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">Commercial Graphics</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">7</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-77-2-2799889186-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="77.2" data-value-id="2799889186">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">Crystalline</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">5</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-77-2-2398354367-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="77.2" data-value-id="2398354367">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">Crystalline Series</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-77-2-3473225001-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="77.2" data-value-id="3473225001">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">FX-HP</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">4</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-77-2-1988165346-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="77.2" data-value-id="1988165346">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span
                              class="ocf-value-name">Magnetron Double Silver Skin Care High Heat Insulation Series</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-77-2-4282639623-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="77.2" data-value-id="4282639623">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">Magnetron Skin Care High Heat Insulation Series</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-77-2-204960888-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="77.2" data-value-id="204960888">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">New Energy 5G High Heat Insulation Series</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-77-2-609157094-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="77.2" data-value-id="609157094">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">PPF</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">6</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-77-2-2415174919-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="77.2" data-value-id="2415174919">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">Shade</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">3</span>
  </span>
                          </button>
                        </div>


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

                          <button type="button" id="ocf-v-46-2-4020409676-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="46.2" data-value-id="4020409676">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">вологий</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">13</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-46-2-797692996-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="46.2" data-value-id="797692996">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">сухий</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">400</span>
  </span>
                          </button>
                        </div>


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

                          <button type="button" id="ocf-v-39-2-2911762373-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="39.2" data-value-id="2911762373">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">глянцева</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">249</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-39-2-2156173017-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="39.2" data-value-id="2156173017">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">матова</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">77</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-39-2-104821101-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="39.2" data-value-id="104821101">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">сатинова</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">70</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-39-2-4120922725-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="39.2" data-value-id="4120922725">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">структурна</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">19</span>
  </span>
                          </button>
                        </div>


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

                          <button type="button" id="ocf-v-53-2-3368032136-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="53.2" data-value-id="3368032136">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">від -20°C до +120°C</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">2</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-53-2-2084967861-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="53.2" data-value-id="2084967861">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">від -40°C до +107°C</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">2</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-53-2-339480683-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="53.2" data-value-id="339480683">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">від -40°C до +90°C</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">29</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-53-2-1922667821-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="53.2" data-value-id="1922667821">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">від -50°C до +110°C</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">109</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-53-2-892989437-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="53.2" data-value-id="892989437">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">від -50°C до +120°C</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">4</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-53-2-2915552131-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="53.2" data-value-id="2915552131">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">від -50°C до +90°C</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-53-2-3272819126-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="53.2" data-value-id="3272819126">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">від -53°C до +93°C</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">2</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-53-2-1481826631-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="53.2" data-value-id="1481826631">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">від -54°C до +107°C</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">84</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-53-2-2632630797-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="53.2" data-value-id="2632630797">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">від -55°C до +65°C</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-53-2-4226166998-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="53.2" data-value-id="4226166998">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">від -60°C до +107°C</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">77</span>
  </span>
                          </button>
                        </div>


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

                          <button type="button" id="ocf-v-59-2-2931280339-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="59.2" data-value-id="2931280339">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">вініл</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">97</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-59-2-784695848-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="59.2" data-value-id="784695848">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">лита плівка пвх</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">20</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-59-2-2115891095-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="59.2" data-value-id="2115891095">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">литий вініл</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">275</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-59-2-3799582808-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="59.2" data-value-id="3799582808">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">пвх</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">9</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-59-2-1425414317-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="59.2" data-value-id="1425414317">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">поліуретан</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">12</span>
  </span>
                          </button>
                        </div>


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

                          <button type="button" id="ocf-v-38-2-816167911-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="38.2" data-value-id="816167911">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">атермальна</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">24</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-38-2-1965124537-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="38.2" data-value-id="1965124537">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">декоративна</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">1</span>
  </span>
                          </button>
                          <button type="button" id="ocf-v-38-2-3214384054-1" class="ocf-value ocf-checkbox"
                                  data-filter-key="38.2" data-value-id="3214384054">
                            <span class="ocf-value-input ocf-value-input-checkbox"></span>

                            <span class="ocf-value-name">металізована</span>
                            <span class="ocf-value-append">
    <span class="ocf-value-count">4</span>
  </span>
                          </button>
                        </div>


                      </div>
                    </div>
                  </div>
                </div>
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

        <div class="category-right-top flex-justify"
             data-background="catalog/view/theme/wrapshop/image/icon/head-top.png"
             style="background-image: url(&quot;catalog/view/theme/wrapshop/image/icon/head-top.png&quot;);">
          <div class="category-sort flex-justify">
            <div class="label">Сортувати за:</div>
            <div class="category-sort-close button"><i class="fal fa-times"></i></div>
            <div class="list">
              <a href="https://wrap.shop/plivky/?sort=p.sort_order&amp;order=ASC" class="button active"
                 title="Замовчуванням">Замовчуванням</a>
              <a href="https://wrap.shop/plivky/?sort=pd.name&amp;order=ASC" class="button"
                 title="Алфавітом">Алфавітом</a>
              <a href="https://wrap.shop/plivky/?sort=p.price&amp;order=ASC" class="button" title="Зростанням ціни">Зростанням
                ціни</a>
              <a href="https://wrap.shop/plivky/?sort=p.price&amp;order=DESC" class="button" title="Спаданням ціни">Спаданням
                ціни</a>
              <a href="https://wrap.shop/plivky/?sort=rating&amp;order=ASC" class="button" title="Найпопулярнішими">Найпопулярнішими</a>
              <a href="https://wrap.shop/plivky/?sort=p.date_available&amp;order=ASC" class="button"
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
          @foreach($products as $product)
            <div class="category-products-item product-default product-layout product-grid"
                 id="categoryProductsItem{{$product->id}}">
              <div class="top flex-justify">
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
              <div class="sale statuses">
                <div class="category-status category-status-1 status-inline text rectangle "
                     style=" color:#ffffff; background-color:#d04b4b;">
                  {{__('general-translate.sales_hit')}}
                </div>
              </div>
              <div class="image swiper swiper-initialized swiper-horizontal swiper-android swiper-backface-hidden">
                <i class="far fa-search-plus colord" data-src="{{$product->getMedia('images')->first()->getUrl()}}"
                   data-fancybox="gallery{{$product->id}}" data-caption="{{$product->name}}"></i>
                <a href="{{route('products.show', ['product' => $product->id])}}" title="{{$product->name}}"
                   class="swiper-wrapper">

                  @foreach($product->getMedia('images') as $key => $image)
                    <div class="swiper-slide item flex-center swiper-slide-active" role="group">

                      @if($key>0)
                        <div class="hide"
                             data-src="{{$image->getUrl()}}"
                             data-fancybox="gallery{{$product->id}}"
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
                <div class="swiper-button-next button" tabindex="0" role="button" aria-label="Next slide"></div>
                <div class="swiper-button-prev button" tabindex="-1" role="button" aria-label="Previous slide"></div>
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
                <div class="category">{{$product->categories->value('name')}}</div>
                <a href="{{route('products.show', ['product' => $product->id])}}" title="{{$product->name}}"
                   class="name">{{$product->name}}</a>
              </div>
              <div class="bottom flex-center">
                <div class="price">{{number_format($product->prices->where('price_type_id', 2)->first()->price, 2, '.', '')}} ₴<span
                    class="price-unit-xvr"></span></div>
                <button class="button colord remarketing_cart_button" data-product_id="{{$product->id}}"><i
                    class="fas fa-chevron-right"></i>{{__('general-translate.product_card.add_to_cart')}}</button>
              </div>
              <div class="params">
                <div class="item flex-column">Призначення <span class="label">декоративна</span></div>
                <div class="item flex-column">Структура <span class="label">сатинова</span></div>
              </div>
            </div>

          @endforeach

          {{--          <a href="https://wrap.shop/plivky/kolorovi-plivky/?ocf=F1S0V11" title="3m color" class="category-products-item product-default product-layout banner product-grid">--}}
          {{--            <img class="vertical" data-src="/image/catalog/category/webp/banners catalog/1-3m-vert.webp" alt="3m color" title="3m color" src="/image/catalog/category/webp/banners catalog/1-3m-vert.webp">--}}
          {{--            <img class="gorizont" data-src="/image/catalog/category/webp/banners catalog/1-3m-gor.webp" alt="3m color" title="3m color">--}}
          {{--          </a>--}}


        </div>

        <div class="category-bottom flex-center">
          <div class="category-pagination">
            @if ($products->hasPages())
              <ul class="flex-center pagination">
                @if ($products->currentPage() > 3)
                  <li><a href="{{ $products->url(1) }}">1</a></li>
                  <li class="disabled"><span>&hellip;</span></li>
                @endif
                @for ($i = max($products->currentPage() - 2, 1); $i <= min($products->currentPage() + 2, $products->lastPage()); $i++)
                  @if ($i == $products->currentPage())
                    <li class="active"><span>{{$i}}</span></li>
                  @else
                    <li><a href="{{ $products->url($i) }}">{{ $i }}</a></li>
                  @endif
                @endfor
                @if ($products->currentPage() < $products->lastPage() - 2)
                  <li class="disabled"><span>&hellip;</span></li>
                  <li><a href="{{ $products->url($products->lastPage()) }}">{{ $products->lastPage() }}</a></li>
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
    <script>
      let categoryProductsItem = new Swiper(".category-products-item .image", {
        navigation: {
          nextEl: ".category-products-item .image .swiper-button-next",
          prevEl: ".category-products-item .image .swiper-button-prev",
        },
        lazy: true,
      });
    </script>
  @endpush
@endsection
