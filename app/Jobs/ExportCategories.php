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

class ExportCategories implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;

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
        Storage::delete('public/exportCategories.csv');
        $categories = Category::get()->toArray();
        $count = count($categories);
        $columns = [
           'id',
           'name',
           'description',
           'picture',
           'created_at',
           'updated_at' 
        ];
            Storage::append('public/exportCategories.csv',implode(';',$columns));
            $i = 1;
        foreach ($categories as $category) {
            $category['name']  = iconv('utf-8', 'windows-1251//IGNORE', $category['name']);
            $category['description']  = iconv('utf-8', 'windows-1251//IGNORE', $category['description']);
            $category['picture']  = iconv('utf-8', 'windows-1251//IGNORE', $category['picture']);
            Storage::append('public/exportCategories.csv',implode(';',$category));
            $percent = round($i++ / $count * 100, 2);
            $pusher->trigger('counter','ExportCategoriesCounter', $percent);
            sleep(1);
        }
        $pusher->trigger('general','categories-export-finish', ['message' => 'exportCategories.csv']);
    }
}
