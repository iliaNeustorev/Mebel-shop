<?php

namespace App\Jobs;

use App\Models\Category;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Pusher\Pusher;

class ImportCategories implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $pusher = new Pusher('app-key', 'app-secret', 'app-id', [
            'host' => '127.0.0.1',
            'port' => 6001,
            'scheme' => 'http',
            'encrypted' => true,
            'useTLS' => false,
        ]);
        $file_name ="storage/app/public/categories.csv";
        $file = fopen($file_name, 'r');
        $i = 0;
        $j = 0;
        $insert = [];
        while($data = fgetcsv($file, 1000, ';'))
        {
            $count = count($data);
            if($i++ == 0) continue;
            $id = empty($data[0]) ? null : $data[0];
            $insert[] = [
                'id'=> $id,
                'name' => $data[1],
                'description' => $data[2],
                'picture' => $data[3],
            ];
            $percent = round($j++ / $count * 100);
            $pusher->trigger('counter','ImportCategoriesCounter', $percent);
            sleep(1);
        }
        Category::upsert($insert,['id'],['name','description','picture']);
        fclose($file);
        $pusher->trigger('general','categories-import-finish', ['message' => 'Загрузка завершена']);
        Storage::delete("public/categories.csv");
    }
}
