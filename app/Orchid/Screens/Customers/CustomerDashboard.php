<?php

namespace App\Orchid\Screens\Customers;

use App\Models\Numbers;
use App\Orchid\Layouts\Tables\MyLuckyNumbersTableLayout;
use Orchid\Screen\Action;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class CustomerDashboard extends Screen
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
            'tableTitle'        => 'Seus últimos números da sorte',
            'stats'             => [
                [
                    'image'     => 'lottery.png',
                    'pretitle'  => 'TOTAL DE NÚMEROS DA SORTE',
                    'title'     => 'SORTE ' . '81',
                    'subtitle'  => 'Mantenha suas parcelas em dia, para que seus números sejam válidos para o sorteio'
                ],
                [
                    'image'     => 'badge.png',
                    'pretitle'  => 'VEJA O TOTAL DE',
                    'title'     => 'PRÊMIOS ' . '752',
                    'subtitle'  => 'A BR Vita sempre sorteando prêmios para todos os seus clientes'
                ]
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
        return 'Painel do Cliente';
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [

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
            Layout::view('dashboard-stats'),
            Layout::view('layout/table-top'),
            MyLuckyNumbersTableLayout::class
        ];
    }
}
