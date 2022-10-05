<?php

namespace App\Jobs;

use App\Models\Customer;
use App\Services\Importer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Orchid\Platform\Models\Role;
use Orchid\Platform\Models\User;


class ProcessImportCustomers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $file;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        try {
            Log::channel("import")->info("Iniciando importação dos clientes");
            $importer = new Importer($this->file);
            $importer->importCustomers();
            Log::info("Importação dos clientes processada.");
        } catch (\Exception $e) {
            Log::channel("import")->info( "Erro ao importar clientes: " . $e->getMessage());
            Log::error($e->getMessage());
        }
    }
}
