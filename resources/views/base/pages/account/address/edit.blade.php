@extends('base.pages.account.layout')
@section('title', __('personal-account.you-addresses.meta_title'))

@section('account.content')
  <h1>{{__('personal-account.editing-adding-address.title')}}</h1>
  <div class="simple-content">
    <form action="{{route('account.address.update', ['address'=>$address->id])}}" method="post" enctype="multipart/form-data"
          id="simplepage_form">
      @csrf
      @method('PUT')
      <div class="simpleregister" id="simpleaddress">
        <div class="simpleregister-block-content">
          <fieldset class="form-horizontal">
            <div class="form-group required row-address_city">
              <label class="control-label col-sm-2"
                     for="city">{{__('personal-account.editing-adding-address.locality')}}</label>
              <div class="col-sm-10" id="city-input-wrapper" style="position: relative;">
                <input class="form-control" type="text" name="city" id="city" value="{{$address->city}}"
                       placeholder="{{__('personal-account.editing-adding-address.enter-city')}}">
                <ul id="city-suggestions" class="dropdown-suggestions" style="display:none;"></ul>
                @error('city')
                <div class="simplecheckout-rule-group">
                  <div class="simplecheckout-error-text simplecheckout-rule">
                    {{$errors->first('city')}}
                  </div>
                </div>
                @enderror
              </div>
            </div>

            <div class="form-group required row-address_address_1">
              <label class="control-label col-sm-2"
                     for="address">{{__('personal-account.editing-adding-address.branch-address')}}</label>
              <div class="col-sm-10" style="position: relative;">
                <input class="form-control" type="text" name="address" id="shipping_address" value="{{$address->address}}"
                       placeholder="{{__('personal-account.editing-adding-address.enter-address')}}">
                <input type="hidden" name="novaposhta_warehouse_ref" id="novaposhta_warehouse_ref" value="">
                <ul id="address-suggestions" class="dropdown-suggestions" style="display:none;"></ul>
                @error('address')
                <div class="simplecheckout-rule-group">
                  <div class="simplecheckout-error-text simplecheckout-rule">
                    {{$errors->first('address')}}
                  </div>
                </div>
                @enderror
              </div>
            </div>
          </fieldset>
        </div>
        <div class="simpleregister-button-block buttons">
          <div class="simpleregister-button-right">
            <button class="button btn-primary button_oc btn" data-onclick="submit"
                    id="simpleregister_button_confirm"><span>{{__('personal-account.editing-adding-address.save')}}</span></button>
          </div>
        </div>
      </div>
    </form>
  </div>
@endsection
@push('scripts')
<script>
  $(function(){
    let debounceTimer; let cityRefSelected=null; let branchesData=[];
    $(document).on('input', '#city', function(){
      clearTimeout(debounceTimer);
      const q = this.value.trim();
      const $box = $('#city-suggestions');
      if(q.length<2){ $box.hide(); return; }
      const locale = $('html').attr('lang') || 'uk';
      debounceTimer = setTimeout(function(){
        $.get('/'+locale+'/api/get-cities', {cityName:q}).done(function(r){
          const items = (r.data||[]).map(c=>`<li data-id="${c.ref}" data-value="${c.name}">${c.name} (${c.region})</li>`).join('') || "<li class='no-results'>Місто не знайдено</li>";
          $box.html(items).show();
        }).fail(function(){ $box.html("<li class='no-results'>Помилка завантаження</li>").show(); });
      },300);
    });
    $(document).on('click', '#city-suggestions li', function(e){
      e.preventDefault(); $('#address-suggestions').empty(); $('#shipping_address').val('');
      const sel = $(this).data('value'); const ref = $(this).data('id');
      $('#city').val(sel); cityRefSelected = ref; $('#city-suggestions').hide();
      if(cityRefSelected){
        const locale = $('html').attr('lang') || 'uk';
        $.get('/'+locale+'/api/get-branches', {cityRef:cityRefSelected}).done(function(r){
          branchesData = r.data||[]; updateAddressSuggestions('');
        });
      }
    });
    function updateAddressSuggestions(q){
      const $box = $('#address-suggestions');
      let html='';
      branchesData.forEach(function(b){ if((b.name||'').toLowerCase().indexOf((q||'').toLowerCase())!==-1){ html += `<li data-id="${b.id}" data-value="${b.name}">${b.name}</li>`; }});
      if(!html){ html = "<li class='no-results'>Відділення не знайдено</li>"; }
      $box.html(html).show();
    }
    $(document).on('input', '#shipping_address', function(){ updateAddressSuggestions(this.value.trim()); });
    $(document).on('click', '#address-suggestions li', function(e){
      e.preventDefault(); const name=$(this).data('value'); const ref=$(this).data('id');
      $('#shipping_address').val(name); $('#novaposhta_warehouse_ref').val(ref); $('#address-suggestions').hide();
    });
    $(document).on('click', function(e){
      if(!$(e.target).closest('#city-input-wrapper').length){ $('#city-suggestions').hide(); }
      if(!$(e.target).closest('#shipping_address').length){ $('#address-suggestions').hide(); }
    });
  });
</script>
@endpush
@push('fixed-catalog')
  @include('base.components.categories-catalog')
@endpush
