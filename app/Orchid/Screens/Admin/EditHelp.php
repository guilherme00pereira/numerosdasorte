<?php

namespace App\Orchid\Screens\Admin;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Code;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\SimpleMDE;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
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
        return [];
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
     * @return \Orchid\Screen\Action[]
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
            Layout::rows([
                SimpleMDE::make('simplemde')
                    ->title('SimpleMDE')
                    ->popover('SimpleMDE is a simple, embeddable, and beautiful JS markdown editor'),

                Button::make('Salvar')->method('saveHelpText')->type(Color::PRIMARY()),
            ])
        ];
    }

    public function saveHelpText(): void
    {
        
    }
}
