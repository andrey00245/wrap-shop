<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
  public function update(UpdatePasswordRequest $request){
    $user = User::findOrFail(Auth::user()->id);
    $user->password = Hash::make($request->validated('password'));
    $user->save();

    return redirect()->route('account')->with('success', __('success.update-password'));
  }
}
