<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Jobs\ExportCategories;
use App\Jobs\ExportProducts;
use App\Jobs\ImportCategories;
use App\Jobs\importProducts;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class MainController extends Controller
{
    //filter by users
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

    //enter as user
    public function enterAsUser ($userId)
    {
        return Auth::loginUsingId($userId);
    }

    //export categories in csv file
    public function exportCategories () 
    {
        ExportCategories::dispatch();
        return response()->json(
            ['ОК'],
        200);
    }

    //import categories of csv file
    public function importCategories (Request $request) 
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt',
        ]);
        $file = $request->file('file');
        Storage::putFileAs('public',$file, 'categories.csv');
        ImportCategories::dispatch();
        return response()->json(
            ['ОК'],
        200);
    }

    //export products in csv file
    public function ExportProducts () 
    {
        ExportProducts::dispatch();
        return response()->json(
            ['ОК'],
        200);
    }

    //import products in csv file
    public function importProducts (Request $request)  
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt',
        ]);
        $file = $request->file('file');
        Storage::putFileAs('public',$file, 'products.csv');
        ImportProducts::dispatch();
        return response()->json(
            ['ОК'],
        200);
    }

}
