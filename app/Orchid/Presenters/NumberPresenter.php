<?php

namespace App\Orchid\Presenters;

use App\Models\DiscardedNumber;
use App\Models\Raffle;
use Orchid\Support\Presenter;

class NumberPresenter extends Presenter
{

    public function isWinner()
    {
        return Raffle::where('number', $this->entity->id)->first();
    }

    public function gotDiscarded(): ?string
    {
        $item = DiscardedNumber::where('number_id', $this->entity->id)->first();
        if( is_null( $item ) ) {
            return null;
        } else {
            return "123";
        }
    }

}
