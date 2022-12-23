<?php

namespace App\Http\Controllers;

use App\Models\advertisement;
use Illuminate\Http\Request;

class adsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api','verified']);
    }

    public function insert(Request $request){

        $this->authorize('create', advertisement::class);

        $request->validate([
            'name'        => 'required|string',
            'description' => 'required|string',
            'category'    => 'required|exists:categories,id',
        ]);

        $ads = $request->toArray();
        $ads['user'] = auth()->user()->id;

        if($Ads = advertisement::create($ads)){

            $orderID = (new OrdersController)->createOrder($Ads);

            if($i = $request->hasFile('imgs')){
                $c = 0;
                foreach($request->imgs as $img)
                {
                    ImagesController::insert($Ads, $image[] = $img->store('storage'));
                    if($c == 4) break;
                    $c++;
                }
            }

            //NotificationsController::adminNotify($orderID);

            return response()->json([
                'msg'    => 'advertisement inserted successfully!',
                'ads'    => $Ads,
                'images' => $i? $image: null,
            ]);

        }else
        {
            return response()->json(['error' => 'advertisement doesn\'t created!!!']);
        }

    }

    public function delete(Request $request){

        $ads = advertisement::find($request->AdsID);

        $this->authorize('delete', $ads, advertisement::class);

        ImagesController::delete($ads->id);

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
            'description' => 'required|string',
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
