<header class="header ">
  <div class="head-top row">
    <div class="head-top-wrap flex-justify wrap">
      <div class="head-lang flex-center" id="form-language">
        <a href="{{ LaravelLocalization::getLocalizedURL('uk') }}"
           class="button language-select {{LaravelLocalization::getCurrentLocale() === 'uk' ? 'active' : ''}}">Ua</a>
        <a href="{{ LaravelLocalization::getLocalizedURL('ru') }}"
           class="button language-select {{LaravelLocalization::getCurrentLocale() === 'ru' ? 'active' : ''}}">Ru</a>
        <a href="{{ LaravelLocalization::getLocalizedURL('en') }}"
           class="button language-select {{LaravelLocalization::getCurrentLocale() === 'en' ? 'active' : ''}}">En</a>
          <input type="hidden" name="code" value="">
          <meta name="csrf-token" content="{{ csrf_token() }}">

      </div>

      <div class="head-theme" style="padding: 0; background-color: transparent"></div>

      {{--      <form class="head-theme flex-center" action="http://localhost:8888/index.php?route=common/theme/theme"--}}
      {{--            method="post" enctype="multipart/form-data" id="form-theme">--}}
      {{--        <button class="button theme-select  fas fa-sun" type="button" name="light"></button>--}}
      {{--        <button class="button theme-select  active  far fa-moon" type="button" name="dark"></button>--}}
      {{--        <input type="hidden" name="code" value="">--}}
      {{--        <input type="hidden" name="redirect_route" value="http://localhost:8888/">--}}
      {{--      </form>--}}
      <nav class="head-menu">
        @include('base.layouts.menu')
      </nav>
      <div class="head-social flex-center">
        <a href="##" title="Telegram" target="_blank" class="fab fa-telegram-plane"></a>
        <a href="##" title="Instagram" target="_blank" class="fab fa-instagram"></a>
      </div>
      <div class="head-phone flex-center">
        <div class="title">{{__('header_footer.phone')}}</div>
        <a href="tel:+380660003202"><span>+38</span> 066 000 32 02</a>
      </div>
      <div class="head-top-close button"><i class="fal fa-times"></i></div>
    </div>
  </div>


  {{--  <div class="head-top row">--}}
  {{--    <div class="head-top-wrap flex-justify wrap">--}}
  {{--      <form class="head-lang flex-center" action="https://wrap.shop/index.php?route=common/language/language" method="post" enctype="multipart/form-data" id="form-language">--}}
  {{--        <button class="button language-select active" type="button" name="uk-ua">Ua</button>--}}
  {{--        <button class="button language-select" type="button" name="ru-ru">Ru</button>--}}
  {{--        <button class="button language-select" type="button" name="en-gb">En</button>--}}
  {{--        <input type="hidden" name="code" value="">--}}

  {{--        <input type="hidden" name="redirect_route" value="extension/ocdevwizard/blog/category">--}}
  {{--        <input type="hidden" name="redirect_query" value="&amp;ocdw_blog_path=12">--}}
  {{--        <input type="hidden" name="redirect_ssl" value="1">--}}
  {{--      </form>--}}
  {{--      <div class="head-lang flex-center">--}}
  {{--                <a href="{{ LaravelLocalization::getLocalizedURL('uk') }}" class="button language-select {{LaravelLocalization::getCurrentLocale() === 'uk' ? 'active' : ''}}">Ua</a>--}}
  {{--                <a href="{{ LaravelLocalization::getLocalizedURL('ru') }}" class="button language-select {{LaravelLocalization::getCurrentLocale() === 'ru' ? 'active' : ''}}">Ru</a>--}}
  {{--                <a href="{{ LaravelLocalization::getLocalizedURL('en') }}" class="button language-select {{LaravelLocalization::getCurrentLocale() === 'en' ? 'active' : ''}}">En</a>--}}
  {{--                <input type="hidden" name="code" value="">--}}
  {{--              </div>--}}
  {{--      <form class="head-theme flex-center" action="https://wrap.shop/index.php?route=common/theme/theme" method="post" enctype="multipart/form-data" id="form-theme">--}}
  {{--        <button class="button theme-select  fas fa-sun" type="button" name="light"></button>--}}
  {{--        <button class="button theme-select  active  far fa-moon" type="button" name="dark"></button>--}}
  {{--        <input type="hidden" name="code" value="">--}}
  {{--        <input type="hidden" name="redirect_route" value="https://wrap.shop/pro-wrap-shop/">--}}
  {{--      </form>--}}
  {{--      <nav class="head-menu">--}}
  {{--        <ul class="menu">--}}

  {{--          <li><a href="https://wrap.shop/videoogljadi/" title="Відеоогляди">Відеоогляди</a></li>--}}

  {{--          <li><a href="https://wrap.shop/dostavka-ta-oplata/" title="Доставка">Доставка</a></li>--}}

  {{--          <li><a href="https://wrap.shop/novini-ta-akcii/" title="Новини та акції">Новини та акції</a></li>--}}

  {{--          <li class="active"><a href="https://wrap.shop/pro-wrap-shop/" title="Про нас">Про нас</a></li>--}}

  {{--          <li><a href="https://wrap.shop/kontakty/" title="Контакти">Контакти</a></li>--}}

  {{--          <li><a href="https://wrap.shop/privacy-policy/" title="Оферта">Оферта</a></li>--}}
  {{--        </ul>--}}
  {{--      </nav>--}}
  {{--      <div class="head-social flex-center">--}}
  {{--        <a href="##" title="Telegram" target="_blank" class="fab fa-telegram-plane"></a>--}}
  {{--        <a href="##" title="Instagram" target="_blank" class="fab fa-instagram"></a>--}}
  {{--      </div>--}}
  {{--      <div class="head-phone flex-center">--}}
  {{--        <div class="title">Телефон</div>--}}
  {{--        <a href="tel:+380660003202"><span>+38</span> 066 000 32 02</a>--}}
  {{--      </div>--}}
  {{--      <div class="head-top-close button"><i class="fal fa-times"></i></div>--}}
  {{--    </div>--}}
  {{--  </div>--}}


  <div class="fixed-header">
    <div class="head-boot flex-justify wrap">
      <div class="head-catalog-open button">
        <div class="butt-menu"><span></span></div>
        {{__('header_footer.catalog_products')}}
      </div>
      <div class="head-catalog-bg"></div>
      <nav class="head-catalog">
        <div class="head-catalog-close button"><i class="far fa-times"></i></div>
        <ul class="menu">
          <li class="item parrent flex-all">
            <a href="http://localhost:8888/plivky/" title="Плівки">Плівки</a>
            <div class="child-popup-open open"></div>
            <div class="child-popup">
              <ul class="child-menu">
                <li class="item parrent flex-all">
                  <a href="http://localhost:8888/plivky/kolorovi-plivky/" title="Кольорові плівки">Кольорові плівки</a>
                </li>
                <li class="item parrent flex-all">
                  <a href="http://localhost:8888/plivky/zahysni-plivky/" title="Захисні плівки">Захисні плівки</a>
                </li>
                <li class="item parrent flex-all">
                  <a href="http://localhost:8888/plivky/plivka-dlya-avtomobilnyh-vikon/"
                     title="Плівка для автомобільних вікон">Плівка для автомобільних вікон</a>
                  <div class="child-popup-open"></div>
                  <div class="child-popup">
                    <ul class="child-menu">
                      <li><a href="http://localhost:8888/plivky/plivka-dlya-avtomobilnyh-vikon/antyvandalna-3m/"
                             title="Антивандальна 3M">Антивандальна 3M</a></li>
                      <li><a href="http://localhost:8888/plivky/plivka-dlya-avtomobilnyh-vikon/zahysna-clearplex/"
                             title="Захисна ClearPlex">Захисна ClearPlex</a></li>
                      <li><a href="http://localhost:8888/plivky/plivka-dlya-avtomobilnyh-vikon/tonuvalna-3m/"
                             title="Тонувальна 3M">Тонувальна 3M</a></li>
                    </ul>
                  </div>
                </li>
                <li class="item parrent flex-all">
                  <a href="http://localhost:8888/plivky/gotovi-dyzajny-ta-plivka-dlya-druku/"
                     title="Готові дизайни та плівка для друку">Готові дизайни та плівка для друку</a>
                  <div class="child-popup-open"></div>
                  <div class="child-popup">
                    <ul class="child-menu">
                      <li><a href="http://localhost:8888/plivky/gotovi-dyzajny-ta-plivka-dlya-druku/auto-design/"
                             title="AUTO Design">AUTO Design</a></li>
                      <li><a href="http://localhost:8888/plivky/gotovi-dyzajny-ta-plivka-dlya-druku/camo/" title="CAMO">CAMO</a>
                      </li>
                      <li><a
                          href="http://localhost:8888/plivky/gotovi-dyzajny-ta-plivka-dlya-druku/race-car-livery-design/"
                          title="Race Car Livery Design">Race Car Livery Design</a></li>
                      <li><a
                          href="http://localhost:8888/plivky/gotovi-dyzajny-ta-plivka-dlya-druku/materialy-dlya-druku/"
                          title="Матеріали для друку">Матеріали для друку</a></li>
                    </ul>
                  </div>
                </li>
                <li class="item parrent flex-all">
                  <a href="http://localhost:8888/plivky/lekala-dlya-salona/" title="Лекала для салона">Лекала для
                    салона</a>
                </li>
              </ul>
            </div>
          </li>
          <li class="item parrent flex-all">
            <a href="http://localhost:8888/materialy-dlya-shumoizolyaciyi-avto/"
               title="Матеріали для шумоізоляції авто">Матеріали для шумоізоляції авто</a>
            <div class="child-popup-open open"></div>
            <div class="child-popup">
              <ul class="child-menu">
                <li class="item parrent flex-all">
                  <a href="http://localhost:8888/materialy-dlya-shumoizolyaciyi-avto/antyskryp/" title="Антискрип">Антискрип</a>
                </li>
                <li class="item parrent flex-all">
                  <a href="http://localhost:8888/materialy-dlya-shumoizolyaciyi-avto/bagatosharovi-materialy/"
                     title="Багатошарові матеріали">Багатошарові матеріали</a>
                </li>
                <li class="item parrent flex-all">
                  <a href="http://localhost:8888/materialy-dlya-shumoizolyaciyi-avto/vibro-izolyaciya/"
                     title="Вібро ізоляція">Вібро ізоляція</a>
                </li>
                <li class="item parrent flex-all">
                  <a href="http://localhost:8888/materialy-dlya-shumoizolyaciyi-avto/mastyka/"
                     title="Мастика">Мастика</a>
                </li>
                <li class="item parrent flex-all">
                  <a href="http://localhost:8888/materialy-dlya-shumoizolyaciyi-avto/shumo-izolyaciya/"
                     title="Шумо ізоляція">Шумо ізоляція</a>
                </li>
                <li class="item parrent flex-all">
                  <a href="http://localhost:8888/materialy-dlya-shumoizolyaciyi-avto/shumopoglynayuchi-materialy/"
                     title="Шумопоглинаючі матеріали">Шумопоглинаючі матеріали</a>
                </li>
              </ul>
            </div>
          </li>
          <li class="item parrent flex-all">
            <a href="http://localhost:8888/materialy-dlya-detejlingu-avto/" title="Матеріали для детейлінгу авто">Матеріали
              для детейлінгу авто</a>
            <div class="child-popup-open open"></div>
            <div class="child-popup">
              <ul class="child-menu">
                <li class="item parrent flex-all">
                  <a href="http://localhost:8888/materialy-dlya-detejlingu-avto/zahysni-pokryttya/"
                     title="Захисні покриття">Захисні покриття</a>
                  <div class="child-popup-open"></div>
                  <div class="child-popup">
                    <ul class="child-menu">
                      <li><a href="http://localhost:8888/materialy-dlya-detejlingu-avto/zahysni-pokryttya/dlya-gumy/"
                             title="Для гуми">Для гуми</a></li>
                      <li><a href="http://localhost:8888/materialy-dlya-detejlingu-avto/zahysni-pokryttya/dlya-dyskiv/"
                             title="Для дисків">Для дисків</a></li>
                      <li><a
                          href="http://localhost:8888/materialy-dlya-detejlingu-avto/zahysni-pokryttya/dlya-kuzovu-avto/"
                          title="Для кузову авто">Для кузову авто</a></li>
                      <li><a
                          href="http://localhost:8888/materialy-dlya-detejlingu-avto/zahysni-pokryttya/dlya-plastyku/"
                          title="Для пластику">Для пластику</a></li>
                      <li><a href="http://localhost:8888/materialy-dlya-detejlingu-avto/zahysni-pokryttya/dlya-skla/"
                             title="Для скла">Для скла</a></li>
                      <li><a href="http://localhost:8888/materialy-dlya-detejlingu-avto/zahysni-pokryttya/dlya-tkanyny/"
                             title="Для тканини">Для тканини</a></li>
                      <li><a href="http://localhost:8888/materialy-dlya-detejlingu-avto/zahysni-pokryttya/dlya-shkiry/"
                             title="Для шкіри">Для шкіри</a></li>
                    </ul>
                  </div>
                </li>
                <li class="item parrent flex-all">
                  <a href="http://localhost:8888/materialy-dlya-detejlingu-avto/salon/" title="Салон">Салон</a>
                  <div class="child-popup-open"></div>
                  <div class="child-popup">
                    <ul class="child-menu">
                      <li><a href="http://localhost:8888/materialy-dlya-detejlingu-avto/salon/zasoby-dlya-plastyku/"
                             title="Засоби для пластику">Засоби для пластику</a></li>
                      <li><a href="http://localhost:8888/materialy-dlya-detejlingu-avto/salon/zasoby-dlya-shkiry/"
                             title="Засоби для шкіри">Засоби для шкіри</a></li>
                      <li><a href="http://localhost:8888/materialy-dlya-detejlingu-avto/salon/himchystka-salona/"
                             title="Хімчистка салона">Хімчистка салона</a></li>
                    </ul>
                  </div>
                </li>
                <li class="item parrent flex-all">
                  <a href="http://localhost:8888/materialy-dlya-detejlingu-avto/zovnishnij-vyglyad-avto/"
                     title="Зовнішній вигляд авто">Зовнішній вигляд авто</a>
                  <div class="child-popup-open"></div>
                  <div class="child-popup">
                    <ul class="child-menu">
                      <li><a
                          href="http://localhost:8888/materialy-dlya-detejlingu-avto/zovnishnij-vyglyad-avto/antybitum-ta-moshka/"
                          title="Антибітум та мошка">Антибітум та мошка</a></li>
                      <li><a
                          href="http://localhost:8888/materialy-dlya-detejlingu-avto/zovnishnij-vyglyad-avto/znezhyryuvachi/"
                          title="Знежирювачі">Знежирювачі</a></li>
                      <li><a
                          href="http://localhost:8888/materialy-dlya-detejlingu-avto/zovnishnij-vyglyad-avto/ochysnyky/"
                          title="Очисники">Очисники</a></li>
                      <li><a
                          href="http://localhost:8888/materialy-dlya-detejlingu-avto/zovnishnij-vyglyad-avto/shampun-ta-pina-dlya-myjky/"
                          title="Шампунь та піна для мийки">Шампунь та піна для мийки</a></li>
                    </ul>
                  </div>
                </li>
                <li class="item parrent flex-all">
                  <a href="http://localhost:8888/materialy-dlya-detejlingu-avto/poliruvannya/" title="Полірування">Полірування</a>
                  <div class="child-popup-open"></div>
                  <div class="child-popup">
                    <ul class="child-menu">
                      <li><a href="http://localhost:8888/materialy-dlya-detejlingu-avto/poliruvannya/krugy/"
                             title="Круги">Круги</a></li>
                      <li><a href="http://localhost:8888/materialy-dlya-detejlingu-avto/poliruvannya/pasty/"
                             title="Пасти">Пасти</a></li>
                    </ul>
                  </div>
                </li>
                <li class="item parrent flex-all">
                  <a href="http://localhost:8888/materialy-dlya-detejlingu-avto/aksesuary/"
                     title="Аксесуари">Аксесуари</a>
                  <div class="child-popup-open"></div>
                  <div class="child-popup">
                    <ul class="child-menu">
                      <li><a href="http://localhost:8888/materialy-dlya-detejlingu-avto/aksesuary/aplikatory/"
                             title="Аплікатори">Аплікатори</a></li>
                      <li><a href="http://localhost:8888/materialy-dlya-detejlingu-avto/aksesuary/mikrofibry/"
                             title="Мікрофібри">Мікрофібри</a></li>
                      <li><a href="http://localhost:8888/materialy-dlya-detejlingu-avto/aksesuary/shchitky/"
                             title="Щітки">Щітки</a></li>
                    </ul>
                  </div>
                </li>
              </ul>
            </div>
          </li>
          <li class="item"><a href="http://localhost:8888/zahysne-sklo-dlya-multymedia-avto/"
                              title="Захисне скло для мультимедіа авто">Захисне скло для мультимедіа авто</a></li>
          <li class="item parrent flex-all">
            <a href="http://localhost:8888/instrumenty-rozhidnyky/" title="Інструменти / розхідники">Інструменти /
              розхідники</a>
            <div class="child-popup-open open"></div>
            <div class="child-popup">
              <ul class="child-menu">
                <li class="item parrent flex-all">
                  <a href="http://localhost:8888/instrumenty-rozhidnyky/leza-ta-nozhi/" title="Леза та ножі">Леза та
                    ножі</a>
                </li>
                <li class="item parrent flex-all">
                  <a href="http://localhost:8888/instrumenty-rozhidnyky/rakelya-vygonky/" title="Ракеля / Вигонки">Ракеля
                    / Вигонки</a>
                </li>
                <li class="item parrent flex-all">
                  <a href="http://localhost:8888/instrumenty-rozhidnyky/rozpylyuvachi/"
                     title="Розпилювачі">Розпилювачі</a>
                </li>
                <li class="item parrent flex-all">
                  <a href="http://localhost:8888/instrumenty-rozhidnyky/skotch/" title="Скотч">Скотч</a>
                </li>
                <li class="item parrent flex-all">
                  <a href="http://localhost:8888/instrumenty-rozhidnyky/rukavychky-ta-sumky/"
                     title="Рукавички та сумки">Рукавички та сумки</a>
                </li>
                <li class="item parrent flex-all">
                  <a href="http://localhost:8888/instrumenty-rozhidnyky/gel-dlya-oklejky-plivky/"
                     title="Гель для оклейки плівки">Гель для оклейки плівки</a>
                </li>
                <li class="item parrent flex-all">
                  <a href="http://localhost:8888/instrumenty-rozhidnyky/antystatychni-servetky/"
                     title="Антистатичні серветки">Антистатичні серветки</a>
                </li>
                <li class="item parrent flex-all">
                  <a href="http://localhost:8888/instrumenty-rozhidnyky/profesijni-elektroprylady/"
                     title="Професійні електроприлади">Професійні електроприлади</a>
                </li>
              </ul>
            </div>
          </li>
          <li class="item"><a href="http://localhost:8888/avtomobilni-kylymy-hand-made/"
                              title="Автомобільні килими Hand made">Автомобільні килими Hand made</a></li>
          <li class="item"><a href="http://localhost:8888/merch/" title="Merch">Merch</a></li>
          <li class="item parrent flex-all">
            <a href="http://localhost:8888/tyuning/" title="Тюнінг">Тюнінг</a>
            <div class="child-popup-open open"></div>
            <div class="child-popup">
              <ul class="child-menu">
                <li class="item parrent flex-all">
                  <a href="http://localhost:8888/tyuning/avto-obvisy/" title="Авто обвіси">Авто обвіси</a>
                </li>
                <li class="item parrent flex-all">
                  <a href="http://localhost:8888/tyuning/detali-salonu/" title="Деталі салону">Деталі салону</a>
                </li>
                <li class="item parrent flex-all">
                  <a href="http://localhost:8888/tyuning/dysky/" title="Диски">Диски</a>
                </li>
                <li class="item parrent flex-all">
                  <a href="http://localhost:8888/tyuning/spojlery/" title="Спойлери">Спойлери</a>
                </li>
              </ul>
            </div>
          </li>
        </ul>
      </nav>
      <div class="head-logo">
        <a href="{{route('index')}}" title="Wrap.Shop">
          <img loading="lazy" src="{{asset('assets/img/logo.png')}}"
               title="Wrap.Shop" alt="Wrap.Shop" width="93"
               height="98"></a>
      </div>
      <div class="head-phone-mobil">
        <div class="title">{{__('header_footer.phone')}}</div>
        <a href="tel:+380660003202"><span>+38</span> 066 000 32 02</a>
      </div>
      <div class="head-buttons">

        <div class="search-popup-open button search far fa-search"></div>
        <button class="button cart-open far fa-shopping-cart dropdown-toggle"><span
            id="cart-total" class="cart-total flex-center">0</span></button>
          @guest
        <div id="wishlist-total" class="button heart login-show far fa-heart login-popup-open"><span
            class="wishlist-total flex-center">0</span></div>
        <div class="button userform-open login-show far fa-user login-popup-open"></div>
          @endguest

          @auth
        <a href="{{route('wishlist')}}" id="wishlist-total" title="Закладки (0)"
           class="button heart far fa-heart"><span class="wishlist-total flex-center">0</span></a>
        <a href="{{route('account')}}" title="Особистий кабінет" class="button user far fa-user-check"></a>
          @endauth
        <div class="head-top-open button"><i class="far fa-bars"></i></div>

      </div>
    </div>
  </div>
</header>
