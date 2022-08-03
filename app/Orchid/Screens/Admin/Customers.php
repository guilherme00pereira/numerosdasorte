<?php

namespace App\Orchid\Screens\Admin;

use App\Orchid\Filters\CustomerFilter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Orchid\Layouts\Tables\CustomersTableLayout;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Screen;
use Orchid\Support\Color;

class Customers extends Screen
{
    private mixed $defaulter;

    /**
     * Query data.
     *
     * @return array
     */
    public function query( Request $request ): iterable
    {
        $customers              = Customer::orderBy('created_at', 'desc');
        if( !is_null( $request->get('dft') ) ) {
            $this->defaulter    = $request->get("dft");
            $customers          = $customers->where('defaulter', $this->defaulter);
        }
        return [
            'customers' => $customers->filters()->paginate()
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
     * @return Action[]
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
            Layout::wrapper('filters-bar', [
                'searchFields'        => Layout::rows([
                    Group::make([
                        Select::make('dft')->options([
                            'todos'         => 'Todos',
                            'adimplente'    => 'Adimplente',
                            'inadimplente'  => 'Inadimplente'
                        ])->title('Status do cliente'),
                        Input::make('name')->title('Busca por nome'),
                        Input::make('cpf')->title('Busca por CPF')->mask('999.999.999-99'),
                        Button::make('Buscar')->method('filterCustomers')->type(Color::PRIMARY())
                    ])
                ])
            ]),
            CustomersTableLayout::class
        ];
    }

    public function filterCustomers( Request $request ): RedirectResponse
    {
        $queryParams = "";
        if($request['dft']) {
            if('todos' !== $request['dft']) {
                $queryParams .= "dft=" . ($request['dft'] === 'adimplente' ? 0 : 1);
            }
        }
        if($request['name']) {
            $queryParams .= "&filter[name]=" . $request['name'];
        }
        if($request['cpf']) {
            $queryParams .= "&filter[cpf]=" . $request['cpf'];
        }
        if(empty($queryParams)) {
               return redirect()->to('/admin/clientes');
        } else {
            return redirect()->to('/admin/clientes?' . $queryParams);
        }
    }
}
