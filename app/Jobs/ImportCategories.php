<?php

namespace App\Jobs;

use App\Models\Category;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ImportCategories implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $file_name ="storage/app/public/categories.csv";
        $file = fopen($file_name, 'r');
        $i = 0;
        $insert = [];
        while($data = fgetcsv($file, 1000, ';'))
        {
            if($i++ == 0) continue;
            $id = empty($data[0]) ? null : $data[0];
            $insert[] = [
                'id'=> $id,
                'name' => $data[1],
                'description' => $data[2],
                'picture' => $data[3],
            ];
        }
        Category::upsert($insert,['id'],['description','picture']);
        fclose($file);
        Storage::delete("public/categories.csv");
    }
}
