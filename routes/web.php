<?php

use App\Http\Controllers\Account\ChangePasswordController;
use App\Http\Controllers\Account\PersonalDataController;
use App\Http\Controllers\Account\UserAddressController;
use App\Http\Controllers\Account\ViewedProductsController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SubscribeController;
use App\Http\Controllers\SyncProductImagesController;
use App\Http\Controllers\VideosController;
use App\Http\Controllers\WishlistController;
use App\Models\Category;
use App\Models\PrivacyPolicy;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\SyncProductController;

Route::get('/syn-images', [SyncProductImagesController::class, 'updateProducts']);
Route::get('/syn-products', [SyncProductController::class, 'updateProducts']);
Route::get('/slug-generate', function(){
  $products = Product::all();
  $categories = Category::all();
  foreach ($products as $product){
    $product->slug = [
      'en' => Str::slug($product->getTranslation('name', 'en')),
      'uk' => Str::slug($product->getTranslation('name', 'uk')),
      'ru' => Str::slug($product->getTranslation('name', 'ru'))
    ];
    $product->save();
  }
  foreach ($categories as $category){
    $category->slug = [
      'en' => Str::slug($category->getTranslation('name', 'en')),
      'uk' => Str::slug($category->getTranslation('name', 'uk')),
      'ru' => Str::slug($category->getTranslation('name', 'ru'))
    ];
    $category->save();
  }

  return 'okay';
});


Route::post('/add-product-to-wishlist', [WishlistController::class, 'update'])->name('add-product-to-wishlist');

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

    Route::get('/viewed-products', [ViewedProductsController::class, 'index'])->name('viewed-products');

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
  });

  Route::get('/wishlist/{product}/delete', [WishlistController::class, 'delete'])->name('wishlist.delete');


  Route::get('/restore-password', function () {return view('base.pages.account.restore-password');})->name('restore-password');


  Route::group(['prefix' => '/products'], function() {
    Route::get('/', [ProductController::class,'index'])->name('products.index');
    Route::get('/{product}/show', [ProductController::class,'show'])->name('products.show');
    Route::get('/{category}/{subcategory?}/{subsubcategory?}', [ProductController::class, 'category'])->name('products.category');
  });

  Route::get('/privacy-policy', function (){
    $privacy_policy = PrivacyPolicy::first();
    return view('base.pages.privacy-policy', compact('privacy_policy'));
  })->name('privacy-policy');

  Route::get('/checkout', function () {
    return view('base.pages.checkout.index');
  })->name('checkout');

  Route::post('/subscribe', [SubscribeController::class, 'store'])->name('subscribe');

});

