<?php

namespace App\Orchid\Screens\Customers;

use App\Models\Raffle;
use App\Orchid\Layouts\Tables\RafflesTableLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;

class CustomerRaffles extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query( Request $request ): iterable
    {
        $signal     = '<';
        $this->next = false;
        if($request->get('next')){
            if(boolval($request->get('next'))){
                $signal = '>='; 
                $this->next = true;
            }
        }

        return [
            'raffles'   => Raffle::where('raffle_date', $signal, now())->filters()->defaultSort('created_at')->paginate(),
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
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        $text       = $this->next ? "Sorteios Realizados" : "Próximos Sorteios";
        $params     = $this->next ? [] : ['next' => 'true'];
        $color      = $this->next ? Color::DARK() : Color::WARNING();
        return [
            Link::make($text)->route("platform.raffles", $params)->type($color),
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::wrapper('filters.raffles-bar', [
                'searchFields'        => Layout::rows([
                    Group::make([
                        DateTimer::make('year')->title('')->format('Y'),
                        Button::make('Buscar')->method('filterRaffles')->type(Color::PRIMARY())
                    ])
                ])
            ]),
            RafflesTableLayout::class
        ];
    }
}
