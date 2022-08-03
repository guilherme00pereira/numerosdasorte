<?php

namespace App\Orchid\Screens\Admin;

use App\Models\Blog;
use App\Orchid\Layouts\Tables\WinnersTableLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Color;
use Orchid\Screen\Screen;

class Winners extends Screen
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
        $this->year         = date('Y');
        $this->month        = date('m');
        $winners    = Blog::whereNotNull('raffle')->orderBy('created_at', 'desc');
        if( is_null( $request->get('year' ) ) && is_null( $request->get('month' ) ) )
            $winners    = $winners->whereMonth('created_at', $this->month);
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
        return [
            Link::make("Adicionar Postagem")->route("platform.winners.post")->type(Color::SUCCESS())
        ];
    }

    /**
     * Views.
     *
     * @return Layout[]|string[]
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
            WinnersTableLayout::class
        ];
    }

    public function filterRaffles( Request $request )
    {
        $queryParams = "";
        if($request['month']) {
            $queryParams .= "month=" . $request['month'];
        }
        if($request['year']) {
            $queryParams .= "&year=" . $request['year'];
        }
        if(empty($queryParams)) {
            return redirect()->to('/admin/ganhadores');
        } else {
            return redirect()->to('/admin/ganhadores?' . $queryParams);
        }
    }
}
