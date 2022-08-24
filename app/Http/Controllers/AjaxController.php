<?php

namespace App\Http\Controllers;

use App\Services\Importer;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function asyncGetImportStatus(Request $request): array
    {
        $file = storage_path('logs/import.log');
        if(!file_exists($file)) {
            file_put_contents($file, "");
        }
        $html       = file_get_contents($file);
        $complete   = session("importComplete");
        return [
            'complete'  => $complete,
            'html'      => $html
        ];
    }

    public function asyncRunImportCustomers(Request $request): array
    {
        set_time_limit(0);
        try {
            $importer = new Importer(session("importedFile"));
            $importer->importCustomers();
            return ["success" => true];
        } catch (\Exception $e) {
            return ["success" => false];
        }
    }

    public function asyncRunImportOrders(Request $request): array
    {
        set_time_limit(0);
        try {
            $importer = new Importer(session("importedFile"), session("importedCategory"));
            $importer->importOrders();
            return ["success" => true];
        } catch (\Exception $e) {
            return ["success" => false];
        }
    }


}
