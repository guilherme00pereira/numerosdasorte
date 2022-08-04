<?php

namespace App\Services;

use App\Models\Number;
use App\Models\Order;
use App\Models\Customer;
use Carbon\Carbon;


class NumbersAssigner
{
    private int $totalNumbersToAssign;
    protected Order $orderData;
    private array $categories;
    private int $months;
    private array $onCreditMethods;

    public function __construct( $order )
    {
        $this->onCreditMethods      = ['BOLETO BANCARIO', 'CARTAO CREDITO'];
        $this->orderData            = $order;
        $this->totalNumbersToAssign = 1;
        $this->months               = 1;
    }

    public function setCategories( $categories )
    {
        $this->categories   = $categories;
        return $this;
    }

    public function process()
    {
        if( in_array( $this->orderData->payment_type, $this->onCreditMethods ) && intval($this->orderData->installments) > 1) {
            $this->months += intval($this->orderData->installments);
        } else {
            $this->months += intval($this->orderData->num_items);
        }
        $existingOrders = Order::where('customer_id', $this->orderData->customer_id)->where('created_at', '<', Carbon::now()->subHours(1));
        if( !is_null( $existingOrders ) ) {
            $this->totalNumbersToAssign += $existingOrders->count();
        }
        $this->applyNumbers();
    }

    private function applyNumbers()
    {
        foreach ($this->categories as $category) {
            $numbers = [];
            $existing = Number::select('number')->where('expiration', '>', Carbon::now())->where('category_id', $category)->get()->toArray();
            while (count($numbers) <= $this->totalNumbersToAssign) {
                $rand       = rand(1, 99999);
                if( !in_array( $rand, $existing ) ) {
                    $numbers[]  = $rand;
                    $existing[] = $rand;
                }
            };
            foreach ($numbers as $number) {
                Number::create([
                    'customer_id' => $this->orderData->customer_id,
                    'category_id' => $category,
                    'order_id' => $this->orderData->id,
                    'number' => $number,
                    'expiration' => Carbon::now()->addMonths($this->months)
                ]);
            }
           $customer = Customer::where('id', $this->orderData->customer_id)->first();
           if( !is_null( $customer ) ) {
                ZenviaHelper::getInstance()->jobToEnqueue( ZenviaClient::GENERATED_NUMBERS_TYPE, [ $customer->phone ], count( $numbers ) );
           }
        }
    }

}
