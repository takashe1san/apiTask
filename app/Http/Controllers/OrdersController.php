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

    public function createOrder(advertisement $Ads)
    {
        return Order::create(['advertisement' => $Ads->id]);
    }

    public function changeStatus(Request $request){

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
            return response()->json(['msg' => 'successfully updated!', 'order' => $order]);
        }else{
            return response()->json(['error' => 'Something went wronge!!']);
        }
    }
}
