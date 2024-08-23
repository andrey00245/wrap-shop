@extends('base.pages.account.layout')

@section('account.content')
  <h1>Список адрес доставки</h1>
  <div class="table-responsive">
    <table class="table table-bordered table-hover">
      <form action="https://wrap.shop/index.php?route=account/address/delete&amp;address_id=235" method="post"
            enctype="multipart/form-data" id="form-address-235"></form>
      <tbody>
      <tr>
        <td class="text-left">Сергій Толстіков<br>Україна</td>
        <td class="text-right"><a
            href="https://wrap.shop/index.php?route=account/simpleaddress/update&amp;address_id=235"
            class="btn btn-info">Редагувати</a> &nbsp;
          <a href="https://wrap.shop/index.php?route=account/address/delete&amp;address_id=235" data-toggle="tooltip"
             title="Видалити" class="btn btn-danger" onclick="return confirm('Ви впевнені?')">Видалити</a></td>
      </tr>

      </tbody>
    </table>
  </div>

  <div class="buttons flex-justify clearfix">
    <div class="pull-left"><a href="https://wrap.shop/account/" class="btn btn-default">Назад</a></div>
    <div class="pull-right"><a href="https://wrap.shop/index.php?route=account/simpleaddress/insert"
                               class="btn btn-primary">Нова адреса</a></div>
  </div>
@endsection
