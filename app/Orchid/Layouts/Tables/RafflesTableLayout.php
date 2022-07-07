<?php

namespace App\Orchid\Layouts\Tables;

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
            TD::make('chosen_number', 'Número Sorteado')->render(function ($raffle){
                return $raffle->chosen_number ?? "000000";
            })->sort(),
            TD::make('prize', 'Prêmio'),
            TD::make('raffle_date', 'Data do Sorteio')->sort(),
            TD::make('winner', 'Ganhador do Prêmio' ),
            TD::make('id', '')->render(function ($raffle){
                return Link::make('')
                    ->route('platform.raffle.edit', ['id' => $raffle->id])
                    ->icon('note');
            })
        ];
    }
}
