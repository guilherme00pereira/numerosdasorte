<?php

namespace App\Orchid\Layouts\Tables;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class MyLuckyNumbersTableLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'myNumbers';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('number', 'Número'),
            TD::make('order', 'Pedido'),
            TD::make('created_at', 'Data de Emissão')->render(function ($customer){
                return e(date_format($customer->created_at, 'd/m/Y'));
            }),
        ];
    }
}
