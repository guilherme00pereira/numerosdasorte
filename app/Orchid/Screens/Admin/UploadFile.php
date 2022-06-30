<?php

namespace App\Orchid\Screens\Admin;

use App\Services\ImportCustomers;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Alert;

class UploadFile extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
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
        return [];
    }

    /**
     * Views.
     *
     * @return iterable
     */
    public function layout(): iterable
    {
        return [
                Layout::rows([
                    Group::make([
                        Input::make('import_orders')
                            ->type('file')
                            ->title('Importar Pedidos')
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
                        Button::make('Importar')->method('handleCustomerFileUpload')->type(Color::PRIMARY()),
                    ])
                ])
        ];
    }

    public function handleOrdersFileUpload(Request $request): void
    {
        $uploaded = $request->file("import_orders");
        if ($this->verifyUploadedFile($uploaded)) {
            $savedFile = $request->file("import_orders")->store('imported');
        } else {
            return;
        }
    }

    public function handleCustomerFileUpload(Request $request): void
    {
        $uploaded = $request->file("import_customers");
        if ($this->verifyUploadedFile($uploaded)) {
            try {
                $savedFile = $request->file("import_customers")->store('imported');
                ImportCustomers::process($savedFile);
                Alert::success("Arquivo processado");
            } catch (\Exception $e) {
                Alert::error("Erro ao processar o arquivo: " . $e->getMessage());
            }
        } else {
            return;
        }
    }

    private function verifyUploadedFile($uploaded): bool
    {
        if (is_null($uploaded)) {
            Alert::error("Nenhum arquivo selecionado");
            return false;
        }
        if ($uploaded->extension() != 'json') {
            Alert::error("Tipo de arquivo inválido! O arquivo precisa estar no formato .json");
            return false;
        }
        return true;
    }
}
