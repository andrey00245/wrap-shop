@props([
    'category' => null,
])
@php
    /** @var \App\Models\Category|null $category */
    $locale = app()->getLocale();
    $faqSchema = \App\Support\LocaleFaqItems::faqPageSchema($category?->faqForSchema($locale) ?? []);
@endphp
@if($faqSchema !== null)
<script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endif

