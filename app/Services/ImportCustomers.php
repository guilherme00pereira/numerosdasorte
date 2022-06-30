<?php

namespace App\Services;

use App\Models\Customer;
use Exception;
use Illuminate\Support\Facades\Storage;

class ImportCustomers
{

    /**
     * @throws Exception
     */
    public static function process($file ): void
    {
        try {
            $customers = [];
            $json = Storage::get($file);
            $importedCustomers = json_decode($json);
            foreach ($importedCustomers as $customer) {
                $customers[] = [
                    'external_code' => $customer->id,
                    'cpf' => $customer->cpf,
                    'name' => $customer->nome,
                    'email' => $customer->email,
                    'birthdate' => $customer->data_nascimento,
                    'phone' => $customer->telefone,
                    'city' => $customer->cidade,
                    'state' => $customer->estado,
                    'defaulter' => $customer->status === 'inadimplente'
                ];
            }
            Customer::upsert(
                $customers,
                ['id', 'external_code'],
                ['cpf', 'name', 'email', 'birthdate', 'phone', 'city', 'state', 'defaulter']
            );
        } catch (Exception $e) {
            throw $e;
        }
    }

}
