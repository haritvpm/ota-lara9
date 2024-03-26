<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
// use App\Queue\Middleware\RateLimited;
use Illuminate\Queue\Middleware\RateLimited;

class AebasFetch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $date;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($date)
    {
        $this->date = $date;
    }

    // public function getJobGroup() {
    //     // using default group name
    //     return "default";
    // }
    // public function middleware() {
    //     return [new RateLimited()];
    // }
    // public function middleware()
    // {
    //     return [new RateLimited('fetchaebas')];
    // }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::info ('processing AebasFetch' . $this->date);
    }
}
