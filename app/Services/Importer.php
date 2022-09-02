<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Order;
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
    }

    public function importCustomers(): void
    {
        session( [ 'importComplete' => false ] );
        $customersPhones = [];
        try {
            $json = Storage::get($this->file);
            $importedCustomers = json_decode($json);
            $total = $this->checkImporterdData($importedCustomers);
            $role = Role::where('slug', 'cliente')->first();
            foreach ($importedCustomers as $key => $customer) {
                Log::channel("import")->info( "importando " . (intval($key) + 1) . " de " . $total . " clientes" );
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

                    $customersPhones[] = Helper::formatPhoneInternational( $customer->telefone );
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
            if( count( $customersPhones ) > 0 ) {
                ZenviaHelper::getInstance()->jobToEnqueue( ZenviaClient::NEW_ACCOUNT_TYPE, $customersPhones );
            }
            Log::channel("import")->info( "Importação dos clientes processada." );
            Log::info("Importação dos clientes processada.");
        } catch (\Exception $e) {
            Log::channel("import")->info( "ocorreu um erro ao importar os clientes: " . $e->getMessage() );
            Log::error("Erro importação de clientes: - " . $e->getMessage());
        } finally {
            session( [ 'importComplete' => true ] );
        }
    }

    public function importOrders(): void
    {
        try {
            $json = Storage::get($this->file);
            $importedOrders = json_decode($json);
            $total = $this->checkImporterdData($importedOrders);
            foreach ($importedOrders as $key => $order) {
                Log::channel("import")->info( "importando " . (intval($key) + 1) . " de " . $total . " pedidos" );
                $newOrder = $this->saveOrder( $order );
                if( !is_null( $newOrder ) ) {
                    $assigner = new NumbersAssigner($newOrder);
                    $assigner->setCategories($this->categories)->process();
                }
            }
            Log::info("Importação dos pedidos processada.");
        } catch (\Exception $e) {
            Log::error("Erro importação de pedidos: - " . $e->getMessage());
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
            return null;
        }
    }

    /**
     * @param mixed $importedCustomers
     * @return int
     */
    private function checkImporterdData(mixed $importedCustomers): int
    {
        $total = 0;
        if (is_object($importedCustomers)) {
            $total = 1;
        } else {
            $total = count($importedCustomers);
        }
        return $total;
    }
}
