<?php

namespace App\Jobs;

use App\Models\ZenviaJob;
use App\Services\ZenviaClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Services\ZenviaHelper;

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
    public function handle(): void
    {
        try {
            $jobs = ZenviaJob::where('type', ZenviaClient::NEW_ACCOUNT_TYPE)->where('processed', false)->get();
            $zenvia = new ZenviaClient();
            foreach ($jobs as $job) {
                $content = $zenvia->sendSMS($job->type, ZenviaHelper::getInstance()->prepareSmsData($jobs->data));
                Log::info("SMS enviado [ Nova Conta ] - " . $content);
                $job->processed = true;
                $job->save();
            }
        } catch (\Exception $e) {
            Log::error("Erro ao enviar SMS [ Nova Conta ] - " . $e->getMessage() );
        }
    }
}
