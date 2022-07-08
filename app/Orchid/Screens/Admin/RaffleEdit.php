<?php

namespace App\Orchid\Screens\Admin;

use App\Models\Raffle;
use App\Models\RaffleCategory;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Screen;
use Orchid\Support\Color;

class RaffleEdit extends Screen
{
    public Raffle $raffle;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Raffle $raffle): iterable
    {
        return [
            'raffle'    => $raffle
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->raffle->exists ? 'Editar Sorteio' : 'Criar Sorteio';
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make("Cancelar")->route("platform.raffles")->type(Color::LIGHT()),
            Button::make('Salvar')->method('saveRaffle')->type(Color::PRIMARY()),
            Button::make('Excluir')->method('removeRaffle')->type(Color::DANGER()),
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
            Layout::rows([
                Group::make([
                    Input::make('number')
                        ->title('Número da sorte')
                        ->vertical(),
                    DateTimer::make('raffle.raffle_date')
                        ->title('Data do Sorteio')
                        ->format('d/m/Y H:i')
                        ->format24hr()
                        ->enableTime()
                        ->vertical()
                ]),
                Group::make([
                    Select::make('raffle.category')
                        ->title('Categoria')
                        ->fromModel(RaffleCategory::class, 'name')
                        ->vertical(),
                    Input::make('raffle.winner')
                        ->title('Ganhador')
                        ->vertical()
                ]),
                Group::make([
                    TextArea::make('raffle.prize')
                        ->title('Prêmio')
                        ->rows(5)
                        ->vertical(),

                ]),
            ])
        ];
    }

    public function saveRaffle( Raffle $raffle, Request $request)
    {
        $preRaffle              = $request['raffle'];
        $data                   = \DateTime::createFromFormat('d/m/Y H:i', $preRaffle['raffle_date']);
        $raffle->raffle_date    = $data;
        $raffle->prize          = $preRaffle['prize'];
        $raffle->category       = $preRaffle['category'];
        $raffle->save();
        Alert::info('Sorteio editado com sucesso.');
        return redirect()->route('platform.raffles');
    }
}
