@extends('base.pages.account.layout')

@section('account.content')
  <h1>{{__('personal-account.you-addresses.title')}}</h1>
  <div class="table-responsive">
    <table class="table table-bordered table-hover">
      <form action="https://wrap.shop/index.php?route=account/address/delete&amp;address_id=235" method="post"
            enctype="multipart/form-data" id="form-address-235"></form>
      <tbody>
      <tr>
        <td class="text-left">Сергій Толстіков<br>Україна</td>
        <td class="text-right">
          <a href="#"
            class="btn btn-info">{{__('personal-account.you-addresses.edit')}}</a> &nbsp;
          <a href="#" data-toggle="tooltip"
             title="{{__('personal-account.you-addresses.delete')}}" class="btn btn-danger"
             onclick="return confirm('{{__('personal-account.you-addresses.are-you-sure')}}')">{{__('personal-account.you-addresses.delete')}}</a>
        </td>
      </tr>
      </tbody>
    </table>
  </div>

  <div class="buttons flex-justify clearfix">
    <div class="pull-left"><a href="{{route('account')}}" class="btn btn-default">{{__('personal-account.you-addresses.back')}}</a></div>
    <div class="pull-right"><a href="{{route('account.address.create')}}"
                               class="btn btn-primary">{{__('personal-account.you-addresses.new-address')}}</a></div>
  </div>
@endsection
