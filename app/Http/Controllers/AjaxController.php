<?php

namespace App\Http\Controllers;

use App\Services\Importer;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function asyncGetImportStatus(Request $request)
    {
        return [
            'complete' => true,
            'html' => "processado com sucesso"
        ];
    }

    public function asyncRunImportCustomers(Request $request): array
    {
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
        try {
            $importer = new Importer(session("importedFile"), session("importedCategory"));
            $importer->importOrders();
            return ["success" => true];
        } catch (\Exception $e) {
            return ["success" => false];
        }
    }


}
