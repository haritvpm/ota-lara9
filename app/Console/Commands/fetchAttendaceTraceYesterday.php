<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PunchingService;
use Carbon\Carbon;

class fetchAttendaceTraceYesterday extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:attendancetraceyesterday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches complete data of yesterday trace using api 6';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $reportdate = Carbon::yesterday()->format('Y-m-d'); //today
        \Log::info("fetch attendance trace yesterday execution!");
        (new PunchingService())->fetchTodayTrace($reportdate);

        return Command::SUCCESS;
    }
}
