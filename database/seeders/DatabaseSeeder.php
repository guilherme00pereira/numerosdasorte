<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Orchid\Platform\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        Blog::insert([
            'title'     => '',
            'content'   => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis a congue libero. Aliquam facilisis tortor sed neque pharetra tristique. Nullam mattis quam in eros vehicula, ut dictum nulla placerat. Phasellus quis rhoncus sapien, sit amet maximus sem. Phasellus gravida non tellus cursus vehicula. Phasellus eleifend nec nulla in placerat. Fusce id nisi ac odio molestie aliquam. Praesent tincidunt urna vel ullamcorper consequat. Etiam egestas dictum pharetra. Nam euismod massa ante, vitae cursus elit lobortis suscipit. Aenean sit amet sem placerat, blandit metus placerat, faucibus lectus. Nulla at tristique felis, in ullamcorper sem. Fusce a tempus diam. Nulla est sem, porta et mi ac, ultricies iaculis mauris. Sed vehicula pellentesque velit vel interdum.',
            'tag'       => 'raffle_rule'
        ]);
    }
}
