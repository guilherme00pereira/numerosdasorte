<?php

namespace App\Services;

use App\Jobs\ZenviaSendNewAccount;
use App\Models\ZenviaJob;

class ZenviaHelper {

    private static $instance;

    public function __construct()
    {

    }

    public static function getInstance(): ZenviaHelper
    {
        if(self::$instance === null){
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function prepareSmsData( $data ): array
    {
        $items  = [];
        $sendData = json_decode( $data );
        foreach( $sendData->phones as $phone)
        {
            $items[] = (object)[
                'arg'   => null,
                'phone' => $phone,
            ];
        }
        return $items;
    }

    public function prepareSmsDataWithArgs( $data ): array
    {
        $items  = [];
        $sendData = json_decode( $data );
        foreach( $sendData->phones as $phone)
        {
            $items[] = [
                'arg'   => $sendData->arg,
                'phone' => $phone,
            ];
        }
        return $items;
    }

    public function jobToEnqueue( $type, $items, $arg = null ): void
    {
        $chunks = array_chunk($items, 100);
        foreach($chunks as $chunk) {
            ZenviaJob::create([
                'type'  => $type,
                'data'  => json_encode([
                    'phones' => $chunk,
                    'arg'   => $arg
                ])
            ]);
        }
        ZenviaSendNewAccount::dispatch();
    }

}
