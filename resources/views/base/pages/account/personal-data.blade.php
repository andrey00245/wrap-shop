@extends('base.pages.account.layout')

@section('title', __('personal-account.personal-data.meta_title'))

@section('account.content')
  <h1>{{__('personal-account.account.personal_data')}}</h1>
  <div class="simple-content">
    <div class="simpleedit-nav">
      <div class="button fiz active"><span class="flex-center"></span>{{__('personal-account.personal-data.physical_person')}}</div>
      <div class="button yur"><span class="flex-center"></span>{{__('personal-account.personal-data.legal_entity')}}</div>
    </div>

    @push('scripts')
      <script type="text/javascript">
        $(".simpleedit-nav .fiz").on("click", function () {
          $(".simpleedit-nav .button").removeClass("active");
          $(this).addClass("active");
          $(".simple-content .simpleregister .form-horizontal .form-group").hide();
          $(".simple-content .simpleregister .form-horizontal .form-group.required").show();
        });
        // $(".simpleedit-nav .yur").on("click", function () {
        //   $(".simpleedit-nav .button").removeClass("active");
        //   $(this).addClass("active");
        //   $(".simple-content .simpleregister .form-horizontal .form-group").show();
        //   $(".simple-content .simpleregister .form-horizontal .form-group.required").hide();
        // });
      </script>

    @endpush
    <form action="{{route('personal-data.update')}}" method="POST" enctype="multipart/form-data" id="simplepage_form">
      @csrf
      @method('PUT')
      <div class="simpleregister" id="simpleedit">
        <div class="simpleregister-block-content">
          <fieldset class="form-horizontal">
            <div class="form-group required row-edit_email">
              <label class="control-label col-sm-2" for="email">Email</label>
              <div class="col-sm-10">
                <input class="form-control" type="email" name="email" id="email" value="{{auth()->user()->email}}" placeholder="{{__('personal-account.personal-data.enter_email')}}">
                @error('email')
                <div class="simplecheckout-rule-group">
                  <div class="simplecheckout-error-text simplecheckout-rule">
                    {{$errors->first('email')}}
                  </div>
                </div>
                @enderror
              </div>
            </div>

            <div class="form-group required row-edit_firstname">
              <label class="control-label col-sm-2" for="name">{{__('personal-account.personal-data.firstname')}}</label>
              <div class="col-sm-10">
                <input class="form-control" type="text" name="name" id="name" value="{{auth()->user()->name}}"
                       placeholder="{{__('personal-account.personal-data.enter_firstname')}}">
                @error('name')
                <div class="simplecheckout-rule-group">
                  <div class="simplecheckout-error-text simplecheckout-rule">
                    {{$errors->first('name')}}
                  </div>
                </div>
                @enderror
              </div>
            </div>

            <div class="form-group required row-edit_lastname">
              <label class="control-label col-sm-2" for="last_name">{{__('personal-account.personal-data.lastname')}}</label>
              <div class="col-sm-10">
                <input class="form-control" type="text" name="last_name" id="last_name" value="{{auth()->user()->last_name}}"
                       placeholder="{{__('personal-account.personal-data.enter_lastname')}}">
                @error('last_name')
                <div class="simplecheckout-rule-group">
                  <div class="simplecheckout-error-text simplecheckout-rule">
                    {{$errors->first('last_name')}}
                  </div>
                </div>
                @enderror
              </div>
            </div>

            <div class="form-group required row-edit_telephone">
              <label class="control-label col-sm-2" for="phone">{{__('personal-account.personal-data.telephone')}}</label>
              <div class="col-sm-10">
                <input class="form-control" type="tel" name="phone" id="phone" value="{{auth()->user()->phone}}"
                       placeholder="{{__('personal-account.personal-data.enter_telephone')}}" data-onchange="reloadAll">
                @error('phone')
                <div class="simplecheckout-rule-group">
                  <div class="simplecheckout-error-text simplecheckout-rule">
                    {{$errors->first('phone')}}
                  </div>
                </div>
                @enderror
              </div>
            </div>

          </fieldset>
        </div>
        <div class="simpleregister-button-block buttons flex-justify">
          <a href="{{route('change-password.edit')}}" title="{{__('personal-account.personal-data.change_password')}}" class="colord link">{{__('personal-account.personal-data.change_password')}}</a>
          <div class="simpleregister-button-right">
            <button type="submit" class="button btn-primary button_oc btn"><i
                class="fas fa-chevron-right"></i><span>{{__('personal-account.personal-data.save_changes')}}</span></button>
          </div>
        </div>
      </div>
    </form>
  </div>
@endsection
@push('fixed-catalog')
  @include('base.components.categories-catalog')
@endpush
