<?php

namespace App\Services;

use App\Jobs\ZenviaSendNewAccount;
use App\Models\ZenviaJob;
use App\Services\Helper;
use App\Services\ZenviaClient;

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
        $rows = json_decode( $data );
        foreach( $rows as $row)
        {
            $items[] = [
                'arg'   => null,
                'phone' => $row->phone,
            ];
        }
        return $items;
    }

    public function prepareSmsDataWithArgs( $data ): array
    {
        $items  = [];
        $rows = json_decode( $data );
        foreach( $rows as $row)
        {
            $items[] = [
                'arg'   => $row->arg,
                'phone' => $row->phone,
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
                    'phone' => $chunk,
                    'arg'   => $arg
                ])
            ]);
        }
        ZenviaSendNewAccount::dispatch();
    }

}
