<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class OrderController extends Controller
{
    public function index(): Factory|View
    {
        return view('orders.index');
    }

    public function create(): Factory|View
    {
        return view('orders.create');
    }
}
