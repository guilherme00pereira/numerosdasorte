<?php

namespace App\Orchid\Screens\Customers;

use App\Models\Customer;
use App\Models\Number;
use App\Orchid\Layouts\Tables\MyLuckyNumbersTableLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;

class MyNumbers extends Screen
{
    /**
     * @var int|mixed
     */
    private mixed $year;
    /**
     * @var mixed|string
     */
    private mixed $month;
    /**
     * Query data.
     *
     * @return array
     */
    public function query( Request $request ): iterable
    {
        $user           = Auth::user();
        $userCustomer   = Customer::where( 'user', $user->id)->first();
        $myNumbers      = Number::with('raffle')->where('customer_id', $userCustomer->id)->orderBy('created_at')->get();
        $this->year         = null;
        $this->month        = null;
        if( !is_null( $request->get('year') ) ) {
            $this->year         = $request->get("year");
            $myNumbers    = $myNumbers->whereYear('created_at', $this->year);
        }
        if( !is_null( $request->get('month') ) ) {
            $this->month        = $request->get("month");
            $myNumbers    = $myNumbers->whereMonth('created_at', $this->month);
        }
        return [
            'numbers'         => $myNumbers,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Meus Números';
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
            Layout::modal('loser', [
                Layout::view('customers.didnotwin')
            ])->title('Que Pena!')->withoutApplyButton()->withoutCloseButton(),
            Layout::modal('winner', [
               Layout::view('customers.congratulations')
            ])->title('Parabéns')->withoutApplyButton()->withoutCloseButton(),
            Layout::wrapper('filters-bar', [
                'searchFields'        => Layout::rows([
                    Group::make([
                        Input::make('year')
                            ->type("number")
                            ->min(2020)
                            ->value($this->year)
                            ->title('Ano'),
                        Input::make('month')
                            ->type("number")
                            ->value($this->month)
                            ->min(1)
                            ->max(12)
                            ->title('Mês'),
                        Button::make('Buscar')->method('filterRaffles')->type(Color::PRIMARY())
                    ])
                ])
            ]),
            MyLuckyNumbersTableLayout::class
        ];
    }

    public function filterRaffles( Request $request )
    {
        $queryParams = "";
        if($request['month']) {
            $queryParams .= "month=" . $request['month'];
        }
        if($request['year']) {
            $queryParams .= "year=" . $request['year'];
        }
        if(empty($queryParams)) {
            return redirect()->to('/admin/meus-numeros');
        } else {
            return redirect()->to('/admin/meus-numeros?' . $queryParams);
        }
    }
}
