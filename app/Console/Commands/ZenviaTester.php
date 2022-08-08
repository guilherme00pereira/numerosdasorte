<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ZenviaJob;
use App\Services\ZenviaClient;
use App\Services\ZenviaHelper;
use Illuminate\Support\Facades\Log;

class ZenviaTester extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zenvia:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Zenvia send SMS';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $jobs  = ZenviaJob::where('type', ZenviaClient::NEW_ACCOUNT_TYPE)->where('processed', false)->get();
        Log::alert( json_decode($jobs[0]->data)->phone );
        return 0;
    }
}
