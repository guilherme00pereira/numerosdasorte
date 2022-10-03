<?php

namespace App\Orchid\Screens\Admin;

use App\Models\Customer;
use App\Models\Raffle;
use Exception;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use phpDocumentor\Reflection\PseudoTypes\Numeric_;

class CustomerEdit extends Screen
{
    public Customer $customer;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Customer $customer): iterable
    {
        return [
            'customer'  => $customer,
            'numbers'   => $customer->numbers()->count()
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Perfil do Cliente';
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make("Cancelar")->route("platform.customers")->type(Color::LIGHT()),
            Button::make('Salvar')->method('saveProfile')->type(Color::PRIMARY()),
            Button::make('Excluir')->method('removeProfile')->type(Color::DANGER()),
        ];
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
                Group::make([
                    Input::make('customer.name')
                        ->title('Nome Completo')
                        ->vertical(),
                    Input::make('customer.cpf')
                        ->title('CPF')
                        ->vertical()
                ]),
                Group::make([
                    Input::make('customer.birthdate')
                        ->title('Data de Nascimento')
                        ->vertical(),
                    Input::make('numbers')
                        ->title('Quantidade de Números da Sorte')
                        ->readonly()
                        ->vertical()
                ]),
                Group::make([
                    Input::make('customer.phone')
                        ->title('Telefone com DDD')
                        ->vertical(),
                    Input::make('customer.city')
                        ->title('Cidade')
                        ->vertical()
                ]),
                Group::make([
                    Input::make('customer.state')
                        ->title('Estado')
                        ->vertical(),
                    Input::make('customer.updated_at')
                        ->title('Última atualização')
                        ->vertical()
                ]),
            ])
        ];
    }

    public function saveProfile( Customer $customer, Request $request )
    {
        try {
            $customer->fill($request->get("customer"))->save();
            Alert::info('Perfil do cliente editado com sucesso.');
        } catch (Exception $e) {
            Alert::error('Erro ao salvar cliente');
        }
        return redirect()->route('platform.customers');
    }

    public function removeProfile( Customer $customer )
    {
        try {
            $raffle = Raffle::where('customer', $customer->id)->get();
            if( is_null($raffle)) {
                $customer->delete();
                Alert::info('Perfil do cliente excluído com sucesso.');
            } else {
                Alert::error('Não é possível remover estes cliente, pois o mesmo já está relacionado como ganhador de um sorteio');                    
            }
        } catch (Exception $e) {
            Alert::error('Erro ao remover cliente: ' . $e->getMessage());
        }
        return redirect()->route('platform.customers');
    }
}
