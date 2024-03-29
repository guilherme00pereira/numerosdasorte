<?php

namespace App\Orchid\Screens\Admin;

use App\Jobs\ProcessImportCustomers;
use App\Jobs\ProcessImportOrders;
use App\Models\RaffleCategory;
use App\Models\ZenviaJob;
use App\Services\ZenviaClient;
use App\Services\ZenviaHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Screen\Fields\Select;
use Orchid\Support\Facades\Layout;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Alert;

class UploadFile extends Screen
{
    private $processing;

    const IMPORT_QUEUE_MESSAGE = "A importação foi iniciada e está sendo processada em segundo plano";
    private mixed $categories;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Request $request): iterable
    {

        if ($request->get('processing')) {
            $this->processing = true;
        } else {
            $this->processing = false;
        }
        return [];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Importar Dados';
    }

    /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return 'Atenção: realize cada processo de upload por vez!';
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
            //Button::make('Salvar')->method('Proc')->type(Color::PRIMARY()),
        ];
    }

    /**
     * Views.
     *
     * @return iterable
     */
    public function layout(): iterable
    {
        $layout = [
            Layout::rows([
                Group::make([
                    Input::make('import_orders')
                        ->type('file')
                        ->title('Importar Pedidos')
                        ->vertical(),
                    Select::make('raffle_category')
                        ->multiple()
                        ->title('Vincular Categoria')
                        ->fromModel(RaffleCategory::class, 'name')
                        ->vertical(),
                    Button::make('Importar')->method('handleOrdersFileUpload')->type(Color::PRIMARY()),
                ])
            ]),
            Layout::rows([
                Group::make([
                    Input::make('import_customers')
                        ->type('file')
                        ->title('Importar Cliente')
                        ->vertical(),
                    Button::make('Importar')
                        ->method('handleCustomerFileUpload')
                        ->type(Color::PRIMARY()),
                ])
            ])
        ];
        if ($this->processing) {
            $layout[] = Layout::view('layout.status');
        }
        return $layout;
    }

    public function handleOrdersFileUpload(Request $request)
    {
        set_time_limit(0);
        $uploaded           = $request->file("import_orders");
        $this->categories  = $request->get("raffle_category");
        if ($this->verifyUploadedFile($uploaded) && $this->verifyRaffle()) {
            try {
                ProcessImportOrders::dispatch($uploaded->store('imported'), $this->categories);
                return redirect()->to('/admin/importar-dados?processing=true');
            } catch (\Exception $e) {
                Alert::error("Erro ao processar o arquivo: " . $e->getMessage());
            }
        }
    }

    public function handleCustomerFileUpload(Request $request)
    {
        set_time_limit(0);
        $uploaded = $request->file("import_customers");
        if ($this->verifyUploadedFile($uploaded)) {
            try {
                ProcessImportCustomers::dispatch($uploaded->store('imported'));
                return redirect()->to('/admin/importar-dados?processing=true');
            } catch (\Exception $e) {
                Alert::error("Erro ao processar o arquivo: " . $e->getMessage());
            }
        }
    }

    private function verifyUploadedFile($uploaded): bool
    {
        if (is_null($uploaded)) {
            Alert::error("Nenhum arquivo selecionado!");
            return false;
        }
        if ($uploaded->extension() != 'json') {
            Alert::error("Tipo de arquivo inválido! O arquivo precisa estar no formato .json");
            return false;
        }
        return true;
    }

    private function verifyRaffle(): bool
    {
        if (is_null($this->categories)) {
            Alert::error("Selecione ao menos uma categoria de sorteio para vincular aos pedidos!");
            return false;
        }
        return true;
    }

    /**
     * @return void
     */
    private function cleanImportedStorage(): void
    {
        $files = Storage::allFiles("imported");
        Storage::delete($files);
    }

//    public function Proc()
//    {
//        try {
//            $jobs = ZenviaJob::where('type', ZenviaClient::NEW_ACCOUNT_TYPE)->where('processed', false)->get();
//            $zenvia = new ZenviaClient();
//            foreach ($jobs as $job) {
//                $zenvia->sendSMS($job->type, ZenviaHelper::getInstance()->prepareSmsData($job->data));
//                $job->processed = true;
//                $job->save();
//            }
//        } catch (\Exception $e) {
//            Log::error("Erro ao enviar SMS [ Nova Conta ] - " . $e->getMessage() );
//        }
//    }
}
