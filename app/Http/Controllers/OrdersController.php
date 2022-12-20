<?php

namespace App\Http\Controllers;

use App\Models\advertisement;
use App\Models\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function createOrder(advertisement $Ads){
        return Order::create(['advertisement' => $Ads->id]);
    }
}
