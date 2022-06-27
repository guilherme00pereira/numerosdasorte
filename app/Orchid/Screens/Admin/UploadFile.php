<?php

namespace App\Orchid\Screens\Admin;

use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;

class UploadFile extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Importar Dados';
    }

    /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return 'Atenção: realize cada processo de upload por vez!';
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * Views.
     *
     * @return iterable
     */
    public function layout(): iterable
    {
        return [
            Layout::rows([
                Group::make([
                    Input::make('raw_file')
                        ->type('file')
                        ->title('Importar pedidos')
                        ->vertical(),
                    Button::make('Primary')->method('handleOrdersFileUpload')->type(Color::PRIMARY()),
                ])
            ]),
            Layout::rows([
                Group::make([
                    Input::make('raw_file')
                        ->type('file')
                        ->title('Importar status dos cliente')
                        ->vertical(),
                    Button::make('Primary')
                        ->method('handleCustomerStatusFileUpload')
                        ->type(Color::PRIMARY())
                        ->horizontal(),
                ])
            ])
        ];
    }

    public function handleOrdersFileUpload(): void
    {

    }

    public function handleCustomerStatusFileUpload(): void
    {

    }
}
