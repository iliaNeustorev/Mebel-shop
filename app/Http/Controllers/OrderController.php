<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index () 
    {
        return view('orders');


        
    }

    public function repeatOrder ($id) 
    {
        $order = Order::find($id)->products();
        $basket_products = session('products',[]);
        $order->transform( function ($product) use ($basket_products) {
            return $basket_products[$product->id] = $product->pivot->quantity;
        });
        dd($basket_products);
        session()->put('products', $basket_products);
        session()->save();
        return redirect()->route('basket');
    }
}
