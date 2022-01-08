<?php

namespace App\Http\Controllers;

use App\Models\Category as ModelsCategory;
use Illuminate\Http\Request;

class Category extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return  App\Models\Category
     */

    public function category(ModelsCategory $category)
    {
        return view('category', compact('category')); 
    }
   
}
