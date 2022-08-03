<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use App\Services\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Orchid\Support\Facades\Alert;

class CustomerController extends Controller
{
    public function index( Request $request )
    {
        try {
            if ($request->isMethod('post')) {
                $cpf = $request['cpf'];
                if (Helper::validateCPF($cpf)) {
                    $customer = Customer::where('cpf', $cpf)->first();
                    if (is_null($customer)) {
                        Alert::warning("CPF não cadastrado no sistema.");
                    } else {
                        $user = User::find($customer->user);
                        Auth::login($user);
                        return redirect('/admin/painel-cliente');
                    }

                } else {
                    Alert::error("CPF inválido");
                }
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        return view("auth-customer");
    }
}
