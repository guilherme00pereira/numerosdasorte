<?php

namespace App\Jobs;

use App\Models\Customer;
use App\Models\Order;
use App\Services\Importer;
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

            Log::channel("import")->info("Iniciando importação dos clientes". PHP_EOL);
            $importer = new Importer($this->file, $this->categories);
            $importer->importOrders();
            Log::info("Importação dos pedidos processada.");
        } catch (\Exception $e) {
            Log::channel("import")->info( "Erro ao importar pedidos: " . $e->getMessage() . PHP_EOL);
            Log::error($e->getMessage());
        }
    }
}
