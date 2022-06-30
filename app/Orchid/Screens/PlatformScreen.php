<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Models\Customer;
use App\Orchid\Layouts\CustomersTableLayout;
use App\View\Components\DashboardCustomers;
use App\View\Components\DashboardDefaulters;
use App\View\Components\DashboardLotteryNumbers;
use App\View\Components\DashboardPrize;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class PlatformScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'customers' => Customer::all()->take(5)
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Dashboard';
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
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [
            //Layout::view('platform::partials.welcome'),
            Layout::columns([
                Layout::component(DashboardLotteryNumbers::class),
                Layout::component(DashboardPrize::class),
            ]),
            Layout::columns([
                Layout::component(DashboardCustomers::class),
                Layout::component(DashboardDefaulters::class),
            ]),
            CustomersTableLayout::class
        ];
    }
}
