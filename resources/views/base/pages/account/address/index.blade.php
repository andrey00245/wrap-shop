@extends('base.pages.account.layout')

@section('title', __('personal-account.you-addresses.meta_title'))

@section('account.content')
  <h1>{{__('personal-account.you-addresses.title')}}</h1>
  <div class="table-responsive">

      <table class="table table-bordered table-hover">
        <tbody>
        @foreach(auth()->user()->addresses as $address)
          <tr>
            <td class="text-left">
              {{auth()->user()->name . ' ' . auth()->user()->last_name}}<br>
              {{$address->address}}<br>
              {{$address->city}}<br>
              Україна</td>
            <td class="text-right">
              <form action="{{route('account.address.delete', ['address'=>$address->id])}}" method="post" enctype="multipart/form-data">
                @csrf
                @method('DELETE')
                <input type="hidden" name="address_id" value="{{$address->id}}">
                <a href="{{route('account.address.edit', ['address' => $address->id])}}"
                   class="btn btn-info">{{__('personal-account.you-addresses.edit')}}</a>
                <button type="submit"
                        title="{{__('personal-account.you-addresses.delete')}}"
                        class="btn btn-info address-edit-delete-btn"
                        onclick="return confirm('{{__('personal-account.you-addresses.are-you-sure')}}')">{{__('personal-account.you-addresses.delete')}}</button>
              </form>
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
  </div>

  <div class="buttons flex-justify clearfix">
    <div class="pull-left"><a href="{{route('account')}}"
                              class="btn btn-default">{{__('personal-account.you-addresses.back')}}</a></div>
    <div class="pull-right"><a href="{{route('account.address.create')}}"
                               class="btn btn-primary">{{__('personal-account.you-addresses.new-address')}}</a></div>
  </div>
@endsection
