<?php

namespace App\Orchid\Screens\Customers;

use App\Models\Numbers;
use App\Orchid\Layouts\Tables\MyLuckyNumbersTableLayout;
use Orchid\Screen\Action;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class MyNumbers extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'myNumbers'         => Numbers::all()->take(5),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Meus Números';
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * Views.
     *
     * @return iterable
     */
    public function layout(): iterable
    {
        return [
            MyLuckyNumbersTableLayout::class
        ];
    }
}
