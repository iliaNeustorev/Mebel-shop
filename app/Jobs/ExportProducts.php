<?php

namespace App\Jobs;

use App\Events\ProductsExportFinishEvents;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ExportProducts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        {
            $products = Product::get();
            $productsExport = $products->map( function ($product) {
                $product['name_category'] = $product->category()->value('name');
                return $product;
              })->toArray();
            Storage::delete('public/exportProducts.csv');
            $columns = [
               'id',
               'name',
               'description',
               'price',
               'picture',
               'category_id',
               'created_at',
               'updated_at', 
               'name_category'
            ];
            Storage::append('public/exportProducts.csv',implode(';',$columns));
            foreach ($productsExport as $product) {
                $product['name']  = iconv('utf-8', 'windows-1251//IGNORE',$product['name']);
                $product['description']  = iconv('utf-8', 'windows-1251//IGNORE', $product['description']);
                $product['price']  = iconv('utf-8', 'windows-1251//IGNORE', $product['price']);
                $product['picture']  = iconv('utf-8', 'windows-1251//IGNORE', $product['picture']);
                $product['category_id']  = iconv('utf-8', 'windows-1251//IGNORE', $product['category_id']);
                $product['name_category']  = iconv('utf-8', 'windows-1251//IGNORE', $product['name_category']);
                Storage::append('public/exportProducts.csv',implode(';',$product));
            }
            event(new ProductsExportFinishEvents('exportProducts.csv'));
        }
    }
}
