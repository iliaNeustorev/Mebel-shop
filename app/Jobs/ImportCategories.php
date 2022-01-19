<?php

namespace App\Jobs;

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportCategories implements ShouldQueue
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
        $file_name ='categories.csv';
        $file = fopen($file_name, 'r');

        $carbon = new Carbon();

        $time = $carbon->now()->toDateTimeString();

        $i = 0;
        $insert = [];
        while($data = fgetcsv($file, 1000, ';'))
        {
        if($i++ == 0) continue;
        $insert[] = [
            'name' => $data[0],
            'description' => $data[1],
            'picture' => $data[2],
            'created_at' => $time,
            'updated_at' => $time
        ];
        }
        Category::insert($insert);
    }
}
