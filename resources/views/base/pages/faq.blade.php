@extends('base.layouts.app')

@section('content')
  @push('styles')
      @if($theme === 'dark')
          <link rel="stylesheet" type="text/css" href="{{mix('build/css/style-information-dark.css')}}">
      @else
          <link rel="stylesheet" type="text/css" href="{{mix('build/css/style-information-light.css')}}">
      @endif
  @endpush

  <nav class="breadcrumbs wrap row">
    <ul class="flex-center">
      <li>
        <a href="{{route('index')}}" title="{{__('header_footer.home')}}" class="button">{{__('header_footer.home')}}</a>
      </li>
      <li>
        <span class="button">FAQ</span>
      </li>
    </ul>
  </nav>

  <section class="page-faq wrap">
    <h1 class="default-title">{{ __('faq.title') }}</h1>
    
    @if($faqs->count() > 0)
      <div class="faq-accordion">
        @foreach($faqs as $index => $faq)
          <div class="faq-item" data-faq-id="{{ $faq->id }}">
            <div class="faq-question" data-index="{{ $index }}">
              <h3 class="faq-question-text">{{ $faq->question }}</h3>
              <span class="faq-toggle">
                <svg class="faq-icon" width="24" height="24" viewBox="0 0 24 24" fill="none">
                  <path d="M19 9L12 16L5 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
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
    @else
      <div class="no-faq">
        <p>{{ __('faq.no_items') }}</p>
      </div>
    @endif
  </section>

  @push('scripts')
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const faqItems = document.querySelectorAll('.faq-item');
        let activeItem = null;

        faqItems.forEach((item, index) => {
          const question = item.querySelector('.faq-question');
          const answer = item.querySelector('.faq-answer');
          const toggle = item.querySelector('.faq-toggle');

          question.addEventListener('click', function() {
            if (activeItem === item) {
              // Закрываем текущий элемент
              closeItem(item, answer, toggle);
              activeItem = null;
            } else {
              // Закрываем предыдущий активный элемент
              if (activeItem) {
                const prevAnswer = activeItem.querySelector('.faq-answer');
                const prevToggle = activeItem.querySelector('.faq-toggle');
                closeItem(activeItem, prevAnswer, prevToggle);
              }
              
              // Открываем новый элемент
              openItem(item, answer, toggle);
              activeItem = item;
            }
          });
        });

        function openItem(item, answer, toggle) {
          item.classList.add('active');
          answer.style.maxHeight = answer.scrollHeight + 'px';
          toggle.style.transform = 'rotate(180deg)';
        }

        function closeItem(item, answer, toggle) {
          item.classList.remove('active');
          answer.style.maxHeight = '0';
          toggle.style.transform = 'rotate(0deg)';
        }
      });
    </script>
  @endpush
@endsection