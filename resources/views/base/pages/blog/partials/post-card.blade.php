@php
    $locale = app()->getLocale();
    $slug = $post->getTranslation('slug', $locale) ?: (string) reset($post->getTranslations('slug'));
    $variant = $variant ?? null;
    $latestIsLead = (bool) ($latestIsLead ?? false);
    $cardClass = 'article-card';
    if ($variant === 'latest') {
        $cardClass .= ' article-card--latest';
    }
    $useLeadCover = $post->hasCover() && $variant === 'latest' && $latestIsLead;
@endphp
<article class="{{ $cardClass }}" data-category="{{ $post->blog_category_id ?? '' }}">
    <div class="article-image">
        @if($post->hasCover())
            @if($useLeadCover)
                <img src="{{ $post->coverUrlArticle() }}" alt="{{ $post->getTranslation('title', $locale) }}" loading="lazy" width="646" height="292" decoding="async">
            @elseif($variant === 'latest')
                <img src="{{ $post->coverUrlSmall() }}" alt="{{ $post->getTranslation('title', $locale) }}" loading="lazy" width="312" height="292" decoding="async">
            @else
                <img src="{{ $post->coverUrlSmall() }}" alt="{{ $post->getTranslation('title', $locale) }}" loading="lazy" width="646" height="266" decoding="async">
            @endif
        @else
            <div class="image-placeholder"></div>
        @endif
    </div>
    <div class="article-content">
        <div class="article-meta">
            <div class="article-tag">{{ $post->category?->getTranslation('name', $locale) ?? '—' }}</div>
            <div class="article-date">{{ $post->publicDisplayDate()->format('d.m.Y') }}</div>
        </div>
        <h3 class="article-title">{{ $post->getTranslation('title', $locale) }}</h3>
        <p class="article-excerpt">{{ strip_tags($post->getTranslation('excerpt', $locale) ?? '') }}</p>
        <div class="article-author">
            <span>автор:
                @if($post->author)
                    <a href="{{ route('blog.author', $post->author) }}">{{ $post->author->getTranslation('name', $locale) }}</a>
                @else
                    —
                @endif
            </span>
            <a href="{{ route('blog.show', ['blog_post' => $slug]) }}" class="article-author-link" title="{{ __('blog.read') }}">
                <img src="{{ asset('assets/img/icons/arrow_outward.png') }}" alt="">
            </a>
        </div>
    </div>
</article>
