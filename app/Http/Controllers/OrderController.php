<?php

namespace App\Http\Controllers;

use App\Models\Order;

class OrderController extends Controller
{
    public function index () 
    {
        return view('orders');
    }

    public function repeatOrder ($id) 
    {
        $order = Order::find($id)->products;
        $basket_products = collect();

        $order->each( function ($product) use ($basket_products) {
            $basket_products->put($product->id, $product->pivot->quantity);
        });

        session()->put('products', $basket_products);
        session()->save();

        return redirect('basket');
    }
}
