<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index ()
    {
        $users = User::get();
        $data = [
            'users' => $users,
            'title' => 'Администрирование'
        ];
        return view('admin/home', $data);
    }

    public function enterAsUser ($userId)
    {
        Auth::loginUsingId($userId);
        session()->flash('another_user');
        return redirect()->route('home');
    }

    public function get_categories()
    {
        $categories = Category::get();
        $data = [
            'id' => NULL,
            'name' =>  NULL,
            'description' => NULL,
            'categories' => $categories,
            'caption' => 'Добавление категории',
            'title' => 'Список категорий',
            'button' => 'добавить категорию',
        ];
        return view('admin/categories', $data); 
    }

    public function add_category(Request $request)
    {
        $categories = Category::get();

        $request->validate([
            'picture' => 'image',
            'name' => 'required|max:255|alpha_num',
            'description' => 'required|max:1000'
        ]);

        $input = $request->all();
        $file = $request->file('picture');
            if($file) 
                {
                    $ext = $file->getClientOriginalExtension();
                    $file_name = time(). mt_rand(1000, 9999) . '.' . $ext;
                    $file->storeAs('public/img/categories', $file_name);
                }

        if ($request->input('id') == NULL) {

        if ($categories->contains('name', $input['name'])) {
            session()->flash('error_updated_category');
            return back();
        }
            $category = new Category();

            if(isset($file_name)) 
            {    
                $category->picture = $file_name;
            }
           
            $category->name = $input['name'];
            $category->description = $input['description'];
            $category->picture = 'nopicture';
            $category->save();
            session()->flash('category_add');
        }

            if(isset($file_name)) 
            {
                $new_picture = $file_name;
            }
            else {
                $new_picture = $categories->find($input['id'])->picture;
            }

        Category::where('id', $input['id'])->update([
                 'name' => $input['name'],
                 'description' => $input['description'],
                 'picture' => $new_picture,
            ]);

        session()->flash('category_updated');
        return back();
        }
    
        public function get_category(Category $category)
        {
            $data = [
                'show_picture' => $category->picture,
                'name' =>  $category->name,
                'description' =>  $category->description,
                'id' => $category->id,
                'title' => 'Редактирование категории',
                'caption' => "Редактирование категории - $category->name",
                'button' => 'Принять изменения',
            ];
            return view('admin/categories', $data); 
        }

        public function get_products()
        {
            $categories = Category::get();
            $products = Product::get()->sortBy('created_at', SORT_DESC, 3);
            $data = [
                'categories' =>  $categories,
                'title' => 'Список продуктов',
                'products' => $products,       
            ];
            return view('admin/products', $data); 
        }

        public function get_product(Category $category, $id = null)
        {
            $categories = Category::where('id','!=', $category->id)->get();
                if ($id != null) { 
                $product = Product::find($id);
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
                $name = $input['name'];
                session()->flash('status_product', "Продукт $name отредактирован");
            }
            return back();
        }

        public function del_product (Request $request) {
            $res = $request->input('check_delete',[]);
            $product = Product::whereIn("id",$res);
            $name = $product->pluck('name');
            $product->delete();
            session()->flash('status_product', "Продукт $name удален");
            return back();
        }
        
}
