<?php

namespace App\Orchid\Layouts\Tables;

use App\Services\Helper;
use App\Models\Order;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class MyLuckyNumbersTableLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'numbers';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('number', 'Número'),
            TD::make('order', 'Pedido')->render(function($number){
                $order = Order::find($number->order_id);
                return $order->order_id;
            }),
            TD::make('created_at', 'Data de Emissão')->render(function ($number){
                return Helper::brDate($number->created_at);
            }),
            TD::make('expiration', 'Vencimento')->render(function ($number){
                return Helper::brDate($number->expiration);
            }),
            TD::make('raffle', '')->render(function ($number){
                $winner = $number->presenter()->isWinner();
                if( !is_null( $winner ) ) {
                    return ModalToggle::make('')
                        ->modal('winner')
                        ->icon('badge')
                        ->asyncParameters($winner->prize)
                        ->class('btn-modal-winner');
                }
                $discarded = $number->presenter()->gotDiscarded();
                if( !is_null( $discarded ) ) {
                    return ModalToggle::make('')
                        ->modal('loser')
                        ->icon('bell')
                        ->asyncParameters($discarded)
                        ->class('btn-modal-discarded');
                }
                return "";
            }),
        ];
    }
}
