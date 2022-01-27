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
        $order = Order::find($id)->products;
        
        $basket_products = $order->map( function ($productOrder) {
            $product = Product::find($productOrder->id);
            return [
                'title' => $product->name,
                'price' => $product->price,
                'quantity' => $productOrder->pivot->quantity,    
            ];

        });
        session()->put('products', $basket_products);
        session()->save();
        $date = [
            'products' =>  $basket_products,
            'title' => 'Корзина',
            'sum_order' => 12,
        ];
        return redirect()->route('basket',$date);
    }
}
