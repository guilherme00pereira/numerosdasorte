<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\DiscardedNumber;
use App\Models\Number;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class RaffleManager
{

    private int $targetNumber;
    private $category;
    private $chosenNumber;
    private array $discarded;
    private $dbNumbers;
    /**
     * @var array|int[]
     */
    private array $intNumbers;

    public function __construct( $raffle, $number, $category )
    {
        $this->raffle           = $raffle;
        $this->targetNumber     = intval($number);
        $this->category         = $category;
        $this->chosenNumber     = null;
        $this->discarded        = [];
    }

    public function chooseNumber(): string
    {
        $this->dbNumbers        = Number::where('expiration', '>', Carbon::now())->where('category_id', $this->category)->get();
        $this->intNumbers       = array_map(fn($value) => intval($value['number']), $this->dbNumbers->toArray() );
        $this->chosenNumber     = $this->checkWinnerIsNoDefaulter();
        
        $customer               = Customer::where('id', $this->chosenNumber->customer_id)->first();
        if( !is_null( $customer ) ) {
            ZenviaHelper::getInstance()->jobToEnqueue( ZenviaClient::DRAWN_NUMBER_TYPE, [ $customer->phone ] );
        }
        
        return $this->chosenNumber->id;
    }

    public function getWinnerId()
    {
        return $this->chosenNumber->customer_id;
    }

    private function checkWinnerIsNoDefaulter()
    {
        $chosenNumber       = Helper::getClosest( $this->intNumbers, $this->targetNumber);
        $numberEntity       = $this->dbNumbers->where('number', $chosenNumber)->first();
        $id                 = $numberEntity->customer_id;
        $customer           = Customer::where('id', $id)->first();
        if( $customer->defaulter ) {
            $this->saveDiscardedNumber( $this->raffle, $numberEntity->id );
            if( ( $key = array_search( $chosenNumber, $this->intNumbers ) ) ) {
                unset( $this->intNumbers[$key] );
            }
            ZenviaHelper::getInstance()->jobToEnqueue( ZenviaClient::DRAWN_DEFAULTER_TYPE, [ $customer->phone ] );
            return $this->checkWinnerIsNoDefaulter();
        } else {
            return $numberEntity;
        }
    }

    private function saveDiscardedNumber( $raffle, $number ): void
    {
        DiscardedNumber::create([
            'raffle_id' => $raffle,
            'number_id' => $number
        ]);
    }

}
