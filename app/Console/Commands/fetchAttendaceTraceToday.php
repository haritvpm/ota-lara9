<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PunchingService;

class fetchAttendaceTraceToday extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:attendancetracetoday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch today attendance trace using api4';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        \Log::info("fetch attendance trace today execution!");
        (new PunchingService())->fetchTodayTrace();
        return Command::SUCCESS;
    }
}