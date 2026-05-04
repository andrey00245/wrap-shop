@php
    $homeColorFilmsCards = collect($homeColorFilmsCards ?? [
        ['image' => asset('assets/img/home/color-films/film-green.png'), 'alt' => 'Color film green', 'brand' => '3M', 'name' => '2080-S261 SATIN DARK GRAY'],
        ['image' => asset('assets/img/home/color-films/film-blue.png'), 'alt' => 'Color film blue', 'brand' => '3M', 'name' => '2080-S261 SATIN DARK GRAY'],
        ['image' => asset('assets/img/home/color-films/film-texture.png'), 'alt' => 'Color film texture', 'brand' => 'HEXIS', 'name' => 'HX30CAF89S CAMO FILM'],
        ['image' => asset('assets/img/home/color-films/film-red.png'), 'alt' => 'Color film red', 'brand' => '3M', 'name' => '2080-S261 SATIN DARK GRAY'],
        ['image' => asset('assets/img/home/color-films/film-purple.png'), 'alt' => 'Color film purple', 'brand' => '3M', 'name' => '2080-S261 SATIN DARK GRAY'],
    ])->filter(fn ($item) => !empty($item['image']) && !empty($item['name']))->values();
@endphp

@if($homeColorFilmsCards->isNotEmpty())
    <section class="home-color-films row" id="homeColorFilms">
        <div class="wrap">
            <div class="home-color-films__head flex-justify">
                <h2 class="home-title"><span class="colord">КОЛЬОРОВІ</span> ПЛІВКИ</h2>
            </div>

            <div class="home-color-films__tabs home-products-nav">
                <button type="button" class="item button all active">Всі</button>
                <button type="button" class="item button">Глянцева</button>
                <button type="button" class="item button">Матова</button>
                <button type="button" class="item button">Сатинова</button>
                <button type="button" class="item button">Структура</button>
            </div>

            <div class="splide home-color-films__list">
                <div class="splide__arrows home-products-slide-buttons">
                    <div class="line"></div>
                </div>
                <div class="splide__track">
                    <ul class="splide__list">
                        @foreach($homeColorFilmsCards as $card)
                            <li class="splide__slide home-color-films__slide">
                                <article class="home-color-films__card">
                                    <div class="home-color-films__image">
                                        <img src="{{ $card['image'] }}" alt="{{ $card['alt'] ?? $card['name'] }}">
                                    </div>
                                    <div class="home-color-films__meta">
                                        <div class="home-color-films__text">
                                            <div class="home-color-films__brand">{{ $card['brand'] ?? '' }}</div>
                                            <div class="home-color-films__name">{{ $card['name'] }}</div>
                                        </div>
                                        <svg class="home-color-films__arrow" viewBox="0 0 14 14" aria-hidden="true" focusable="false">
                                            <path d="M3 11L11 3"></path>
                                            <path d="M5 3H11V9"></path>
                                        </svg>
                                    </div>
                                </article>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endif
