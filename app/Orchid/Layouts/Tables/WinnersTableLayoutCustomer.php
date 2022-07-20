<?php

namespace App\Orchid\Layouts\Tables;

use App\Models\Raffle;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class WinnersTableLayoutCustomer extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'posts';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('title', 'Postagens')->sort(),
            TD::make('raffle', 'Data de Sorteio')->sort()->render(function ($post){
                $data = Raffle::find($post->raffle);
                return e(date_format($data->raffle_date, 'd/m/Y H:i'));
            })
        ];
    }
}
