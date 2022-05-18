<?php

namespace App\Http\Controllers;

use App\Models\Category;

class ProductController extends Controller
{
    public function getProducts (Category $category) 
    {
        $products = $category->products;
        $basketProducts = session('products');
        return $products->transform(function($product) use ($basketProducts) {
            $product->quantity = $basketProducts[$product->id] ?? 0;
            return $product;
        });
    }
}
