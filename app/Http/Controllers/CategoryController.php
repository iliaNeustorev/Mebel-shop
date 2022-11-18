<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return  App\Models\Category
     */

    public function getCategories()
    {
        return Category::paginate(4);
    }

    public function getShortCategories()
    {
        $shortCategories = collect([]);
        Category::All()->map(function ($elem) use ($shortCategories) {
            $shortCategories->put($elem->id, $elem->name);
        });
        return $shortCategories;     
    }

    public function category(Category $category)
    {
        return view('category', compact('category')); 
    }

    
}
