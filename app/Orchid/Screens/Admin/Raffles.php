<?php

namespace App\Orchid\Screens\Admin;

use App\Models\Raffle;
use App\Orchid\Layouts\Tables\RafflesTableLayout;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;

class Raffles extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'raffles'   => Raffle::filters()->defaultSort('created_at')->paginate()
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Sorteios Realizados';
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make("Adicionar Novo")->route("platform.raffle.edit")->type(Color::SUCCESS())
        ];
    }

    /**
     * Views.
     *
     * @return Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::wrapper('filters.raffles-bar', [
                'searchFields'        => Layout::rows([
                    Group::make([
                        DateTimer::make('year')->title('')->format('Y'),
                        Button::make('Buscar')->method('filterRaffles')->type(Color::PRIMARY())
                    ])
                ])
            ]),
            RafflesTableLayout::class
        ];
    }

    public function filterRaffles()
    {

    }
}
