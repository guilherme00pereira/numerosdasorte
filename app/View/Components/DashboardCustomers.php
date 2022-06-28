<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DashboardCustomers extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return <<<'blade'
        <div class="flex flex-row p-4 bg-white rounded shadow-sm h-100">
        <div class="flex-1 basis-1/4">
            <img src="{{ asset('assets/img/clientes.jpg') }}" />
        </div>
        <div class="flex-1 basis-3/4">
            <small class="text-primary">TOTAL DE NÚMEROS DA</small>
        </div>
        
</div>
blade;
    }
}
