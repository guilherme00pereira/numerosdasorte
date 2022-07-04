<?php

use App\Http\Controllers\CustomerController;
use App\Orchid\Screens\Customers\CustomerDashboard;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth-customer');
});


// CUSTOMERS ROUTES
Route::get('customers-dashboard', [CustomerController::class, 'index']);

