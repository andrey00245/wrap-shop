<ul class="menu">
  <li class="{{url()->current() === route('videoreviews') ? 'active' : ''}}"><a href="{{route('videoreviews')}}" title="{{__('header_footer.video_reviews')}}">{{__('header_footer.video_reviews')}}</a></li>
  <li class="{{url()->current() === route('delivery') ? 'active' : ''}}"><a href="{{route('delivery')}}" title="{{__('header_footer.delivery')}}">{{__('header_footer.delivery')}}</a></li>
  <li class="{{url()->current() === route('news.index') ? 'active' : ''}}"><a href="{{route('news.index')}}" title="{{__('header_footer.news')}}">{{__('header_footer.news')}}</a></li>
  <li class="{{url()->current() === route('about-us') ? 'active' : ''}}"><a href="{{route('about-us')}}" title="{{__('header_footer.about_us')}}">{{__('header_footer.about_us')}}</a></li>
  <li class="{{url()->current() === route('contacts') ? 'active' : ''}}"><a href="{{route('contacts')}}" title="{{__('header_footer.contact_us')}}">{{__('header_footer.contact_us')}}</a></li>
  <li><a href="" title="{{__('header_footer.offers')}}">{{__('header_footer.offers')}}</a></li>
</ul>
