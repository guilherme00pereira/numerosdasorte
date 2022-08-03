<?php

namespace App\Jobs;

use App\Models\ZenviaJob;
use App\Services\ZenviaClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ZenviaSendNewAccount implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $jobs = ZenviaJob::where('type', ZenviaJob::NEW_ACCOUNT_TYPE)->where('processed', false)->get();
            $zenvia = new ZenviaClient();
            $zenvia->sendSMS( $jobs->type, $jobs->data, true );
        } catch (\Exception $e) {
            Log::error( $e->getMessage() );
        }
    }
}
