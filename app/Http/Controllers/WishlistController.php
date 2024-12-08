<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateWishlistRequest;
use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
  public function update(UpdateWishlistRequest $request)
  {
    $data = [];
    $productId = (int)$request->get('product_id');

    if (Auth::check()) {
      /**
       * @var User $user
       */
      $user = Auth::user();
      if (!$user->wishlists()->where('product_id', $productId)->exists()) {
        $user->wishlists()->attach($productId);
        $data['status'] = 'add';
      } else {
        $user->wishlists()->detach($productId);
        $data['status'] = 'remove';
      }
      $data['count'] = $user->wishlists()->count();
    } else {
      $wishlist = session()?->get('wishlist', []);
      $key = array_search($productId, $wishlist, true);
      if (!in_array($productId, $wishlist, true)) {
        $data['status'] = 'add';
        $wishlist[] = $productId;
        session()?->put('wishlist', $wishlist);
      } else {
        $data['status'] = 'remove';
        unset($wishlist[$key]);
        session()?->put('wishlist', $wishlist);
      }
      $data['count'] = count(session()?->get('wishlist', []));
    }
    return response()->json($data);
  }

  public function delete(Product $product)
  {
    Auth::user()->wishlists()->detach($product->id);
    return redirect()->back()->with('success', __('personal-account.you-wishlist.changed'));
  }

  public function index()
  {
    return view('base.pages.account.wishlist', [
      'wishlists' => Auth::user()->wishlists
    ]);
  }

}
