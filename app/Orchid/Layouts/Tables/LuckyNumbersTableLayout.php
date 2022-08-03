<?php

namespace App\Orchid\Layouts\Tables;

use App\Services\Helper;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class LuckyNumbersTableLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'numbers';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('number', 'Número'),
            TD::make('order_id', 'Pedido'),
            TD::make('created_at', 'Data de Emissão')->render(function ($number){
                return e(Helper::brDate($number->created_at));
            }),
            TD::make('expiration', 'Vencimento')->render(function ($number){
                return Helper::brDate($number->expiration);
            }),
        ];
    }
}
