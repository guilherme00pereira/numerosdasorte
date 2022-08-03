<?php

namespace App\Jobs;

use App\Models\Customer;
use App\Models\Order;
use App\Services\NumbersAssigner;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessImportOrders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $file;
    private array $categories;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( $file, $categories )
    {
        $this->file         = $file;
        $this->categories   = $categories;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        try {
            $json = Storage::get($this->file);
            $importedOrders = json_decode($json);
            foreach ($importedOrders as $order) {
                $newOrder = $this->saveOrder( $order );
                $assigner = new NumbersAssigner( $newOrder );
                $assigner->setCategories( $this->categories )->process();
            }
            Log::info("Importação dos pedidos processada.");
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    private function saveOrder( $order )
    {
        $dbOrder            = Order::where("order_id", $order->id_pedido)->first();
        if( is_null( $dbOrder) ) {
            $customer = Customer::where('external_code', $order->codigo)->first();
            return Order::create([
                'order_id' => $order->id_pedido,
                'value' => $order->valor,
                'num_items' => $order->quant_itens_pedidos,
                'installments' => $order->parcelas,
                'payment_type' => $order->tipo_pagamento,
                'customer_id' => $customer->id
            ]);
        } else {
            return $dbOrder;
        }
    }
}
