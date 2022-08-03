<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Order;
use App\Models\ZenviaJob;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Orchid\Platform\Models\Role;
use Orchid\Platform\Models\User;

class Importer
{
    private $file;
    private array $categories;

    public function __construct( $file, $categories = [])
    {
        $this->file             = $file;
        $this->categories       = $categories;
        $this->zenviaCustomers  = [];
    }

    public function importCustomers()
    {
        try {
            $json = Storage::get($this->file);
            $importedCustomers = json_decode($json);
            $role = Role::where('slug', 'cliente')->first();
            foreach ($importedCustomers as $customer) {
                $existingCustomer = Customer::where('external_code', $customer->id_cliente)->first();
                if (is_null($existingCustomer)) {
                    $checkedMail = $this->checkEmail($customer->email, $customer->id_cliente);
                    $user = User::where('email', $checkedMail)->first();
                    if (is_null($user)) {
                        $user = User::create([
                            'name' => $customer->nome,
                            'email' => $checkedMail,
                            'password' => Hash::make('$enha@123'),
                            'permissions' => $role->permissions
                        ]);
                        $user->addRole($role);
                    }

                    $this->newAccountToEnqueue( $customer->telefone );
                    Customer::create([
                        'external_code' => $customer->id_cliente,
                        'user' => $user->id,
                        'cpf' => $customer->cpf,
                        'name' => $customer->nome,
                        'email' => $checkedMail,
                        'birthdate' => $this->formatDate($customer->data_nascimento),
                        'phone' => $customer->telefone,
                        'city' => $customer->cidade,
                        'state' => $customer->estado,
                        'defaulter' => strtolower($customer->status) === 'inadimplente'
                    ]);

                } else {
                    $existingCustomer->cpf = $customer->cpf;
                    $existingCustomer->name = $customer->nome;
                    $existingCustomer->birthdate = $this->formatDate($customer->data_nascimento);
                    $existingCustomer->phone = $customer->telefone;
                    $existingCustomer->city = $customer->cidade;
                    $existingCustomer->state = $customer->estado;
                    $existingCustomer->defaulter = strtolower($customer->status) === 'inadimplente';
                    $existingCustomer->save();
                }
            }
            Log::info("Importação dos clientes processada.");
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function importOrders()
    {
        try {
            $json = Storage::get($this->file);
            $importedOrders = json_decode($json);
            foreach ($importedOrders as $order) {
                $newOrder = $this->saveOrder( $order );
                $assigner = new NumbersAssigner( $newOrder );
                $assigner->setCategories( $this->categories )->process();
            }
            Log::info("Importação dos pedidos processada.");
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    private function checkEmail($email, $code): string
    {
        if (is_null($email)) {
            return $code . "@brvitapremios.com.br";
        }
        return $email;
    }

    private function formatDate($value): \DateTime|bool
    {
        $value = str_replace("\\", "", $value);
        return \DateTime::createFromFormat('m/d/Y', $value);
    }

    private function saveOrder( $order )
    {
        $dbOrder            = Order::where("order_id", $order->id_pedido)->first();
        if( is_null( $dbOrder) ) {
            $customer = Customer::where('external_code', $order->codigo)->first();
            return Order::create([
                'order_id' => $order->id_pedido,
                'value' => $order->valor,
                'num_items' => $order->quant_itens_pedidos,
                'installments' => $order->parcelas,
                'payment_type' => $order->tipo_pagamento,
                'customer_id' => $customer->id
            ]);
        } else {
            return $dbOrder;
        }
    }

    private function newAccountToEnqueue( $phone )
    {
        $this->zenviaCustomers[] = $phone;
        if( count( $this->zenviaCustomers ) == 100 ) {
            ZenviaJob::create([
                'type'  => ZenviaJob::NEW_ACCOUNT_TYPE,
                'data'  => $this->zenviaCustomers
            ]);
            $this->zenviaCustomers = [];
        }
    }
}
