@props([
    'category' => null,
])
@php
    /** @var \App\Models\Category|null $category */
    $locale = app()->getLocale();
    $faqItems = $category?->faqForSchema() ?? [];
    $faqSchema = null;
    if ($faqItems !== []) {
        $faqSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => collect($faqItems)->map(fn (array $row) => [
                '@type' => 'Question',
                'name' => $row['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => strip_tags($row['answer']),
                ],
            ])->all(),
        ];
    }
@endphp
@if($faqSchema !== null)
<script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endif

