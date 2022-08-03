<?php

namespace App\Orchid\Layouts\Tables;

use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class CategoriesTableLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'categories';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('name', 'Categoria')->sort(),
            TD::make('repeatable', 'Repetível')->render(function ($category){
                return $category->repeatable ? "Sim" : "Não";
            }),
            TD::make('id', '')->render(function ($category){
                return Link::make('')
                    ->route('platform.raffle.category', ['id' => $category->id])
                    ->icon('note')
                    ->class('icon-svg');
            })
        ];
    }
}
