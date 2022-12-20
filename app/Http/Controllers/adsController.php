<?php

namespace App\Http\Controllers;

use App\Models\advertisement;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class adsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function insertAds(Request $request){

        $this->authorize('create', advertisement::class);

        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'category' => 'required|exists:categories,id',
        ]);

        $ads = $request->toArray();
        $ads['user'] = auth()->user()->id;

        if($Ads = advertisement::create($ads)){
            if((new OrdersController)->createOrder($Ads)){
                return response()->json(['msg' => 'advertisement inserted successfully!', 'ads' => $ads]);
            }else{
                return response()->json(['error' => 'Something went wronge!!']);
            }
        }else{
            return response()->json(['error' => 'Something went wronge!!!']);
        }

    }
}
