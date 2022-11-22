<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;

class CategoryController extends Controller
{

     //return categories with products
     public function index () : object
     {
         return Category::with('products')->paginate(5);
     }
 
     //create new category
     public function store (Request $request) : bool
     {
         $request->validate([
             'name' => 'required|min:4|max:255|unique:categories,name',
             'description' => 'required|max:1000',
         ]);
 
         $input = $request->all();
         $file = $request->file('picture');
             if($file)
                 {
                     $request->validate([
                         'picture' => 'image',
                 ]);
                     $ext = $file->extension();
                     $file_name = time(). mt_rand(1000, 9999) . '.' . $ext;
                     Storage::putFileAs('public/img/categories/', $file, $file_name);
                 }
 
             $category = new Category();
             
             $category->picture = isset($file_name) ? $file_name : 'nopicture.png';
             $category->name = $input['name'];
             $category->description = $input['description'];   
             $category->save();
      
         return true;
     }

     //update category 
     public function update (Request $request) : bool 
     {
         $request->validate([
             'name' => 'required|max:255|min:4',
             'description' => 'required|max:1000',
         ]);
 
         $input = $request->all();
         $category = Category::where('id', $input['id'])->firstOrFail();
         $file = $request->file('picture');
            if($file)
                {
                $request->validate([
                    'picture' => 'image',
                ]);
                    if($category->picture != 'nopicture.png')
                    {
                        Storage::delete("public/img/categories/$category->picture");
                    }
                    $ext = $file->extension();
                    $file_name = time(). mt_rand(1000, 9999) . '.' . $ext;
                    $category->update(['name' => $input['name'], 'description' => $input['description'], 'picture' => $file_name]);
                    Storage::putFileAs('public/img/categories/', $file, $file_name);
                } else {
                    $category->update(['name' => $input['name'], 'description' => $input['description']]);
                }
            return true;   
     }   
 
     // delete by category_id
     public function destroy ($id) : bool 
     {
         $category = Category::where('id', $id)->firstOrFail();
         if($category->products->count() == 0) {
             $category->delete();
         }
         return true;
    }

    //show edit category
     public function edit (Category $category) : array
     {
         $data = [
             'picture' => $category->picture,
             'name' =>  $category->name,
             'description' =>  $category->description,
             'id' => $category->id,
         ];
 
         return $data;
     }
     
    // get products by category_id
    public function getProductsCategory (Category $category) : array
    {
        $products = Product::where('category_id', $category->id)->orderByDesc('created_at')->paginate(5);
        return [
            'products' => $products, 
            'categoryName' => $category->name, 
            'categoryId' => $category->id
            ];
    }
}
