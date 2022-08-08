<?php

namespace App\Services;

use App\Models\ZenviaJob;
use App\Services\Helper;
use App\Services\ZenviaClient;

class ZenviaHelper {

    private static $instance;

    public function __construct()
    {
        
    }

    public static function getInstance()
    {
        if(self::$instance === null){
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function prepareSmsData( $data )
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

    public function prepareSmsDataWithArgs( $data )
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

    public function jobToEnqueue( $type, $items, $arg = null )
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
    }

}