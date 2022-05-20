<?php

namespace App\Http\Controllers;

use App\Jobs\ExportCategories;
use App\Jobs\ExportProducts;
use App\Jobs\ImportCategories;
use App\Jobs\importProducts;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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
                    $ext = $file->getClientOriginalExtension();
                    $file_name = time(). mt_rand(1000, 9999) . '.' . $ext;
                    $file->storeAs('public/img/categories', $file_name);
                }

            $category = new Category();

            if(isset($file_name)) 
            {    
                $category->picture = $file_name;
            }
            else 
            {
                $category->picture = 'nopicture.png';
            }

            $category->name = $input['name'];
            $category->description = $input['description'];   
            $category->save();
     
        return true;
    }
    public function update (Request $request) {

        $request->validate([
            'name' => 'required|max:255|',
            'description' => 'required|max:1000',
        ]);

        $input = $request->all();
        $file = $request->file('picture');
            if($file)
                {
                $request->validate([
                    'picture' => 'image',
                ]);
                    $ext = $file->getClientOriginalExtension();
                    $file_name = time(). mt_rand(1000, 9999) . '.' . $ext;
                    $file->storeAs('public/img/categories', $file_name);
                }

            if(isset($file_name)) 
            {
                $new_picture = $file_name;
                Category::where('id', $input['id'])->update(['name' => $input['name'], 'description' => $input['description'], 'picture' => $new_picture]);
                return true;
            }

            Category::where('id', $input['id'])->update(['name' => $input['name'], 'description' => $input['description']]);
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

    public function get_products ()
    {
        $categories = Category::get();
        $products = Product::paginate(5);
        $products->sortBy('created_at', SORT_DESC, 3);
        $data = [
            'categories' =>  $categories,
            'title' => 'Список продуктов',
            'products' => $products,       
        ];

        return view('admin/products', $data); 
    }

    public function get_product (Category $category, $id = null)
    {
        $categories = Category::where('id','!=', $category->id)->get();
        $product = Product::find($id);
            if ($id != null && is_object($product)) { 
                $data = [
                    'id' => $product->id,
                    'name' =>  $product->name,
                    'description' => $product->description,
                    'price' => $product->price,
                    'category' => $product->category,
                    'categories' => $categories,
                    'picture' => $product->picture,
                    'button' => 'Редактировать продукт',
                ];
            } else {
                $data = [
                    'id' => NULL,
                    'name' =>  NULL,
                    'description' => NULL,
                    'price' => NULL,
                    'category' => NULL,
                    'categories' => $categories,
                    'picture' => NULL,
                    'button' => 'Добавить новый продукт',
                ];
            }
           
        return view('admin/product',compact('category'), $data); 
    }

    public function add_product (Request $request)
    {
        $request->validate([
            'picture' => 'image',
            'name' => 'required|max:255|alpha_num',
            'description' => 'required|max:1000',
            'price' => 'required|numeric|max:30000000',
            'category' => 'required',
        ]);

        $input = $request->all();
        $file = $request->file('picture');
        $product = new Product();

        if($file) 
            {
                $ext = $file->getClientOriginalExtension();
                $file_name = time(). mt_rand(1000, 9999) . '.' . $ext;
                $file->storeAs('public/img/products', $file_name);

                $product->picture = $file_name;
            }
        else 
            {
                $product->picture = 'nopicture.png';
            }
    
        $product->name = $input['name'];
        $product->description = $input['description'];
        $product->price = $input['price'];
        $product->category_id = $input['category'];
        $product->save();
        session()->flash('product_add');
        return back();
    }

    public function upd_product (Request $request)
    {
            
        $request->validate([
            'picture' => 'image',
            'name' => 'required|max:255|string',
            'description' => 'required|max:1000',
            'price' => 'required|numeric|max:30000000',
            'category' => 'required',
        ]);

        $input = $request->all();
        $file = $request->file('picture');

        if($file) 
            {
                $ext = $file->getClientOriginalExtension();
                $file_name = time(). mt_rand(1000, 9999) . '.' . $ext;
                $file->storeAs('public/img/products', $file_name);
            }

        if ($input['id'] == null) {
            
            $product = new Product();

            if(isset($file_name)) 
                {
                    $product->picture = $file_name;
                }
                else 
                {
                    $product->picture = 'nopicture.png';
                }
            
            $name = $input['name'];
            $product->name = $input['name'];
            $product->description = $input['description'];
            $product->price = $input['price'];
            $product->category_id = $input['category'];
            $product->save();
            session()->flash('status_product', "Продукт $name добавлен");
        } else {
            $product =  Product::find($input['id']);

                if(isset($file_name)) 
                    {
                        $new_picture = $file_name;
                    }
                    else 
                    {
                        $new_picture =$product->picture;
                    }

            Product::where('id', $input['id'])->update([
                'name' => $input['name'],
                'description' => $input['description'],
                'price' => $input['price'],
                'category_id' => $input['category'],
                'picture' => $new_picture,
            ]);
            
            session()->flash('status_product', "Продукт отредактирован");
        }
        return back();
    }

    public function del_product (Request $request) 
    {
        $res = $request->input('check_delete',[]);
        Product::whereIn("id",$res)->delete();
        session()->flash('status_product', "Удаление успешно выполнено");
        return back();
    }
        
}
