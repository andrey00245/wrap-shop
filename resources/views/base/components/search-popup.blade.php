<div id="search-popup" class="popup-right">
  <div class="search-popup-title">{{__('popup.search_popup.search_title')}}</div>
  <div class="search-popup-desc">{{__('popup.search_popup.whoever_searches')}}</div>
  <div class="search-popup-body form-horizontal">
    <div id="search" class="flex-justify">
      <input type="text" name="search-popup" value="" placeholder="{{__('popup.search_popup.im_looking_for')}}" class="form-control input-lg" autocomplete="off">

        <ul class="dropdown-menu" style="top: 60px;left: 0">

        </ul>

        <button type="button" class="button colord btn btn-default btn-lg"><i class="fal fa-search"></i>{{__('popup.search_popup.search')}}
      </button>
    </div>
  </div>
  <div class="close button search-popup-close fal fa-times"></div>
</div>
