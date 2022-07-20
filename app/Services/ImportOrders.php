<?php

namespace App\Services;

use App\Models\Numbers;
use Exception;
use Illuminate\Support\Facades\Storage;

class ImportOrders
{

    public static function proccess( $file, $raffleIds ): void
    {
        try {
            $json               = Storage::get( $file );
            $importedOrders     = json_decode($json);
            foreach ($importedOrders as $importedOrder) {
                
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

}
