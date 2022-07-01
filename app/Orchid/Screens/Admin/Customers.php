<?php

namespace App\Orchid\Screens\Admin;

use App\Models\Customer;
use App\Orchid\Layouts\CustomersTableLayout;
use Orchid\Screen\Screen;

class Customers extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'customers' => Customer::filters()->defaultSort('created_at')->paginate()
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Clientes';
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
            CustomersTableLayout::class
        ];
    }
}
