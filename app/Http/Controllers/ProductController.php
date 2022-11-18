<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller
{
    public function getProduct(Product $product)
    {
        $product = Product::where('id',$product->id)->with('category:id,name')->get();
        return $this->getQuantity($product);
    }

    public function getProducts (Category $category) 
    {
        $products = Product::where('category_id',$category->id)->orderByDesc('created_at')->paginate(2);
        $this->getQuantity($products);
        return $products;
        
        
    }
    protected function getQuantity($collection) 
    {
        $basketProducts = session('products');
        return $collection->transform(function($product) use ($basketProducts) {
            $product->quantity = $basketProducts[$product->id] ?? 0;
            return $product;
        });
    }
}
