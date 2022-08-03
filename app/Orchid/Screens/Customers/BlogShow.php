<?php

namespace App\Orchid\Screens\Customers;

use App\Models\Blog;
use Orchid\Screen\Action;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class BlogShow extends Screen
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
        return $this->post->title;
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
            Layout::view('customers.blog')
        ];
    }
}
