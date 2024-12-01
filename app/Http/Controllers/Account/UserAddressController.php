<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateStoreUserAddressRequest;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Auth;


class UserAddressController extends Controller
{
  public function create()
  {
    return view('base.pages.account.address.create');
  }

  public function store(UpdateStoreUserAddressRequest $request)
  {
    UserAddress::create([
      'user_id' => Auth::id(),
      ...$request->all()
    ]);
    return redirect()->route('address')->with('success', __('success.add-address'));
  }

  public function edit(UserAddress $address)
  {
    return view('base.pages.account.address.edit', [
      'address' => $address
    ]);
  }

  public function update(UpdateStoreUserAddressRequest $request, UserAddress $address)
  {
    if ($address->user_id !== Auth::user()->id) {
      return redirect()->route('address')->with('error', 'Unknown error');
    }
    $address->update($request->validated());
    return redirect()->route('address')->with('success', __('success.update-address'));
  }

  public function delete(UserAddress $address)
  {
    if (Auth::user()->addresses()->count() <= 1) {
      return redirect()->route('address')->with('error', __('error.default-address'));
    }
    $address->delete();
    return redirect()->route('address')->with('success', __('success.delete-address'));
  }
}
