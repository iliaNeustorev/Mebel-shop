<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\returnSelf;

class OrderController extends Controller
{
    //return user orders with products

    public function index () 
    {
        $orders = Auth::user()->orders;
        $products = [];
            return $orders->map(function($order) use ($products) {
                foreach ($order->products as $product) {
                        $products['order']['order_id'] = $order->id;
                        $products['order']['data'] = date('d.m.Y H:i:s', strtotime($order->created_at));
                        $products['products'][$product->id]['name'] = $product->name;
                        $products['products'][$product->id]['quantity'] = $product->pivot->quantity;
                        $products['products'][$product->id]['price'] = $product->pivot->price;
                       
                }
                $products['order']['sum'] =  $order->products->map( function ($product) {
                    return $product->pivot->quantity * $product->pivot->price;     
                })->sum(); 
                return $products;
        });
    }

    //return products in session for Repeat Order
    
    public function repeatOrder ($id) 
    {
        $order = Order::find($id)->products;
        $products = collect();

        $order->each( function ($product) use ($products) {
            $products->put($product->id, $product->pivot->quantity);
        });

        session()->put('products', $products);
        session()->save();

        return $products;
    }
}
