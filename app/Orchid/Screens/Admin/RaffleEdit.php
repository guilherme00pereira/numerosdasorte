<?php

namespace App\Orchid\Screens\Admin;

use App\Models\Customer;
use App\Models\Raffle;
use App\Models\RaffleCategory;
use App\Services\RaffleManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
                Input::make('raffle.id')->type('hidden'),
                Group::make([
                    Input::make('raffle.lottery_number')
                        ->title('Número da sorte')
                        ->vertical(),
                    DateTimer::make('raffle.raffle_date')
                        ->title('Data do Sorteio')
                        //->format('d/m/Y H:i')
                        ->format24hr()
                        ->enableTime()
                        ->vertical()
                ]),
                Group::make([
                    Select::make('raffle.category')
                        ->title('Categoria')
                        ->fromModel(RaffleCategory::class, 'name')
                        ->vertical(),
                    Select::make('raffle.customer')
                        ->empty('', 0)
                        ->title('Ganhador')
                        ->fromModel(Customer::class, 'name')
                        ->vertical()
                        ->set("disabled", "disabled")
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

    public function saveRaffle( Raffle $raffle, Request $request): RedirectResponse
    {
        try {
        $raffleWinner           = null;
        $raffleNumber           = null;
        $preRaffle              = $request['raffle'];
        
        if( $preRaffle["lottery_number"] ) 
        {
            if( is_null($raffle->customer) && is_null($raffle->number)) {
                $raffleManager      = new RaffleManager( $raffle['id'], $preRaffle["lottery_number"], $preRaffle['category']);
                if( $raffleManager->allDefaulters() ) {
                    Alert::warning('Não foi possível sortear um ganhador pois todos os números são de inadimplentes.');
                } else {
                    $raffleNumber       = $raffleManager->chooseNumber();
                    $raffleWinner       = $raffleManager->getWinnerId();
                }
            }
        }

        $raffle->raffle_date        = $preRaffle['raffle_date'];
        $raffle->prize              = $preRaffle['prize'];
        $raffle->category           = $preRaffle['category'];
        $raffle->lottery_number     = $preRaffle["lottery_number"];
        $raffle->number             = $raffleNumber;
        $raffle->customer           = $raffleWinner;
        $raffle->save();
        Alert::info('Sorteio editado com sucesso.');
        } catch (\Exception $e) {
            Alert::error('Erro ao salvar dados do sorteio');
            Log::error("Erro ao salvar dados do sorteio: " . $e->getMessage());
        }
        return redirect()->route('platform.raffles');
    }

    public function removeRaffle( Raffle $raffle, Request $request ): RedirectResponse
    {
        try {
            $raffle->delete();
            Alert::info('Sorteio excluído com sucesso.');
        }catch (\Exception $e) {
            Alert::error('Erro ao excluir sorteio');
            Log::error("Erro ao excluir sorteio: " . $e->getMessage());
        }
        return redirect()->route('platform.raffles');
    }
}
