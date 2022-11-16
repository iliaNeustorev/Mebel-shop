<?php

namespace App\Jobs;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Pusher\Pusher;

class Search implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */public string $search;

    public function __construct(string $search)
    {
        $this->search = $search;
    }

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
        $res = Product::search($this->search)->get();
        $pusher->trigger('general','search', $res);
    }
}
