<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
    protected $description = 'Fetct today attendance trace';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        \Log::info("fetch attendance trace today execution!");

        return Command::SUCCESS;
    }
}
