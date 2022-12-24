<?php

namespace App\Http\Controllers;

use App\Models\advertisement;
use App\Models\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api','verified']);
    }

    public function index(Request $request)
    {
        $this->authorize('view', Order::class);

        $orders = Order::forPage($request->page, 3)->get();
        return response()->json($orders);
    }

    public function show($id)
    {
        $this->authorize('view', Order::class);
        
        $order = Order::find($id);
        return response()->json($order);
    }

    public function createOrder(advertisement $Ads)
    {
        $order = Order::create(['advertisement' => $Ads->id]);
        return $order->id;
    }

    public function changeStatus(Request $request)
    {
        $order = Order::find($request->order);
        
        $this->authorize('modify', Order::class);

        $rejectReason = $request->status == 'rejected'? 'required': 'nullable';
        $request->validate([
            'status' => 'required|in:pending,rejected,allowed',
            'reject_reason' => $rejectReason,
        ]);
        
        $order->status = $request->status;
        $order->reject_reason = $request->status == 'rejected'? $request->reject_reason: null;

        if($order->save()){

            $ord = [
                'Ads'    => $order->advertisement,
                'Status' => $order->status,
            ];
            if($order->status == 'rejected') $ord['reject_reason'] = $order->reject_reason;

            NotificationsController::userNotify($ord);
            
            return response()->json(['msg' => 'successfully updated!', 'order' => $order]);
        }else{
            return response()->json(['error' => 'Something went wronge!!']);
        }
    }
}
