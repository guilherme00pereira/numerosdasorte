<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function asyncGetImportStatus(Request $request)
    {
        return [
            'complete'  => true,
            'html'      => "processado com sucesso"
        ];
    }

    public function asyncRunImport(Request $request)
    {
        /* $type   = $request->get('type');
        if ('customers' === $type) {
            $importer = new Importer($this->savedFile);
            $importer->importCustomers();
        } else {
            $importer = new Importer($this->savedFile, $this->categories);
            $importer->importOrders();
        } */
        return [];
    }
}
