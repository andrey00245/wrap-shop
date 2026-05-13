@php
    $reviews = $product->reviews->where('is_active', true)->sortByDesc('created_at');
    $average = $reviews->avg('rating') ?? 0;
    $count = $reviews->count();
@endphp

<section class="popup-right general-popup" id="review-popup" data-step="1">
    <div class="popup-review-top flex-justify">
        <div class="left">
            <div class="title">{{__('popup.reviews_popup.review')}}</div>
            <div class="name">{{ $product->getName() }}</div>
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
                <div class="count">{{__('popup.reviews_popup.reviews')}} ({{ $count }})</div>
                <div class="status">
                    @if ($average >= 4.5)
                        {{__('popup.reviews_popup.super')}}
                    @elseif ($average >= 3)
                        {{__('popup.reviews_popup.good')}}
                    @else
                        {{__('popup.reviews_popup.hard_to_say')}}
                    @endif
                </div>
            </div>
        </div>
    </div>

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
                {!! __('popup.reviews_popup.no_reviews') !!}
            </div>
        @endforelse

        <div class="flex-justify" id="review-result">
            <div class="item-result">{{__('popup.reviews_popup.showed', ['count' => $reviews->count(), 'total' => $reviews->count()])}}</div>
            <div class="item-pagination"></div>
        </div>
    </div>

    <form class="popup-review-form form-horizontal popup-step-3" id="form-review">
        <div class="title">{{__('popup.reviews_popup.leave_review')}}</div>
        <div class="form-group required">
            <label class="control-label" for="input-name">{{__('popup.reviews_popup.you_name')}}:</label>
            <input type="text" name="name" value="{{ auth()->check() ? auth()->user()->name : ''}}" id="review-name" class="form-control" placeholder="{{__('popup.reviews_popup.enter_name')}}">
        </div>
        <div class="form-group required">
            <label class="control-label" for="input-review">{{__('popup.reviews_popup.you_review')}}:</label>
            <textarea name="text" id="input-review" class="form-control" placeholder="{{__('popup.reviews_popup.enter_review')}}"></textarea>
        </div>
        <div class="form-group required">
            <div class="control-label">{{__('popup.reviews_popup.mark')}}</div>
            <div class="form-radio">
                @for ($i = 1; $i <= 5; $i++)
                    <label for="input-rating-{{ $i }}">
                        <input id="input-rating-{{ $i }}" type="radio" name="rating" value="{{ $i }}">
                        <span>
                            @switch($i)
                                @case(1) {{__('popup.reviews_popup.mark_could_be_better')}} @break
                                @case(2) {{__('popup.reviews_popup.mark_so_so')}} @break
                                @case(3) {{__('popup.reviews_popup.mark_good')}} @break
                                @case(4) {{__('popup.reviews_popup.mark_great')}} @break
                                @case(5) {{__('popup.reviews_popup.mark_super')}} @break
                            @endswitch
                        </span>
                    </label>
                @endfor
            </div>
        </div>

        <div id="review-alert" class="mt-3 clear-text"></div>

        <div class="buttons">
            <button type="button" id="button-review" data-loading-text="{{__('popup.reviews_popup.loading')}}..." class="button colord">
                <i class="fas fa-chevron-right"></i>{{__('popup.reviews_popup.send_review')}}
            </button>
        </div>
    </form>

    <div class="review-form-open button colord popup-step-table-1">{{__('popup.reviews_popup.leave_review')}}</div>
    <div class="close popup-close button fal fa-times"></div>
</section>

