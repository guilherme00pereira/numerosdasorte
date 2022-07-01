<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * @param Dashboard $dashboard
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);
        $dashboard->registerResource('stylesheets', '/css/app.css');
    }

    /**
     * @return Menu[]
     */
    public function registerMainMenu(): array
    {
        return [
            Menu::make("Dashboard")
                ->icon("grid")
                ->route("platform.main"),
            Menu::make("Clientes")
                ->icon("people")
                ->route("platform.customers"),
            Menu::make("Sorteios e Prêmios")
                ->icon("calendar")
                ->route("platform.raffles"),
            Menu::make("Ganhadores")
                ->icon("badge")
                ->route("platform.winners"),
            Menu::make("Editar Página Ajuda")
                ->icon("question")
                ->route("platform.edit_help_text"),
            Menu::make("Importar Dados")
                ->icon("server")
                ->route("platform.upload_file"),
        ];
    }

    /**
     * @return Menu[]
     */
    public function registerProfileMenu(): array
    {
        return [
            Menu::make('Profile')
                ->route('platform.profile')
                ->icon('user'),
        ];
    }

    /**
     * @return ItemPermission[]
     */
    public function registerPermissions(): array
    {
        return [
            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),
        ];
    }
}
