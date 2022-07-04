<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class CustomersTableLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'customers';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('name', 'Nome Completo')->sort(),
            TD::make('phone', 'Telefone'),
            TD::make('cpf', 'CPF'),
            TD::make('city', 'Cidade'),
            TD::make('created_at', 'Data de Cadastro')->sort()->render(function ($customer){
                return e(date_format($customer->created_at, 'd/m/Y'));
            }),
            TD::make('defaulter', '')->render(function ($customer){
                return $customer->defaulter ?
                    "<span class='status-defaulter defaulter-yes'></span>" :
                    "<span class='status-defaulter defaulter-no'></span>";
            }),
            TD::make('id', '')->render(function ($customer){
               return Link::make('')
                   ->route('platform.customers.edit', ['id' => $customer->id])
                   ->icon('note');
            })
        ];
    }
}
