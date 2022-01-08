<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BasketController extends Controller
{
    public function add ()
    {
        $id = request('id');
        $products = session('products', []);

        if (isset($products[$id])) {
            $products[$id] = $products[$id] + 1;
        }else {
            $products[$id] = 1;
        }
        session()->put('products', $products);
        session()->save();
        return back();
    }

    public function remove()
    {
        $id = request('id');
        $products = session('products', []);

        try {
            if ($products[$id] == 1) {
                unset($products[$id]);
            } else {
                $products[$id] -= 1;
            }
        } catch (Exception $e) {
            Log::info("Нажали на кнопку '-' когда товара не было в корзине {$id}");
        }
        session()->put('products', $products);
        session()->save();
        return back();
    }

    public function index () 
    {
        $products = session('products');
        $basket_products = collect($products)->map( function ($quantity, $id) {
            $product = Product::find($id);
            return [
                'title' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity 
            ];
        });

        $sum_order =  $basket_products->map( function ($product) {
            return $product['price'] * $product['quantity'];     
        })->sum();
       
        $date = [
            'products' => $basket_products,
            'title' => 'Корзина',
            'sum_order' =>  $sum_order,
        ];

        return view('basket', $date);
    }
}
