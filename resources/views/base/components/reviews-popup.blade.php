@php
    $reviews = $product->reviews->where('is_active', true)->sortByDesc('created_at');
    $average = $reviews->avg('rating') ?? 0;
    $count = $reviews->count();
@endphp

<section class="popup-right general-popup" id="review-popup" data-step="1">
    {{-- Заголовок та середня оцінка --}}
    <div class="popup-review-top flex-justify">
        <div class="left">
            <div class="title">Відгук</div>
            <div class="name">{{ $product->name }}</div>
        </div>
        <div class="right">
            <div class="number">{{ number_format($average, 1) }}<span>/5</span></div>
            <div class="rating">
                @for ($i = 1; $i <= 5; $i++)
                    <i class="fal fa-star"></i>
                @endfor
                <div class="rating-result" style="width: {{ ($average / 5) * 100 }}%">
                    @for ($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star"></i>
                    @endfor
                </div>
            </div>
            <div class="info flex-center">
                <div class="count">Відгуків ({{ $count }})</div>
                <div class="status">
                    @if ($average >= 4.5)
                        Супер
                    @elseif ($average >= 3)
                        Добре
                    @else
                        Важко сказати
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Відгуки --}}
    <div class="popup-review-list popup-step-1" id="review">
        @forelse ($reviews as $review)
            <div class="item flex-justify">
                <div class="left">
                    <div class="author">{{ $review->name }}</div>
                    <div class="rating">
                        @for ($i = 1; $i <= $review->rating; $i++)
                            <i class="fas fa-star"></i>
                        @endfor
                        @for ($i = $review->rating + 1; $i <= 5; $i++)
                            <i class="far fa-star"></i>
                        @endfor
                    </div>
                </div>
                <div class="date">{{ $review->created_at->format('d.m.Y') }}</div>
                <div class="desc">{{ $review->text }}</div>
            </div>
        @empty
            <div class="no-reviews">
                <span>Немає відгуків про цей товар.</span> Будь першим хто залишить відгук!
            </div>
        @endforelse

        <div class="flex-justify" id="review-result">
            <div class="item-result">Показано з 1 по {{ $reviews->count() }} із {{ $reviews->count() }} (1 сторінка)</div>
            <div class="item-pagination"></div>
        </div>
    </div>

    {{-- Форма відгуку --}}
    <form class="popup-review-form form-horizontal popup-step-3" id="form-review">
        <div class="title">Залишити відгук</div>
        <div class="form-group required">
            <label class="control-label" for="input-name">Ваше імʼя:</label>
            <input type="text" name="name" value="{{ auth()->check() ? auth()->user()->name : ''}}" id="review-name" class="form-control" placeholder="Введіть імʼя">
        </div>
        <div class="form-group required">
            <label class="control-label" for="input-review">Ваш відгук:</label>
            <textarea name="text" id="input-review" class="form-control" placeholder="Введіть відгук"></textarea>
        </div>
        <div class="form-group required">
            <div class="control-label">Оцінка</div>
            <div class="form-radio">
                @for ($i = 1; $i <= 5; $i++)
                    <label for="input-rating-{{ $i }}">
                        <input id="input-rating-{{ $i }}" type="radio" name="rating" value="{{ $i }}">
                        <span>
                            @switch($i)
                                @case(1) Можно краще @break
                                @case(2) Так собі @break
                                @case(3) Добре @break
                                @case(4) Чудово @break
                                @case(5) Супер @break
                            @endswitch
                        </span>
                    </label>
                @endfor
            </div>
        </div>

        <div id="review-alert" class="mt-3 clear-text"></div>

        <div class="buttons">
            <button type="button" id="button-review" data-loading-text="Завантаження..." class="button colord">
                <i class="fas fa-chevron-right"></i>Відправити відгук
            </button>
        </div>
    </form>

    <div class="review-form-open button colord popup-step-table-1">Залишити відгук</div>
    <div class="close popup-close button fal fa-times"></div>
</section>

{{-- AJAX логіка --}}
<script>
    document.getElementById('button-review').addEventListener('click', function () {
        const button = this;
        const alertContainer = document.getElementById('review-alert');
        alertContainer.innerHTML = '';

        button.disabled = true;
        button.innerHTML = 'Завантаження...';

        fetch('{{ route('reviews.store') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                product_id: '{{ $product->id }}',
                name: document.getElementById('review-name').value,
                text: document.getElementById('input-review').value,
                rating: document.querySelector('input[name="rating"]:checked')?.value || 0
            })
        })
            .then(response => {
                if (!response.ok) throw response;
                return response.json();
            })
            .then(data => {
                alertContainer.innerHTML = `
                <div class="alert alert-success alert-dismissible">
                    <i class="fas fa-check-circle"></i> Спасибі за ваш відгук. Він вступив адміністратору для перевірки на спам і незабаром буде опублікований.
                </div>
            `;
                document.getElementById('form-review').reset();
            })
            .catch(async error => {
                let messages = [];

                if (error.json) {
                    const err = await error.json();
                    if (err.errors) {
                        messages = Object.values(err.errors).flat(); // Збираємо всі повідомлення в один масив
                    } else if (err.message) {
                        messages = [err.message];
                    }
                } else {
                    messages = ['Помилка при відправці відгуку.'];
                }

                alertContainer.innerHTML = `
        <div class="alert alert-danger alert-dismissible">
            <i class="fas fa-exclamation-triangle"></i>
            <ul class="mb-0">
                ${messages.map(msg => `<li>${msg}</li>`).join('')}
            </ul>
        </div>
    `;
            })
            .finally(() => {
                button.disabled = false;
                button.innerHTML = '<i class="fas fa-chevron-right"></i>Відправити відгук';
            });
    });
</script>
