@extends('base.layouts.checkout.app')


@section('content')
  @push('scripts')
    <script src="{{asset('js/jquery/swiper/js/swiper.jquery.min.js')}}"></script>
  @endpush
  @push('styles')
    <link rel="stylesheet" href="{{mix('build/css/simple-dark.css')}}">
  @endpush

  <div id="account-success" class="page-account wrap">
      <div class="page-account-wrap flex-justify">

          <div id="content" class="page-account-right success-page">
              <h1>Ваше замовлення прийняте!</h1>
              <div class="success-text"><p>Ваше замовлення прийняте!</p><p>Історія замовлення знаходиться в <a href="{{route('account')}}">Особистому кабінеті</a>. Для перегляду історії, перейдіть по посиланню <a href="{{route('order')}}">Історія замовлень</a>.</p><p style="display:none">Если ваша покупка связана с загрузкой, вы можете перейти в личный кабинет на страницу <a href="https://wrap.shop/index.php?route=account/download">загрузки</a> для их просмотра.</p><p>Якщо у Вас виникли питання, будь ласка <a href="{{route('contacts')}}">зв'яжіться з нами</a>.</p><p><strong>Дякуємо за покупки в нашому інтернет-магазині!</strong></p></div>
              <div class="buttons">
                  <div class="pull-right"><a href="{{route('index')}}" class="btn btn-primary">Продовжити</a></div>
              </div>
          </div>
      </div>
  </div>

  @push('scripts')
    <script src="{{mix('build/js/checkoutPage.js')}}"></script>
  @endpush

  <script>
      document.getElementById('submitBtn').addEventListener('click', function() {
          document.getElementById('checkoutForm').submit();
      });
  </script>

@endsection
