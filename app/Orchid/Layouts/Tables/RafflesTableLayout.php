<?php

namespace App\Orchid\Layouts\Tables;

use App\Models\Customer;
use App\Models\Number;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class RafflesTableLayout extends Table
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
            TD::make('lottery_number', 'Número Sorteado')->sort(),
            TD::make('number', 'Número Ganhador')->render(function ($raffle){
                $number = Number::find($raffle->number);
                return is_null($number) ? "" : $number->refresh()->number;
            })->sort(),
            TD::make('cpf', 'CPF')->render(function($raffle){
                $customer = Customer::find($raffle->customer_id);
                return is_null($customer) ? "" : $customer->cpf;
            }),
            TD::make('prize', 'Prêmio'),
            TD::make('raffle_date', 'Data do Sorteio')->render(function ($raffle){
                return e(date_format($raffle->raffle_date, 'd/m/Y H:i'));
            })->sort(),
            TD::make('customer', 'Ganhador do Prêmio' )->render(function ($raffle){
                $winner = Customer::find($raffle->customer);
                return is_null($winner) ? "" : e($winner->refresh()->name);
            })->sort(),
            TD::make('id', '')->render(function ($raffle){
                return Link::make('')
                    ->route('platform.raffle.edit', ['id' => $raffle->id])
                    ->icon('note')
                    ->class('icon-svg');
            })
        ];
    }
}
