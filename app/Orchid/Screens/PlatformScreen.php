<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Models\Customer;
use App\Orchid\Layouts\Tables\LatestCustomersTableLayout;
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
            'latestCustomers'   => Customer::all()->take(5),
            'tableTitle'        => 'Últimos clientes cadastrados',
            'stats'             => [
                [
                    'image'     => 'lottery.png',
                    'pretitle'  => 'TOTAL DE NÚMEROS DA SORTE',
                    'title'     => 'SORTE ' . '1456',
                    'subtitle'  => 'Total geral de todos os números da sorte para os cliente cadastrados'
                ],
                [
                    'image'     => 'badge.png',
                    'pretitle'  => 'VEJA O TOTAL DE',
                    'title'     => 'PRÊMIOS ' . '456',
                    'subtitle'  => 'Total de prêmios já distribuídos para a base de clientes cadastrada'
                ],
                [
                    'image'     => 'customer-feedback.png',
                    'pretitle'  => 'TOTAL GERAL DE',
                    'title'     => 'CLIENTES ' . '4789',
                    'subtitle'  => 'Número total de clientes cadastrados na base do sistema'
                ],
                [
                    'image'     => 'defaulter.png',
                    'pretitle'  => 'TOTAL DE CLIENTE',
                    'title'     => 'INADIMPLENTE ' . '789',
                    'subtitle'  => 'Número total de clientes na base do sistema com o status inadimplente'
                ],
            ]
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
            Layout::view('dashboard-stats'),
            Layout::view('layout/table-top'),
            LatestCustomersTableLayout::class
        ];
    }

    public function permission(): ?iterable
    {
        return [
            'manager.painel'
        ];
    }
}
