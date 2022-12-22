<?php

namespace App\Http\Controllers;

use App\Events\AdsInserted;
use App\Events\OrderStatusChanged;
use App\Models\advertisement;
use App\Models\User;
use App\Notifications\AdsGotReview;
use App\Notifications\newAds;
use Illuminate\Support\Facades\Notification;

class NotificationsController extends Controller
{

    public static function adminNotify($orderID)
    {
        $admin = User::where('type', 'admin')->get();
        Notification::send($admin, new newAds($orderID));
        
        event(new AdsInserted($orderID));

    }

    public static function userNotify($order)
    {
        $user = advertisement::find($order['Ads'])->users;
        Notification::send($user, new AdsGotReview($order));

        event(new OrderStatusChanged($order));
        
    }
}
