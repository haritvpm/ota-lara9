<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PunchingService;
use Carbon\Carbon;

class fetchAttendanceYesterday extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:attendanceyesterday';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches successful attendance and trace for yesterday using api5';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        \Log::info("fetch attendance yesterday execution!");
        return Command::SUCCESS;
    }
}
