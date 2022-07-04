<?php

namespace App\Orchid\Screens\Customers;

use App\Models\Blog;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class HelpText extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'raffle_rules' => Blog::where('tag', 'raffle_rule')->first()
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Ajuda e Regulamento dos Sorteios';
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
            Layout::view('customers.help')
        ];
    }
}
