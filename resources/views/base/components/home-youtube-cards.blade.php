@php
    $youtubeCards = collect($youtubeChannelCards ?? [])->filter(fn ($item) => $item->getPreviewImage() !== '');
    $youtubeSlider = $youtubeCards->count() > 1;
@endphp

@if($youtubeCards->isNotEmpty())
    <section class="home-youtube-cards row" id="homeYoutubeCards">
        <h2 class="home-title home-youtube-cards__title">
            {!! __('youtube.home_heading') !!}
        </h2>

        @if($youtubeSlider)
            <div class="splide home-youtube-cards__list home-products-list js-home-youtube-cards-splide">
                <div class="splide__arrows home-products-slide-buttons">
                    <div class="line"></div>
                </div>
                <div class="splide__track">
                    <ul class="splide__list">
                        @foreach($youtubeCards as $card)
                            <li class="splide__slide home-youtube-cards__slide">
                                <article class="home-youtube-card">
                                    <img
                                        src="{{ $card->getPreviewImage() }}"
                                        alt="{{ $card->title }}"
                                        class="home-youtube-card__image"
                                        loading="lazy"
                                    >

                                    <svg class="home-youtube-card__overlay-svg home-youtube-card__overlay-svg--desktop" viewBox="0 0 636 279" preserveAspectRatio="none" aria-hidden="true">
                                        <defs>
                                            <mask id="yt-mask-{{ $card->id }}">
                                                <rect width="636" height="279" fill="white"/>
                                                <g transform="rotate(40.85, 115.4, 54.05)">
                                                    <rect x="-180" y="-23" width="544.8" height="154.1" rx="77" ry="77" fill="black"/>
                                                    <rect x="101" y="-67" width="215" height="30.7" rx="15.4" ry="15.4" fill="black"/>
                                                    <rect x="141" y="-108" width="120" height="30.7" rx="15.4" ry="15.4" fill="black"/>
                                                </g>
                                            </mask>
                                        </defs>
                                        <rect width="636" height="279" fill="#212121" mask="url(#yt-mask-{{ $card->id }})"/>
                                    </svg>
                                    <svg class="home-youtube-card__overlay-svg home-youtube-card__overlay-svg--mobile" viewBox="0 0 636 279" preserveAspectRatio="none" aria-hidden="true">
                                        <defs>
                                            <mask id="yt-mask-{{ $card->id }}-m">
                                                <rect width="636" height="279" fill="white"/>
                                                <g transform="rotate(40.85, 115.4, 54.05)">
                                                    <rect x="-160" y="-23" width="500.8" height="160.1" rx="100" ry="70" fill="black"/>
                                                    <rect x="101" y="-67" width="170" height="30.7" rx="15.4" ry="15.4" fill="black"/>
                                                    <rect x="130" y="-111" width="125" height="30.7" rx="15.4" ry="15.4" fill="black"/>
                                                </g>
                                            </mask>
                                        </defs>
                                        <rect width="636" height="279" fill="#212121" mask="url(#yt-mask-{{ $card->id }}-m)"/>
                                    </svg>

                                    <img
                                        src="{{ asset('assets/img/logo.png') }}"
                                        alt="Wrap Shop"
                                        class="home-youtube-card__logo"
                                        width="56"
                                        height="59"
                                        loading="lazy"
                                    >

                                    <div class="home-youtube-card__content">
                                        <h3 class="home-youtube-card__name">{{ $card->title }}</h3>
                                        <a
                                            href="{{ $card->button_url }}"
                                            class="home-youtube-card__button"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            title="{{ $card->button_text }}"
                                        >
                                            {{ $card->button_text }}
                                        </a>
                                    </div>
                                </article>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @else
            <div class="home-youtube-cards__grid">
                @foreach($youtubeCards as $card)
                    <article class="home-youtube-card">
                        <img
                            src="{{ $card->getPreviewImage() }}"
                            alt="{{ $card->title }}"
                            class="home-youtube-card__image"
                            loading="lazy"
                        >

                        <svg class="home-youtube-card__overlay-svg home-youtube-card__overlay-svg--desktop" viewBox="0 0 636 279" preserveAspectRatio="none" aria-hidden="true">
                            <defs>
                                <mask id="yt-mask-{{ $card->id }}">
                                    <rect width="636" height="279" fill="white"/>
                                    <g transform="rotate(40.85, 115.4, 54.05)">
                                        <rect x="-180" y="-23" width="544.8" height="154.1" rx="77" ry="77" fill="black"/>
                                        <rect x="101" y="-67" width="215" height="30.7" rx="15.4" ry="15.4" fill="black"/>
                                        <rect x="141" y="-108" width="120" height="30.7" rx="15.4" ry="15.4" fill="black"/>
                                    </g>
                                </mask>
                            </defs>
                            <rect width="636" height="279" fill="#212121" mask="url(#yt-mask-{{ $card->id }})"/>
                        </svg>
                        <svg class="home-youtube-card__overlay-svg home-youtube-card__overlay-svg--mobile" viewBox="0 0 636 279" preserveAspectRatio="none" aria-hidden="true">
                            <defs>
                                <mask id="yt-mask-{{ $card->id }}-m">
                                    <rect width="636" height="279" fill="white"/>
                                    <g transform="rotate(40.85, 115.4, 54.05)">
                                        <rect x="-160" y="-23" width="500.8" height="160.1" rx="100" ry="70" fill="black"/>
                                        <rect x="101" y="-67" width="170" height="30.7" rx="15.4" ry="15.4" fill="black"/>
                                        <rect x="130" y="-111" width="125" height="30.7" rx="15.4" ry="15.4" fill="black"/>
                                    </g>
                                </mask>
                            </defs>
                            <rect width="636" height="279" fill="#212121" mask="url(#yt-mask-{{ $card->id }}-m)"/>
                        </svg>

                        <img
                            src="{{ asset('assets/img/logo.png') }}"
                            alt="Wrap Shop"
                            class="home-youtube-card__logo"
                            width="56"
                            height="59"
                            loading="lazy"
                        >

                        <div class="home-youtube-card__content">
                            <h3 class="home-youtube-card__name">{{ $card->title }}</h3>
                            <a
                                href="{{ $card->button_url }}"
                                class="home-youtube-card__button"
                                target="_blank"
                                rel="noopener noreferrer"
                                title="{{ $card->button_text }}"
                            >
                                {{ $card->button_text }}
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
@endif
