@php
    $categoryFaqItems = collect($faqItems ?? []);
@endphp

@if($categoryFaqItems->isNotEmpty())
    <section class="home-faq category-faq row js-home-faq wrap">
        <h2 class="home-title home-faq__title">FAQ</h2>

        <div class="home-faq__accordion faq-accordion">
            @foreach($categoryFaqItems as $index => $faq)
                <div class="faq-item">
                    <div class="faq-question" data-index="{{ $index }}">
                        <h4 class="faq-question-text">{{ $faq['question'] }}</h4>
                        <span class="faq-toggle">
                            <span class="faq-toggle-symbol" aria-hidden="true"></span>
                        </span>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            {!! $faq['answer'] !!}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    @once
        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    document.querySelectorAll('.js-home-faq').forEach(function (root) {
                        if (root.dataset.faqBound === '1') {
                            return;
                        }
                        root.dataset.faqBound = '1';

                        const faqItems = root.querySelectorAll('.faq-item');
                        let activeItem = null;

                        faqItems.forEach(function (item) {
                            const question = item.querySelector('.faq-question');
                            const answer = item.querySelector('.faq-answer');
                            if (!question || !answer) {
                                return;
                            }

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
    @endonce
@endif
