@props([
    'category' => null,
])
@php
    /** @var \App\Models\Category|null $category */
    $locale = app()->getLocale();
    // Той самий набір, що й у UI (faq_items + прив'язані Faq)
    $faqSchema = \App\Support\LocaleFaqItems::faqPageSchema($category?->faqForDisplay($locale) ?? []);
@endphp
@if($faqSchema !== null)
<script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endif

