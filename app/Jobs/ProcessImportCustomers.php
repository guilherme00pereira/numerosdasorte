<?php

namespace App\Jobs;

use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Orchid\Platform\Models\Role;
use Orchid\Platform\Models\User;


class ProcessImportCustomers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $file;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        try {
            $json = Storage::get($this->file);
            $importedCustomers = json_decode($json);
            $role = Role::where('slug', 'cliente')->first();
            foreach ($importedCustomers as $customer) {
                $existingCustomer = Customer::where('cpf', $customer->cpf)->first();
                if (is_null($existingCustomer)) {
                    $checkedMail = self::checkEmail($customer->email, $customer->id_cliente);
                    $user = User::where('email', $customer->email)->first();
                    if (is_null($user)) {
                        $user = User::create([
                            'name' => $customer->nome,
                            'email' => $checkedMail,
                            'password' => Hash::make('$enha@123'),
                            'permissions' => $role->permissions
                        ]);
                        $user->addRole($role);
                    }

                    Customer::create([
                        'external_code' => $customer->id_cliente,
                        'user' => $user->id,
                        'cpf' => $customer->cpf,
                        'name' => $customer->nome,
                        'email' => $checkedMail,
                        'birthdate' => self::formatDate($customer->data_nascimento),
                        'phone' => $customer->telefone,
                        'city' => $customer->cidade,
                        'state' => $customer->estado,
                        'defaulter' => strtolower($customer->status) === 'inadimplente'
                    ]);

                } else {
                    $existingCustomer->cpf = $customer->cpf;
                    $existingCustomer->name = $customer->nome;
                    $existingCustomer->birthdate = self::formatDate($customer->data_nascimento);
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

    private static function checkEmail($email, $code): string
    {
        if (is_null($email)) {
            return $code . "@brvitapremios.com.br";
        }
        return $email;
    }

    private static function formatDate($value): \DateTime|bool
    {
        $value = str_replace("\\", "", $value);
        return \DateTime::createFromFormat('m/d/Y', $value);
    }
}
