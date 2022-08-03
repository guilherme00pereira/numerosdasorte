<?php

namespace App\Orchid\Screens\Customers;

use App\Models\Raffle;
use App\Orchid\Layouts\Tables\RafflesTableLayoutCustomer;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;

class CustomerRaffles extends Screen
{
    protected bool $next;
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
        $this->year         = null;
        $this->month        = null;
        $signal     = '<';
        $this->next = false;
        if($request->get('next')){
            if(boolval($request->get('next'))){
                $signal = '>=';
                $this->next = true;
            }
        }
        $selectedRaffles    = Raffle::query()->orderBy('created_at', 'desc');
        if( is_null( $request->get('year' ) ) && is_null( $request->get('month' ) ) )
            $selectedRaffles    = $selectedRaffles->where('raffle_date', $signal, now());
        else {
            if( !is_null( $request->get('year') ) ) {
                $this->year         = $request->get("year");
                $selectedRaffles    = $selectedRaffles->whereYear('raffle_date', $this->year);
            }
            if( !is_null( $request->get('month') ) ) {
                $this->month        = $request->get("month");
                $selectedRaffles    = $selectedRaffles->whereMonth('raffle_date', $this->month);
            }

        }
        return [
            'raffles'   => $selectedRaffles->filters()->paginate()
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return "Sorteios e Prêmios";
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        $text       = $this->next ? "Sorteios Realizados" : "Próximos Sorteios";
        $params     = $this->next ? [] : ['next' => 'true'];
        $color      = $this->next ? Color::DARK() : Color::WARNING();
        return [
            Link::make($text)->route("platform.customers.raffles", $params)->type($color),
        ];
    }

    /**
     * Views.
     *
     * @return iterable
     */
    public function layout(): iterable
    {
        return [
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
            RafflesTableLayoutCustomer::class
        ];
    }

    public function filterRaffles( Request $request )
    {
        $queryParams = "";
        if($request['month']) {
            $queryParams .= "month=" . $request['month'];
        }
        if($request['year']) {
            $queryParams .= "year=" . strval($request['year']);
        }
        if(empty($queryParams)) {
            return redirect()->to('/admin/sorteios');
        } else {
            return redirect()->to('/admin/sorteios?' . $queryParams);
        }
    }
}
