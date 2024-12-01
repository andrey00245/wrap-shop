<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PersonalDataController extends Controller
{
  public function update(UpdateUserRequest $request){
    $user = User::findOrFail(Auth::user()->id);
    $user->update($request->validated());
    return redirect()->route('account')->with('success', __('success.update-account'));
  }
}
