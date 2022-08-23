<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\User;
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
        Role::insert([
            'name'          => 'Gestor',
            'slug'          => 'gestor',
            'permissions'   => '{"manager.painel": "1", "platform.index": "1", "customer.painel": "0", "platform.systems.roles": "0", "platform.systems.users": "0", "platform.systems.attachment": "1"}'
        ]);
        Role::insert([
            'name'          => 'Cliente',
            'slug'          => 'cliente',
            'permissions'   => '{"manager.painel": "0", "platform.index": "1", "customer.painel": "1", "platform.systems.roles": "0", "platform.systems.users": "0", "platform.systems.attachment": "1"}'
        ]);
        User::insert([
            'name'          => 'Admin',
            'email'         => 'admin@admin.com',
            'password'      => '$2y$10$tqWRfpm0amKakwM78fGK0eTmHUjHloBjmAy22uM1e7Zw1dR1SY1j2',
            'permissions'   => '{"manager.painel": "0", "platform.index": "0", "customer.painel": "0", "platform.systems.roles": "0", "platform.systems.users": "0", "platform.systems.attachment": "0"}'
        ]);

    }
}
