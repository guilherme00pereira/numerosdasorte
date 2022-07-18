<?php

namespace App\Services;

use App\Models\Customer;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Orchid\Platform\Models\Role;
use Orchid\Platform\Models\User;

class ImportCustomers
{

    /**
     * @throws Exception
     */
    public static function process( $file ): void
    {
        try {
            $json               = Storage::get( $file );
            $importedCustomers  = json_decode($json);
            $role               = Role::where('slug', 'cliente')->first();
            foreach ($importedCustomers as $customer) {
                $existingCustomer = Customer::where('email', $customer->email)->first();
                if( is_null( $existingCustomer ) ) {
                    $user = User::create([
                        'name'          => $customer->nome,
                        'email'         => $customer->email,
                        'password'      => Hash::make('$enha@123'),
                        'permissions'   => $role->permissions
                    ]);
                    $user->addRole($role);

                    Customer::create([
                        'external_code' => $customer->id,
                        'user'          => $user->id,
                        'cpf'           => $customer->cpf,
                        'name'          => $customer->nome,
                        'email'         => $customer->email,
                        'birthdate'     => $customer->data_nascimento,
                        'phone'         => $customer->telefone,
                        'city'          => $customer->cidade,
                        'state'         => $customer->estado,
                        'defaulter'     => $customer->status === 'inadimplente'
                    ]);

                } else {
                    $existingCustomer->cpf          = $customer->cpf;
                    $existingCustomer->name         = $customer->nome;
                    $existingCustomer->birthdate    = $customer->data_nascimento;
                    $existingCustomer->phone        = $customer->telefone;
                    $existingCustomer->city         = $customer->cidade;
                    $existingCustomer->state        = $customer->estado;
                    $existingCustomer->defaulter    = $customer->status === 'inadimplente';
                    $existingCustomer->save();
                }

            }
        } catch (Exception $e) {
            throw $e;
        }
    }

}
