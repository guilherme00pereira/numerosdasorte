<?php

namespace App\Orchid\Screens\Admin;

use App\Models\Raffle;
use App\Models\Blog;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Screen;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Color;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class BlogEdit extends Screen
{
    public Blog $post;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Blog $post): iterable
    {
        return [
            'post'  => $post
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->post->exists ? 'Editar Postagem' : 'Criar Postagem';
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make("Cancelar")->route("platform.winners")->type(Color::LIGHT()),
            Button::make('Salvar')->method('savePost')->type(Color::PRIMARY()),
            Button::make('Excluir')->method('removePost')->type(Color::DANGER()),
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
                Select::make('post.raffle')
                        ->title('Sorteio Relacionado')
                        ->fromModel(Raffle::class, 'raffle_date')
                        ->vertical(),
                Input::make('post.title')
                        ->title('Título')
                        ->vertical(),
                Quill::make('post.content')
                    ->title('Conteúdo'),
            ])
        ];
    }

    public function savePost( Blog $post, Request $request )
    {
        $post->fill($request->get("post"))->save();
        Alert::info('Postagem criada com sucesso.');
        return redirect()->route('platform.winners');
    }

    public function removePost( Blog $post )
    {
        $post->delete();
        Alert::info('Categoria excluída com sucesso.');
        return redirect()->route('platform.winners');
    }
}
