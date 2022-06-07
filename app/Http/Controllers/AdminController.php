<?php

namespace App\Http\Controllers;

use App\Jobs\ExportCategories;
use App\Jobs\ExportProducts;
use App\Jobs\ImportCategories;
use App\Jobs\importProducts;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{

    public function showRegUsers ()
    {
        $length = request('length');
        $query = User::query();
        $filters = request('filters');
        $sortColumn = request('sortColumn');
        foreach ($filters as $column => $filter) {
            if($filter['value']) {
                $value = $filter['type'] == 'like' ? "%{$filter['value']}%" : $filter['value'];
                $query->where($column, $filter['type'], $value);
            }
        }
        return $query->orderBy($sortColumn['column'], $sortColumn['direction'])->paginate($length);
    }

    public function enterAsUser ($userId)
    {
        Auth::loginUsingId($userId);
        session()->flash('another_user');
        return redirect()->route('home');
    }

    public function exportCategories () 
    {
        ExportCategories::dispatch();
    }

    public function importCategories () 
    {
        ImportCategories::dispatch();
        session()->flash('startimportCategories');
        return back();
    }

    public function ExportProducts () 
    {
        ExportProducts::dispatch();
    }

    public function importProducts () 
    {
        importProducts::dispatch();
        session()->flash('startimportProducts');
        return back();
    }

    //return categories with products

    public function index ()
    {
        return Category::with('products')->get();
    }

    //create new category , return categories

    public function store (Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|unique:categories,name',
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

    public function update (Request $request) : bool {
        
        $request->validate([
            'name' => 'required|max:255|',
            'description' => 'required|max:1000',
        ]);

        $input = $request->all();
        $category = Category::where('id', $input['id'])->first();
        $file = $request->file('picture');
            if($file)
                {
                $request->validate([
                    'picture' => 'image',
                ]);
                    Storage::delete("public/img/categories/$category->picture");
                    $ext = $file->extension();
                    $file_name = time(). mt_rand(1000, 9999) . '.' . $ext;
                    Storage::putFileAs('public/img/categories/', $file, $file_name);
                    $category->update(['name' => $input['name'], 'description' => $input['description'], 'picture' => $file_name]);
                } else {
                    $category->update(['name' => $input['name'], 'description' => $input['description']]);
                }
            return true;   
    }   

    // delete category_id
    public function destroy ($id) {
        $category = Category::where('id', $id)->first();
        if($category->products->count() == 0) {
            $category->delete();
        }
        return $category->with('products')->get();
    }

    public function edit (Category $category)
    {
        $data = [
            'picture' => $category->picture,
            'name' =>  $category->name,
            'description' =>  $category->description,
            'id' => $category->id,
        ];

        return $data;
    }

    // get all products

    public function getProducts () {
       return Product::get();
    }

    // get products by category_id
    public function getProductsCategory (Category $category)
    {
        $products = $category->products->sortBy('created_at', SORT_DESC, 3)->values()->all();
        return [
            'products' => $products, 
            'categoryName' => $category->name, 
            'categoryId' => $category->id
            ];
    }

    //add new Product
    public function addProduct (Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|unique:products,name',
            'description' => 'required|max:1000',
            'price' => 'required|numeric|max:30000000',
            'categoryId' => 'required',
        ]);

        $input = $request->all();
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
        $product->category_id = $input['categoryId'];
        $product->save();
        return true;
    }

    public function updProduct (Request $request)
    {
            
        $request->validate([
            'name' => 'required|max:255|string',
            'description' => 'required|max:1000',
            'price' => 'required|numeric|max:30000000',
            'categoryId' => 'required',
        ]);

        $input = $request->all();
        $file = $request->file('picture');
        $product = Product::where("id", $input['id'])->first();
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
                    'category_id' => $input['categoryId'],
                    'picture' => $file_name,
                ]);
            } else {
                    $product->update([
                        'name' => $input['name'],
                        'description' => $input['description'],
                        'price' => $input['price'],
                        'category_id' => $input['categoryId'],
                    ]);
                }
        
        return true;
    }

    public function delProducts (Request $request)
    {
        $res = $request->input('idProductsDelete');
        Product::whereIn("id",$res)->delete();
        $category = Category::where('id', $request->input('categoryId'))->first();
        $products = $category->products->sortBy('created_at', SORT_DESC, 3)->values()->all();
        return [
            'products' => $products,
            ];
    }
        
}
