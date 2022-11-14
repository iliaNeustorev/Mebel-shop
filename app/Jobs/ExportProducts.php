<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Pusher\Pusher;

class ExportProducts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
            $pusher = new Pusher('app-key', 'app-secret', 'app-id', [
                'host' => '127.0.0.1',
                'port' => 6001,
                'scheme' => 'http',
                'encrypted' => true,
                'useTLS' => false,
            ]);
            $products = Product::get();
            $productsExport = $products->map( function ($product) {
                $product['category_id'] = $product->category()->value('name');
                return $product;
              })->toArray();
            Storage::delete('public/exportProducts.csv');
            $count = count($productsExport);
            $columns = [
               'id',
               'name',
               'description',
               'price',
               'picture',
               'category_id',
               'created_at',
               'updated_at', 
            ];
            Storage::append('public/exportProducts.csv',implode(';',$columns));
            $i = 1;
            foreach ($productsExport as $product) {
                $product['name']  = iconv('utf-8', 'windows-1251//IGNORE',$product['name']);
                $product['description']  = iconv('utf-8', 'windows-1251//IGNORE', $product['description']);
                $product['price']  = iconv('utf-8', 'windows-1251//IGNORE', $product['price']);
                $product['picture']  = iconv('utf-8', 'windows-1251//IGNORE', $product['picture']);
                $product['category_id']  = iconv('utf-8', 'windows-1251//IGNORE', $product['category_id']);
                Storage::append('public/exportProducts.csv',implode(';',$product));
                $percent = round($i++ / $count * 100);
                $pusher->trigger('counter','ExportProductsCounter', $percent);
            }
            $pusher->trigger('general','products-export-finish', ['message' => 'exportProducts.csv']);
    }
}
