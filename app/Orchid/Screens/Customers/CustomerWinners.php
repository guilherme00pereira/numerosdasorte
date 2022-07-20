<?php

namespace App\Orchid\Screens\Customers;

use App\Models\Blog;
use App\Orchid\Layouts\Tables\WinnersTableLayoutCustomer;
use Orchid\Screen\Screen;

class CustomerWinners extends Screen
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
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            WinnersTableLayoutCustomer::class
        ];
    }
}
