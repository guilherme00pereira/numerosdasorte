<?php

namespace App\Orchid\Screens\Admin;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Orchid\Layouts\CustomersTableLayout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Screen;
use Orchid\Support\Color;

class Customers extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'customers' => Customer::filters()->defaultSort('created_at')->paginate()
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Clientes';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::wrapper('filters.customers-bar', [
                'searchFields'        => Layout::rows([
                    Group::make([
                        Select::make('defaulter')->options([
                            'todos'         => 'Todos',
                            'adimplente'    => 'Adimplente',
                            'inadimplente'  => 'Inadimplente'
                        ])->title('Status do cliente'),
                        Input::make('name')->title('Busca por nome'),
                        Input::make('cpf')->title('Busca por CPF'),
                        Button::make('Buscar')->method('filterCustomers')->type(Color::PRIMARY())
                    ])
                ])
            ]),
            CustomersTableLayout::class
        ];
    }

    public function filterCustomers( Request $request )
    {
        $queryParams = "";
        if($request['defaulter']) {
            if('todos' !== $request['defaulter']) {
                $queryParams .= "[defaulter]=" . ($request['defaulter'] === 'adimplente' ? 0 : 1);
            }
        }
        if($request['name']) {
            $queryParams .= "[name]=" . $request['name'];
        }
        if($request['cpf']) {
            $queryParams .= "[cpf]=" . $request['cpf'];
        }
        if(empty($queryParams)) {
               return redirect()->to('/admin/clientes');
        } else {
            return redirect()->to('/admin/clientes?filter' . $queryParams);
        }
    }
}
