<?php

namespace App\Orchid\Layouts\Tables;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class LatestCustomersTableLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'latestCustomers';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('name', 'Nome Completo'),
            TD::make('phone', 'Telefone'),
            TD::make('cpf', 'CPF'),
            TD::make('city', 'Cidade'),
            TD::make('created_at', 'Data de Cadastro')->render(function ($customer){
                return e(date_format($customer->created_at, 'd/m/Y'));
            }),
        ];
    }
}
