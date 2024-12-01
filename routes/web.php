<?php

use App\Http\Controllers\Account\ChangePasswordController;
use App\Http\Controllers\Account\PersonalDataController;
use App\Http\Controllers\Account\UserAddressController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SyncProductImagesController;
use App\Http\Controllers\VideosController;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\SyncProductController;

Route::get('/syn-images', [SyncProductImagesController::class, 'updateProducts']);
Route::get('/syn-products', [SyncProductController::class, 'updateProducts']);

Route::group(['prefix' => LaravelLocalization::setLocale(),
  'middleware' => ['localizationRedirect', 'localeViewPath' ]], function(){

  Route::get('/',IndexController::class)->name('index');

  Route::get('/shipping-and-payment', function () {
    return view('base.pages.delivery');
  })->name('delivery');

    Route::get('/videoreviews', [VideosController::class, 'index'])->name('videoreviews');

    Route::get('/videoreviews/{category}', [VideosController::class, 'show'])->name('videos.show');

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

    Route::group(['prefix' => '/personal-data'], function(){
      Route::get('/', function () {return view('base.pages.account.personal-data');})->name('personal-data.edit');
      Route::put('/update', [PersonalDataController::class, 'update'])->name('personal-data.update');
    });

    Route::group(['prefix' => '/change-password'], function(){
      Route::get('/', function () {return view('base.pages.account.change-password');})->name('change-password.edit');
      Route::patch('/update', [ChangePasswordController::class, 'update'])->name('change-password.update');
    });

    Route::group(['prefix' => '/address'], function() {
      Route::get('/', function () {return view('base.pages.account.address.index');})->name('address');
      Route::get('/create', [UserAddressController::class, 'create'])->name('account.address.create');
      Route::post('/store', [UserAddressController::class, 'store'])->name('account.address.store');
      Route::get('/edit/{address}', [UserAddressController::class, 'edit'])->name('account.address.edit');
      Route::put('/update/{address}', [UserAddressController::class, 'update'])->name('account.address.update');
      Route::delete('/delete/{address}', [UserAddressController::class, 'delete'])->name('account.address.delete');
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

  Route::group(['prefix' => '/products'], function() {
    Route::get('/', [ProductController::class,'index'])->name('products.index');
    Route::get('/{product}/show', [ProductController::class,'show'])->name('products.show');
    Route::get('/category/{category}', [ProductController::class,'category'])->name('products.category');
  });

  Route::get('/checkout', function () {
    return view('base.pages.checkout.index');
  })->name('checkout');

});

