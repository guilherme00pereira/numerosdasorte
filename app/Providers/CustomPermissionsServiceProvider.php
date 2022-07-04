<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\Dashboard;
use Orchid\Screen\Actions\Menu;

class CustomPermissionsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * @param Dashboard $dashboard
     *
     * @return void
     */
    public function boot(Dashboard $dashboard): void
    {
        $permission = ItemPermission::group('clientes')
            ->addPermission('customer.painel', 'Acesso ao painel de clientes')
            ->addPermission('manager.painel', 'Acesso ao painel do gestor');
        $dashboard->registerPermissions($permission);
    }
}
