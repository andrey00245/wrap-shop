<?php

namespace App\Http\Middleware;

use App\Models\CartItem;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CartMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $sum = 0;

        if (Auth::check()) {
            $cartItems = CartItem::where('user_id', Auth::id())->get();
            $cartItemsCount = 0;
            if ($cartItems->count() > 0) {

                foreach ($cartItems as $item) {
                    $sum += $item?->product?->getPriceByCount($item->quantity);
                }

                $cartItemsCount = $cartItems->count();
            }

        } else {
            $cartItems = Session::get('cart', []);


            foreach ($cartItems as $key => $item) {
                $sum += $item['product']?->getPriceByCount($item['quantity']);
            }

            $cartItemsCount = count($cartItems);
        }
        $integerPart = (int)floor($sum);
        $decimalPart = $sum - floor($sum);
        $decimalStr = number_format($decimalPart, 2, '.', '');
        $decimalStr = '.' . substr($decimalStr, 2);

        $sum_html = $integerPart . '<span class="coins">'.$decimalStr.'</span>';

        view()->share('cartItemsCount', $cartItemsCount);
        view()->share('cartItems', $cartItems);
        view()->share('sum', $sum);
        view()->share('sum_html', $sum_html);

        return $next($request);
    }
}
