<?php

namespace App\Jobs;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Pusher\Pusher;

class importProducts implements ShouldQueue
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
    $file_name ='storage/app/public/products.csv';
    $file = fopen($file_name, 'r');
    $categories = Category::all();
    $categories = $categories->mapWithKeys(function ($item) {
        return [$item->name => $item->id];
    })->toArray();
    $i = 0;
    $j = 0;
    $insert = [];
    while($data = fgetcsv($file, 1000, ';'))
    {
        $count = count($data);
        if($i++ == 0) continue;
        $id = empty($data[0]) ? null : $data[0];
        $picture = empty($data[4]) ? 'nopicture.png' : $data[4];
        $insert[] = [
            'id' => $id,
            'name' => $data[1],
            'description' => $data[2],
            'price' => $data[3],
            'picture' => $picture,
            'category_id' => $categories[$data[5]],
        
        ];
        $percent = round($j++ / $count * 100);
        $pusher->trigger('counter','ImportProductsCounter', $percent);
        sleep(1);
    }
    Product::upsert($insert,['id'],['name','description','price','picture','category_id']);
    fclose($file);
    $pusher->trigger('general','products-import-finish', ['message' => 'Загрузка завершена']);
    Storage::delete("public/products.csv");
    }
}
