<?php

namespace App\Orchid\Screens\Customers;

use App\Models\Blog;
use App\Orchid\Layouts\Tables\WinnersTableLayoutCustomer;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;

class CustomerWinners extends Screen
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
        $this->year         = null;
        $this->month        = null;
        $winners    = Blog::whereNotNull('raffle')->orderBy('created_at', 'desc');
        if( is_null( $request->get('year' ) ) && is_null( $request->get('month' ) ) )
            $winners    = $winners->whereMonth('created_at', date('m'));
        else {
            if( !is_null( $request->get('year') ) ) {
                $this->year         = $request->get("year");
                $winners    = $winners->whereYear('created_at', $this->year);
            }
            if( !is_null( $request->get('month') ) ) {
                $this->month        = $request->get("month");
                $winners    = $winners->whereMonth('created_at', $this->month);
            }
        }
        return [
            'posts' => $winners->filters()->paginate()
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Ganhadores';
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
            WinnersTableLayoutCustomer::class
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
            return redirect()->to('/admin/ver-ganhadores');
        } else {
            return redirect()->to('/admin/ver-ganhadores?' . $queryParams);
        }
    }
}
