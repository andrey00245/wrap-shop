@php
    /** @var \Illuminate\Support\Collection<int, \App\Models\Review> $chunkItems */
    $chunkItems = collect($chunkItems)->values();

    $candidateIndexes = $chunkItems->keys()->filter(function ($idx) use ($chunkItems) {
        $review = $chunkItems->get($idx);

        return ! empty($review?->photoUrl() ?: $review?->product?->getPreviewImage());
    })->values();

    $featuredFirstIdx = $candidateIndexes->get(0);
    $featuredFourthIdx = $candidateIndexes->get(1);
    $usedIndexes = collect([$featuredFirstIdx, $featuredFourthIdx])->filter(fn ($idx) => $idx !== null)->values();
    $remainingItems = $chunkItems->reject(fn ($item, $idx) => $usedIndexes->contains($idx))->values();

    $orderedItems = collect();
    $orderedItems->push($featuredFirstIdx !== null ? $chunkItems->get($featuredFirstIdx) : $remainingItems->shift());
    if ($remainingItems->isNotEmpty()) {
        $orderedItems->push($remainingItems->shift());
    }
    if ($remainingItems->isNotEmpty()) {
        $orderedItems->push($remainingItems->shift());
    }
    $orderedItems->push($featuredFourthIdx !== null ? $chunkItems->get($featuredFourthIdx) : $remainingItems->shift());
    $orderedItems = $orderedItems->filter();
    $orderedItems = $orderedItems->concat($remainingItems)->values()->take(6);

    $slotFeatured = [0, 3];
@endphp
<div class="home-reviews__grid">
    <div class="home-reviews__column">
        @if($orderedItems->has(0))
            @include('base.components.home-reviews-card', [
                'review' => $orderedItems->get(0),
                'isFeatured' => in_array(0, $slotFeatured, true),
            ])
        @endif
    </div>
    <div class="home-reviews__column home-reviews__column--stack">
        @foreach([1, 2] as $slot)
            @if($orderedItems->has($slot))
                @include('base.components.home-reviews-card', [
                    'review' => $orderedItems->get($slot),
                    'isFeatured' => false,
                ])
            @endif
        @endforeach
    </div>
    <div class="home-reviews__column">
        @if($orderedItems->has(3))
            @include('base.components.home-reviews-card', [
                'review' => $orderedItems->get(3),
                'isFeatured' => in_array(3, $slotFeatured, true),
            ])
        @endif
    </div>
    <div class="home-reviews__column home-reviews__column--stack">
        @foreach([4, 5] as $slot)
            @if($orderedItems->has($slot))
                @include('base.components.home-reviews-card', [
                    'review' => $orderedItems->get($slot),
                    'isFeatured' => false,
                ])
            @endif
        @endforeach
    </div>
</div>
