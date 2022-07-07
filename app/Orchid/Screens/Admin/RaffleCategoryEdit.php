<?php

namespace App\Orchid\Screens\Admin;

use App\Models\RaffleCategory;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class RaffleCategoryEdit extends Screen
{
    public RaffleCategory $raffleCategory;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(RaffleCategory $raffleCategory): iterable
    {
        return [
            'raffleCategory'    => $raffleCategory
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->raffleCategory->exists ? 'Editar Categoria "' . $this->raffleCategory->name . '"' : 'Adicionar Categoria';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Salvar')->method('saveCategory')->type(Color::PRIMARY()),
            Button::make('Excluir')->method('removeCategory')->type(Color::DANGER()),
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
            Layout::rows([
                Group::make([
                    Input::make('raffleCategory.name')
                        ->title('Nome da Categoria')
                        ->vertical(),
                    CheckBox::make('raffleCategory.repeatable')
                        ->value(1)
                        ->sendTrueOrFalse()
                        ->title('Repetível ?')
                        ->vertical(),
                ]),
            ])
        ];
    }

    public function saveCategory( RaffleCategory $raffleCategory, Request $request )
    {
        $raffleCategory->fill($request->get("raffleCategory"))->save();
        Alert::info('Categoria criada com sucesso.');
        return redirect()->route('platform.raffle.categories');
    }

    public function removeCategory( RaffleCategory $raffleCategory )
    {
        $raffleCategory->delete();
        Alert::info('Categoria excluída com sucesso.');
        return redirect()->route('platform.raffle.categories');
    }
}
