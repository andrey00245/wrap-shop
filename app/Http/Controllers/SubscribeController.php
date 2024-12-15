<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscribeRequest;
use App\Models\SubscribeUser;
use App\Notifications\SendSubscribeNotification;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;

class SubscribeController extends Controller
{

  public function store(SubscribeRequest $request)
  {
    try {
      DB::beginTransaction();
      $subscribedUser = SubscribeUser::where('email', $request->get('email'))->first();
      if (!$subscribedUser) {
        $subscribedUser = SubscribeUser::create($request->all());
        $subscribedUser->notify(new SendSubscribeNotification());
      }
      DB::commit();
    } catch (\Exception $ex) {
      DB::rollBack();
      return response()->json(['success' => false, 'message' => __('subscription.something_went_wrong')]);
    }
    return response()->json(['success' => true]);
  }
}
