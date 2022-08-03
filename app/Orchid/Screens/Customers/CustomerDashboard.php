<?php

namespace App\Orchid\Screens\Customers;

use App\Models\Customer;
use App\Models\Number;
use App\Models\Raffle;
use App\Orchid\Layouts\Tables\MyLuckyNumbersTableLayout;
use Illuminate\Support\Facades\Auth;
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
        $user               = Auth::user();
        $userCustomer       = Customer::where( 'user', $user->id)->first();
        $myNumbers          = Number::where('customer_id', $userCustomer->id)->get();
        if( is_null( $myNumbers) ) {
            $totalNumbers       = 0;
        } else {
            $totalNumbers       = $myNumbers->count();
        }
        $prizes             = Raffle::whereNotNull('number')->count();
        return [

            'numbers'           => $myNumbers->take(5),
            'tableTitle'        => 'Seus últimos números da sorte',
            'stats'             => [
                [
                    'image'     => 'lottery.png',
                    'pretitle'  => 'TOTAL DE NÚMEROS DA SORTE',
                    'title'     => 'SORTE ' . $totalNumbers,
                    'subtitle'  => 'Mantenha suas parcelas em dia, para que seus números sejam válidos para o sorteio'
                ],
                [
                    'image'     => 'badge.png',
                    'pretitle'  => 'VEJA O TOTAL DE',
                    'title'     => 'PRÊMIOS ' . $prizes,
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
            Layout::modal('loser', [
                Layout::view('customers.didnotwin')
            ])->title('Que Pena!')->withoutApplyButton()->withoutCloseButton(),
            Layout::modal('winner', [
                Layout::view('customers.congratulations')
            ])->title('Parabéns')->withoutApplyButton()->withoutCloseButton()->async('asyncGetData'),
            Layout::view('dashboard-stats'),
            Layout::view('layout/table-top'),
            MyLuckyNumbersTableLayout::class
        ];
    }

    public function asyncGetData(string $prize): array
    {
        return [
            'prize' => $prize
        ];
    }
}