<script>
    document.getElementById('button-review').addEventListener('click', function () {
        const button = this;
        const alertContainer = document.getElementById('review-alert');
        alertContainer.innerHTML = '';

        button.disabled = true;
        button.innerHTML = '{{__('popup.reviews_popup.loading')}}...';

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
            .then(async response => {
                // Получаем текст ответа
                const text = await response.text();
                
                // Пытаемся найти JSON в тексте (может быть HTML перед JSON)
                let jsonText = text;
                const jsonMatch = text.match(/\{[\s\S]*\}/);
                if (jsonMatch) {
                    jsonText = jsonMatch[0];
                }
                
                let data;
                try {
                    data = JSON.parse(jsonText);
                } catch (e) {
                    // Если не удалось распарсить, пробуем весь текст
                    throw { response, message: 'Invalid JSON response' };
                }
                
                if (!response.ok) {
                    throw { response, data };
                }
                
                return data;
            })
            .then(data => {
                // Проверяем, что ответ содержит успешное сообщение
                if (data.message || data.review) {
                    alertContainer.innerHTML = `
                    <div class="alert alert-success alert-dismissible">
                        <i class="fas fa-check-circle"></i> {{__('popup.reviews_popup.success_message')}}
                    </div>
                `;
                    document.getElementById('form-review').reset();
                    
                // Переключаем обратно на список отзывов через 20 секунд
                setTimeout(() => {
                    const popup = document.getElementById('review-popup');
                    if (popup) {
                        popup.setAttribute('data-step', '1');
                        alertContainer.innerHTML = '';
                    }
                }, 20000);
                } else {
                    throw { message: '{{__('popup.reviews_popup.error_message')}}' };
                }
            })
            .catch(async error => {
                let messages = [];

                if (error.data) {
                    // Ошибка от сервера с данными
                    if (error.data.errors) {
                        messages = Object.values(error.data.errors).flat();
                    } else if (error.data.message) {
                        messages = [error.data.message];
                    } else {
                        messages = ['{{__('popup.reviews_popup.error_message')}}'];
                    }
                } else if (error.response) {
                    // Response объект
                    try {
                        const text = await error.response.text();
                        const jsonMatch = text.match(/\{[\s\S]*\}/);
                        if (jsonMatch) {
                            const err = JSON.parse(jsonMatch[0]);
                            if (err.errors) {
                                // Преобразуем ошибки валидации в понятные сообщения
                                const fieldNames = {
                                    'name': '{{__('popup.reviews_popup.you_name')}}',
                                    'text': '{{__('popup.reviews_popup.you_review')}}',
                                    'rating': '{{__('popup.reviews_popup.mark')}}'
                                };
                                
                                // Обрабатываем ошибки по полям
                                const errorMessages = [];
                                Object.keys(err.errors).forEach(field => {
                                    const fieldName = fieldNames[field] || field;
                                    err.errors[field].forEach(msg => {
                                        // Заменяем технические названия полей на понятные
                                        let userMessage = msg;
                                        if (msg.includes('name') && !msg.includes(fieldNames['name'])) {
                                            userMessage = msg.replace(/name/gi, fieldNames['name']);
                                        }
                                        if (msg.includes('text') && !msg.includes(fieldNames['text'])) {
                                            userMessage = msg.replace(/text/gi, fieldNames['text']);
                                        }
                                        if (msg.includes('rating') && !msg.includes(fieldNames['rating'])) {
                                            userMessage = msg.replace(/rating/gi, fieldNames['rating']);
                                        }
                                        errorMessages.push(userMessage);
                                    });
                                });
                                messages = errorMessages.length > 0 ? errorMessages : Object.values(err.errors).flat();
                            } else if (err.message) {
                                messages = [err.message];
                            } else {
                                messages = ['{{__('popup.reviews_popup.error_message')}}'];
                            }
                        } else {
                            messages = ['{{__('popup.reviews_popup.error_message')}}'];
                        }
                    } catch (e) {
                        messages = ['{{__('popup.reviews_popup.error_message')}}'];
                    }
                } else if (error.message) {
                    messages = [error.message];
                } else {
                    messages = ['{{__('popup.reviews_popup.error_message')}}'];
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
                button.innerHTML = '<i class="fas fa-chevron-right"></i>{{__('popup.reviews_popup.send_review')}}';
            });
    });
</script>
