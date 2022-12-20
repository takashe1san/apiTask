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

    public function insert(Request $request){

        $this->authorize('create', advertisement::class);

        $request->validate([
            'name'        => 'required|string',
            'description' => 'nullable|string',
            'category'    => 'required|exists:categories,id',
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
            return response()->json(['error' => 'advertisement doesn\'t created!!!']);
        }

    }

    public function delete(Request $request){

        $ads = advertisement::find($request->AdsID);

        $this->authorize('delete', $ads, advertisement::class);

        if($ads->delete()){
            return response()->json(['msg' => 'successfully deleted!']);
        }else{
            return response()->json(['error' => 'Something went wronge!!']);
        }
    }

    public function update(Request $request){

        $ads = advertisement::find($request->AdsID);

        $this->authorize('update', $ads, advertisement::class);

        $request->validate([
            'name'        => 'required|string',
            'description' => 'nullable|string',
            'category'    => 'required|exists:categories,id',
        ]);

        $ads->name        = $request->name;
        $ads->description = $request->description;
        $ads->category    = $request->category;

        if($ads->save()){
            return response()->json(['msg' => 'successfully updated!', 'ads' => $ads]);
        }else{
            return response()->json(['error' => 'Something went wronge!!']);
        }
    }
}
