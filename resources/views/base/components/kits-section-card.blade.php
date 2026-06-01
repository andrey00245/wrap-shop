@php
  /** @var \App\Models\Kit $kit */
  $eyebrow = $kit->displayTagline($locale);
  $cardTitle = $kit->displayTitle($locale);
@endphp
<article class="home-block-kits__card">
  <div class="home-block-kits__card-head">
    <p class="home-block-kits__eyebrow">{{ $eyebrow }}</p>
    <h3 class="home-block-kits__card-title">{{ $cardTitle }}</h3>
  </div>

  @include('base.components.kits-section-card-body', ['kit' => $kit, 'locale' => $locale])
</article>
