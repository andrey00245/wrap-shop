@php
    $homeFaqItems = collect($homeFaqs ?? []);
@endphp

@if($homeFaqItems->isNotEmpty())
    <section class="home-faq row js-home-faq">
        <h2 class="home-title home-faq__title">FAQ</h2>

        <div class="home-faq__accordion faq-accordion">
            @foreach($homeFaqItems as $index => $faq)
                <div class="faq-item" data-faq-id="{{ $faq->id }}">
                    <div class="faq-question" data-index="{{ $index }}">
                        <h3 class="faq-question-text">{{ $faq->question }}</h3>
                        <span class="faq-toggle">
                            <span class="faq-toggle-symbol" aria-hidden="true"></span>
                        </span>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            {!! $faq->answer !!}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.js-home-faq').forEach(function (root) {
                    const faqItems = root.querySelectorAll('.faq-item');
                    let activeItem = null;

                    faqItems.forEach(function (item) {
                        const question = item.querySelector('.faq-question');
                        const answer = item.querySelector('.faq-answer');

                        question.addEventListener('click', function () {
                            if (activeItem === item) {
                                closeItem(item, answer);
                                activeItem = null;
                                return;
                            }

                            if (activeItem) {
                                const prevAnswer = activeItem.querySelector('.faq-answer');
                                closeItem(activeItem, prevAnswer);
                            }

                            openItem(item, answer);
                            activeItem = item;
                        });
                    });

                    function openItem(item, answer) {
                        item.classList.add('active');
                        answer.style.maxHeight = answer.scrollHeight + 'px';
                    }

                    function closeItem(item, answer) {
                        item.classList.remove('active');
                        answer.style.maxHeight = '0';
                    }
                });
            });
        </script>
    @endpush
@endif
