<?php

namespace App\Orchid\Screens\Admin;

use App\Models\Blog;
use App\Orchid\Layouts\Tables\WinnersTableLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Color;
use Orchid\Screen\Screen;

class Winners extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'posts' => Blog::whereNotNull('raffle')->filters()->paginate()
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Ganhadores';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make("Adicionar Postagem")->route("platform.winners.post")->type(Color::SUCCESS())
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            WinnersTableLayout::class
        ];
    }
}
