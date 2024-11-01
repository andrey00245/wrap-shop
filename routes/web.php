<?php

use App\Http\Controllers\IndexController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SyncProductImagesController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\SyncProductController;

Route::get('/syn-images', [SyncProductImagesController::class, 'updateProducts']);
Route::get('/syn-products', [SyncProductController::class, 'updateProducts']);

Route::group(['prefix' => LaravelLocalization::setLocale(),
  'middleware' => ['localizationRedirect', 'localeViewPath' ]], function(){

  Route::get('/', [IndexController::class,'__invoke'])->name('index');

  Route::get('/shipping-and-payment', function () {
    return view('base.pages.delivery');
  })->name('delivery');

  Route::get('/videoreviews', function () {
    return view('base.pages.videoreviews');
  })->name('videoreviews');

  Route::get('/news', function () {
    return view('base.pages.news');
  })->name('news');

  Route::get('/about-us', function () {
    return view('base.pages.about-us');
  })->name('about-us');

  Route::get('/contacts', function () {
    return view('base.pages.contacts');
  })->name('contacts');

  Route::group(['prefix' => '/account'], function(){
    Route::get('/', function () {
      return view('base.pages.account.account');
    })->name('account');

    Route::get('/personal-data', function () {
      return view('base.pages.account.personal-data');
    })->name('personal-data');

    Route::group(['prefix' => '/address'], function() {
      Route::get('/', function () {
        return view('base.pages.account.address.index');
      })->name('address');
      Route::get('/create', function () {
        return view('base.pages.account.address.create');
      })->name('account.address.create');
      Route::get('/edit', function () {
        return view('base.pages.account.address.edit');
      })->name('account.address.edit');
    });

    Route::get('/order', function () {
      return view('base.pages.account.order');
    })->name('order');

    Route::get('/viewed-products', function () {
      return view('base.pages.account.viewed-products');
    })->name('viewed-products');

    Route::get('/wishlist', function () {
      return view('base.pages.account.wishlist');
    })->name('wishlist');
  });

  Route::group(['prefix' => '/products'], function(){
    Route::get('/', [ProductController::class,'index'])->name('products.index');
    Route::get('/{product}', [ProductController::class,'show'])->name('products.show');
  });

  Route::get('/checkout', function () {
    $products = Product::query()
      ->whereHas('prices', function ($query) {
        $query->where('price_type_id', 2)
          ->where('price', '!=', 0);
      })
      ->whereHas('media')
      ->whereHas('categories')
      ->with(['media', 'categories'])
      ->paginate(6);

    return view('base.pages.checkout.index', compact('products'));
  })->name('checkout');

});

