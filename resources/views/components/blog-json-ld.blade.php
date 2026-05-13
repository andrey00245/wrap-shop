@props([
    'post' => null,
])
@php
    /** @var \App\Models\BlogPost $post */
    $locale = app()->getLocale();
    $headline = $post->getTranslation('title', $locale);
    $description = strip_tags((string) ($post->getTranslation('meta_description', $locale) ?: $post->getTranslation('excerpt', $locale) ?: ''));
    $cover = $post->coverUrlOg() ?: url('assets/img/og-default.jpg');
    $authorName = $post->author?->getTranslation('name', $locale) ?? 'Wrap.Shop';
    $published = $post->publicDisplayDate()->toIso8601String();
    $modified = $post->updated_at->toIso8601String();
    $articleSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => $headline,
        'description' => $description,
        'image' => [$cover],
        'datePublished' => $published,
        'dateModified' => $modified,
        'author' => [
            '@type' => 'Person',
            'name' => $authorName,
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name' => 'Wrap.Shop',
            'logo' => [
                '@type' => 'ImageObject',
                'url' => asset('assets/img/logo.png'),
            ],
        ],
        'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id' => url()->current(),
        ],
    ];
    $faqItems = $post->faqForSchema();
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
<script type="application/ld+json">{!! json_encode($articleSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@if($faqSchema !== null)
<script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endif
