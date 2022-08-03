<?php

namespace App\Orchid\Layouts\Tables;

use App\Models\Customer;
use App\Models\Number;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class RafflesTableLayoutCustomer extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'raffles';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('number', 'Número Sorteado')->render(function ($raffle){
                $number = Number::find($raffle->number);
                if( !is_null($number)) {
                    return $number->refresh()->number;
                } else {
                    return "";
                }
            })->sort(),
            TD::make('prize', 'Prêmio'),
            TD::make('raffle_date', 'Data do Sorteio')->render(function ($raffle){
                return e(date_format($raffle->raffle_date, 'd/m/Y H:i'));
            })->sort(),
            TD::make('customer', 'Ganhador do Prêmio' )->render(function ($raffle){
                $winner = Customer::find($raffle->customer);
                if(!is_null($winner)) {
                    return e($winner->refresh()->name);
                } else {
                    return "";
                }
            })->sort(),
        ];
    }
}
