<?php

namespace App\Orchid\Screens\Customers;

use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
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
        return [
            'user' => Auth::user()
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Editar Perfil';
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
                Group::make([
                    Input::make('user.name')
                        ->title('Nome Completo')
                        ->vertical(),
                    Input::make('user.cpf')
                        ->title('CPF')
                        ->vertical()
                ]),
                Group::make([
                    Input::make('user.birthdate')
                        ->title('Data de Nascimento')
                        ->vertical(),
                    Input::make('user.name')
                        ->title('Quantidade de Números da Sorte')
                        ->vertical()
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
                        ->vertical()
                ]),
                Button::make('Salvar')->method('saveProfile')->type(Color::PRIMARY()),
            ])
        ];
    }

    public function saveProfile()
    {

    }


}
