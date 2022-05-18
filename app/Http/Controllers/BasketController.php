<?php

namespace App\Http\Controllers;

use App\Mail\OrderCreated;
use App\Mail\WelcomeToRegistration;
use App\Models\Address;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
        return [
            'quantity'=> $products[$id],
            'basketProductsQuantity' => collect($products)->sum()
        ];
    }

    public function remove()
    {
        $id = request('id');
        $products = session('products', []);

        try {
            if ($products[$id] == 0) {
                unset($products[$id]);
            } else {
                $products[$id] -= 1;
            }
        } catch (Exception $e) {
            Log::info("Нажали на кнопку '-' когда товара не было в корзине {$id}");
        }
        session()->put('products', $products);
        session()->save();
        return [
            'quantity'=> $products[$id],
            'basketProductsQuantity' => collect($products)->sum()
        ];
    }

    public function index () 
    {
        $products = session('products',[]);
        foreach($products as $key => $product){
            if ($product == 0){
              unset($products[$key]);
            }
        }
        session()->put('products', $products);
        session()->save();
        $main_address = null;
        $email = null;
        $name = null;
        $user = Auth::user();
        if ($user) {
            $main_address = Address::where([
                'user_id' => $user->id,
                'main' => true
                ])->first();
            $email = $user->email;
            $name = $user->name;
            $main_address =  $main_address->address;
        }
        $basket_products = collect($products)->map( function ($quantity, $id) {
            $product = Product::find($id);
            return [
                'id' => $product->id,
                'title' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity,
            ];
        })->values();
        $sum_order =  $basket_products->map( function ($product) {
            return $product['price'] * $product['quantity'];     
        })->sum();
        $date = [
            'products' => $basket_products,
            'sumOrder' =>  $sum_order,
            'mainAddress' =>  $main_address,
            'email' => $email,
            'name' => $name,
        ];

        return $date;
        
    }
    public function getProductsQuantity () {
        $products = session('products', []);
        return collect($products)->sum();
    }

    public function create_order (Request $request)
    {
        $user = Auth::user();
        $unique_rule = $user ? "unique:users,email,{$user->id}" : "unique:users" ;
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'email' => "required|email|$unique_rule",
        ]);

        if(!$user) {
            $password = $this->generate_password(4,8);
            $name = request('name');
            $email = request('email');
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
            ]);
            Address::create([
                'user_id' => $user->id,
                'address' => request('address'),
                'main' => 1,
            ]);
            
            Auth::loginUsingId($user->id);
            $data = [
                'user' => $user,
            ];

            Mail::to($email)->send(new WelcomeToRegistration($data));
        }

        $address = Address::where([
            'user_id' => $user->id,
            'main' => true,
            ])->first();

        $order = Order::create([
            'user_id' => $user->id,
            'address_id' => $address->id,
        ]);
        $products = collect();
        
        collect(session('products'))->each(function($quantity, $id) use ($order, $products){
            $product = Product::find($id);
            $order->products()->attach($product, [
                'quantity' => $quantity,
                'price' => $product->price,
            ]);

            $products->push([
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity,
                ]);
        });

        $sum_order =  $products->map( function ($product) {
            return $product['price'] * $product['quantity'];     
        })->sum();
       
        $email =  $user ? $user->email : $request['email'];
        $data = [
            'user' => [
                'email' => $email,
                'name' => $user ? $user->name : $request['name'],
            ],
            'products' => $products,
            'sum_order' => $sum_order,
        ];

        Mail::to($email)->send(new OrderCreated($data));

        session()->forget('products');
    }

    protected function generate_password ($type, $lenght) 
    {   
        switch ($type) {
            case 1:
                $input = '123456790';
                break;

            case 2:
                $input =  $input = '0123456790qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
                break;

            case 3:
                $input = '0123456790qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM!@#$%';
                break;
            case 4:
                $input = '0123456790qwertyuiopasdfghjklzxcvbnm';
                break;

            default: {
                $input = null;
            }
        }

        return $input ? substr(str_shuffle($input), 0, $lenght): null;
    }
}
