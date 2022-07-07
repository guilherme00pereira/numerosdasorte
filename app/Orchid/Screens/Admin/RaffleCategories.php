<?php

namespace App\Orchid\Screens\Admin;

use App\Models\RaffleCategory;
use App\Orchid\Layouts\Tables\CategoriesTableLayout;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Color;

class RaffleCategories extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return array(
            'categories' => RaffleCategory::all()
        );
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Categorias de Sorteio';
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make("Adicionar Categoria")->route("platform.raffle.category")->type(Color::SUCCESS())
        ];
    }

    /**
     * Views.
     *
     * @return iterable
     */
    public function layout(): iterable
    {
        return [
            CategoriesTableLayout::class
        ];
    }
}
