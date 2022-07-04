<?php

namespace App\Orchid\Screens\Admin;

use App\Models\Blog;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\SimpleMDE;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class EditHelp extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'raffle_rules' => Blog::where('tag', 'raffle_rule')->first()
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Ajuda e Regulamento dos Sorteios';
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
            Layout::rows([
                SimpleMDE::make('raffle_rules.content')
                    ->title('Texto'),
                Button::make('Salvar')->method('saveHelpText')->type(Color::PRIMARY()),
            ])
        ];
    }

    public function saveHelpText( Request $request ): void
    {
        try {
            $rule = $request->get('raffle_rules');
            Blog::updateOrCreate(
                ['tag' => 'raffle_rule'],
                ['content' => $rule['content'], 'title' => '']
            );
            Alert::success("Texto salvo com sucesso");
        } catch (\Exception $e) {
            Alert::error("Erro ao salvar texto." . $e->getMessage());
        }

    }
}
