<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        return view("auth-customer");
    }

    public function access(Request $request)
    {
        
    }
}
