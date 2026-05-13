<div id="search-popup" class="popup-right general-popup search-popup-wrapper">
  <div class="search-popup-title">{{__('popup.search_popup.search_title')}}</div>
  <div class="search-popup-desc">{{__('popup.search_popup.whoever_searches')}}</div>
  <div class="search-popup-body form-horizontal">
    <div id="search" class="flex-justify search-popup-form">
      <div class="search-popup-input-wrap">
        <input type="text" name="search-popup" value="" placeholder="{{__('popup.search_popup.im_looking_for')}}" class="form-control input-lg" autocomplete="off" aria-label="{{__('popup.search_popup.im_looking_for')}}">
        <ul class="dropdown-menu search-popup-dropdown" role="listbox" aria-label="{{__('popup.search_popup.products')}}"></ul>
      </div>
      <button type="button" class="button colord btn btn-default btn-lg search-popup-submit" aria-label="{{__('popup.search_popup.search')}}"><i class="fal fa-search" aria-hidden="true"></i>{{__('popup.search_popup.search')}}</button>
    </div>
  </div>
  <div class="close button popup-close fal fa-times" aria-label="Close"></div>
</div>
