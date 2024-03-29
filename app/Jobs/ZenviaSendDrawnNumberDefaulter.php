<?php

namespace App\Jobs;

use App\Models\ZenviaJob;
use App\Services\ZenviaClient;
use App\Services\ZenviaHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ZenviaSendDrawnNumberDefaulter implements ShouldQueue
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
            $jobs       = ZenviaJob::where('type', ZenviaClient::DRAWN_DEFAULTER_TYPE)->where('processed', false)->get();
            $zenvia     = new ZenviaClient();
            foreach ($jobs as $job) {
                $zenvia->sendSMS( $job->type, ZenviaHelper::getInstance()->prepareSmsData( $job->data ) );
                $job->processed = true;
                $job->save();
            }
        } catch (\Exception $e) {
            Log::error("Erro ao enviar SMS [ Número sorteado - inadimplente ] - " . $e->getMessage() );
        }
    }
}
