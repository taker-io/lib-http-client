<?php

namespace TakerIo\HttpClient\Commands;

use Illuminate\Console\Command;

class Clean extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'taker-io:http-logs-clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        app('db')->table('http_logs')
            ->where('created_at', '<', date('Y-m-d', strtotime('-1 months')))
            ->delete();
    }
}
