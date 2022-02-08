<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return  App\Models\Category
     */

    public function getCategories()
    {
        $categories = Category::get();
        return $categories; 
    }

    public function category(Category $category)
    {
        return view('category', compact('category')); 
    }

    
}
