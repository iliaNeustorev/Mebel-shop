<?php

namespace App\Jobs;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class importProducts implements ShouldQueue
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
        $file_name ='products.csv';
        $file = fopen($file_name, 'r');

        $carbon = new Carbon();

        $time = $carbon->now()->toDateTimeString();

        $i = 0;
        $insert = [];
        while($data = fgetcsv($file, 1000, ';'))
        {
        if($i++ == 0) continue;
        $id = $data[0];
            if (empty($data[0])) {
                $id = null;
            }
        $insert[] = [
            'id' => $id,
            'name' => $data[1],
            'description' => $data[2],
            'price' => $data[3],
            'picture' => $data[4],
            'category_id' => $data[5],
            'created_at' => $time,
            'updated_at' => $time
        ];
        }
        Product::upsert($insert,['id'],['name','description','price','picture','category_id']);
        fclose($file);
    }
}
