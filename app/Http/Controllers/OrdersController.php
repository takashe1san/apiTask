<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditOrderRequest;
use App\Models\advertisement;
use App\Models\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api','verified']);
    }

    public function getAll(Request $request)
    {
        $this->authorize('view', Order::class);

        $orders = Order::forPage($request->page, 3)->get();
        return apiResponse(1, $orders);
    }

    public function get($id)
    {
        $this->authorize('view', Order::class);
        
        if($order = Order::find($id))
            return apiResponse(1, $order);
        else
            return apiResponse(0, 'Can\'t find this Order!');
    }

    public static function add(advertisement $Ads)
    {
        $order = new Order(['advertisement' => $Ads->id]);
        if($order->save())
            return $order->id;
    }

    public function edit(EditOrderRequest $request)
    {
        $order = Order::find($request->order);
        
        $this->authorize('modify', Order::class);
        
        $order->status = $request->status;
        $order->reject_reason = $request->status == 'rejected'? $request->reject_reason: null;

        if($order->save()){

            $ord = [
                'Ads'    => $order->advertisement,
                'Status' => $order->status,
            ];
            if($order->status == 'rejected') $ord['reject_reason'] = $order->reject_reason;

            NotificationsController::userNotify($ord);
            
            return apiResponse(1, $order);
        }else{
            return apiResponse(0, 'Order editing failed!!');
        }
    }
}
