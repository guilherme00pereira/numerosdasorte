<?php

namespace App\Orchid\Screens\Customers;

use App\Models\Customer;
use App\Models\Number;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class ProfileEdit extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        $user           = Auth::user();
        $profile        = Customer::where('user', $user->id)->first();
        $numbers        = Number::where('customer_id', $profile->id)->get();
        return [
            'user'      => $profile,
            'numbers'   => $numbers->count()
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Ver Perfil';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * Views.
     *
     * @return Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::rows([
                Input::make('user.id')->type('hidden'),
                Group::make([
                    Input::make('user.name')
                        ->title('Nome Completo')
                        ->vertical(),
                    Input::make('user.cpf')
                        ->title('CPF')
                        ->vertical()
                ]),
                Group::make([
                    Input::make('user.id')
                        ->title('ID')
                        ->vertical()
                        ->readonly(),
                    DateTimer::make('user.birthdate')
                        ->title('Data de Nascimento')
                        ->format("d/m/Y")
                        ->vertical(),
                    Input::make('numbers')
                        ->title('Quantidade de Números da Sorte')
                        ->vertical()
                        ->readonly()
                ]),
                Group::make([
                    Input::make('user.phone')
                        ->title('Telefone com DDD')
                        ->vertical(),
                    Input::make('user.city')
                        ->title('Cidade')
                        ->vertical()
                ]),
                Group::make([
                    Input::make('user.state')
                        ->title('Estado')
                        ->vertical(),
                    Input::make('user.updated_at')
                        ->title('Última atualização')
                        ->readonly()
                        ->vertical()
                ]),
            ])
        ];
    }

    public function saveProfile( Request $request )
    {
        try {
            $user = $request['user'];
            $profile = Customer::find($user['id']);
            $profile->name = $user['name'];
            $profile->cpf = $user['cpf'];
            $profile->birthdate = $user['birthdate'];
            $profile->phone = $user['phone'];
            $profile->city = $user['city'];
            $profile->state = $user['state'];
            $profile->save();
            Alert::success("Dados do perfil salvos com sucesso");
        } catch (\Exception $e) {
            Alert::error("Erro ao salvar");
        }
    }


}
