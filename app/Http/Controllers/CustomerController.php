<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use App\Services\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchid\Support\Facades\Alert;

class CustomerController extends Controller
{
    public function index( Request $request )
    {
        if( $request->isMethod('post') ) {
            $cpf = $request['cpf'];
            if( Validator::validaCPF( $cpf ) )
            {
                $customer = Customer::where('cpf', $cpf)->first();
                if( is_null( $customer ) ) {
                    Alert::warning("CPF não cadastrado no sistema.");
                } else {
                    $user = User::find($customer->user);
                    Auth::login( $user );
                    return redirect('/admin/painel-cliente');
                }

            } else {
                Alert::error("CPF inválido");
            }
        }
        return view("auth-customer");
    }
}
