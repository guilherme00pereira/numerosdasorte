<?php

declare(strict_types=1);

namespace App\Orchid;

use Illuminate\Support\Facades\Auth;
use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;

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
                ->route("platform.main")
                ->permission('manager.painel'),
            Menu::make("Clientes")
                ->icon("people")
                ->route("platform.customers")
                ->permission('manager.painel'),
            Menu::make("Sorteios e Prêmios")
                ->icon("calendar")
                ->route("platform.raffles")
                ->permission('manager.painel'),
            Menu::make("Ganhadores")
                ->icon("badge")
                ->route("platform.winners")
                ->permission('manager.painel'),
            Menu::make("Editar Página Ajuda")
                ->icon("question")
                ->route("platform.edit_help_text")
                ->permission('manager.painel'),
            Menu::make("Importar Dados")
                ->icon("server")
                ->route("platform.upload_file")
                ->permission('manager.painel'),
            Menu::make("Profile")
                ->icon("user")
                ->route("platform.profile")
                ->permission('manager.painel'),

            // CUSTOMERS
            Menu::make("Dashboard")
                ->icon("grid")
                ->route("platform.customers.dashboard")
                ->permission('customer.painel'),
            Menu::make("Ajuda")
                ->icon("question")
                ->route("platform.customers.help")
                ->permission('customer.painel'),
            Menu::make("Profile")
                ->icon("user")
                ->route("platform.customers.edit-profile")
                ->permission('customer.painel'),
        ];
    }

    /**
     * @return Menu[]
     */
    public function registerProfileMenu(): array
    {
        return [

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
                ->addPermission('platform.systems.users', __('Users'))
        ];
    }
}
