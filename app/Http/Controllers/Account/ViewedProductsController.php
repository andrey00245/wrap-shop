<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ViewedProductsController extends Controller
{
  public function index(){
    session()->get('viewProducts', []);
    $viewedProducts = Product::query()->whereIn('id', session()->get('viewProducts', []))->get();
    return view('base.pages.account.viewed-products', [
      'viewedProducts' => $viewedProducts
    ]);
  }
}
