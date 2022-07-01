<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Models\Customer;
use App\Orchid\Layouts\LatestCustomersTableLayout;
use App\View\Components\DashboardCustomers;
use App\View\Components\DashboardDefaulters;
use App\View\Components\DashboardLotteryNumbers;
use App\View\Components\DashboardPrize;
use Orchid\Screen\Action;
use Orchid\Screen\Screen;
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
            'customers' => Customer::query()->latest()->take(5)
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
     * @return Action[]
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
            Layout::columns([
                Layout::component(DashboardLotteryNumbers::class),
                Layout::component(DashboardPrize::class),
            ]),
            Layout::columns([
                Layout::component(DashboardCustomers::class),
                Layout::component(DashboardDefaulters::class),
            ]),
            //LatestCustomersTableLayout::class
        ];
    }
}
