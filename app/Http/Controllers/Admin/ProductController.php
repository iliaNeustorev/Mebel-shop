<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    // get all products
    public function getProducts () : object
    {
        return Product::paginate(10);
    }

    // get one product
     public function getProduct ($id) : object
     {
        return Product::findOrFail($id);
     }
 
     //add new Product
     public function addProduct (Request $request) : bool
     {
         $input = $request->all();
         $request->validate([
             'name' => ['required','max:255', Rule::unique('products','name')->where('category_id', $input['category_id'])],
             'description' => 'required|max:1000|min:10',
             'price' => 'required|numeric|max:30000000',
             'category_id' => 'required',
         ]);
 
         $file = $request->file('picture');
         $product = new Product();
 
         if($file)
             {
                 $request->validate([
                     'picture' => 'image',
                 ]);
                 $ext = $file->Extension();
                 $file_name = time(). mt_rand(1000, 9999) . '.' . $ext;
                 Storage::putFileAs('public/img/products/', $file, $file_name);
                 $product->picture = $file_name;
             }
         else 
             {
                 $product->picture = 'nopicture.png';
             }
         
         $product->name = $input['name'];
         $product->description = $input['description'];
         $product->price = $input['price'];
         $product->category_id = $input['category_id'];
         $product->save();
         return true;
     }
 
     //update product
     public function updProduct (Request $request) : bool
     {           
         $request->validate([
             'name' => 'required|max:255|string',
             'description' => 'required|max:1000|min:10',
             'price' => 'required|numeric|max:30000000',
             'category_id' => 'required',
         ]);
 
         $input = $request->all();
         $file = $request->file('picture');
         $product = Product::where("id", $input['id'])->firstOrFail();
         if($file) 
             {
                 $request->validate([
                     'picture' => 'image',
                 ]);
                 Storage::delete("public/img/products/$product->picture");
                 $ext = $file->Extension();
                 $file_name = time(). mt_rand(1000, 9999) . '.' . $ext;
                 Storage::putFileAs('public/img/products/', $file, $file_name);
                 $product->update([
                     'name' => $input['name'],
                     'description' => $input['description'],
                     'price' => $input['price'],
                     'category_id' => $input['category_id'],
                     'picture' => $file_name,
                 ]);
             } else {
                     $product->update([
                         'name' => $input['name'],
                         'description' => $input['description'],
                         'price' => $input['price'],
                         'category_id' => $input['category_id'],
                     ]);
                 }
         
         return true;
        }
        
        //delete array products
        public function delProducts (Request $request) : array
        {   
            
            $res = $request->input('idProductsDelete');
            Product::whereIn("id",$res)->delete();
            
            if($request->input('categoryId')) {
                $category = Category::where('id', $request->input('categoryId'))->first();
                $products = $category->products->sortBy('created_at', SORT_DESC, 3)->values()->all();
            } else {
                $products = Product::get();
            }
            return [ 'products' => $products ];
        }
}
