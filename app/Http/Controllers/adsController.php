<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddAdvertisementRequest;
use App\Http\Requests\EditAdvertisementRequest;
use App\Models\advertisement;
use Illuminate\Http\Request;

class adsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api','verified'], ['except' => ['getAll', 'get']]);
    }

    public function getAll(Request $request)
    {
        $Ads = advertisement::forPage($request->page, 5)->with('images')->get();
        return apiResponse(1, $Ads);
    }

    public function get($id)
    {
        if($ads = advertisement::where('id', $id)->with('images')->first())
            return apiResponse(1, $ads);
        else
            return apiResponse(0, 'Advertisement is not exists');
    }

    public function add(AddAdvertisementRequest $request)
    {
        $this->authorize('create', advertisement::class);

        $Ads = new advertisement($request->toArray());
        $Ads->user = auth()->user()->id;

        if($Ads->save()){

            $orderID = OrdersController::add($Ads);

            if($haveImage = $request->hasFile('images'))
            {
                $images = ImagesController::add($Ads->id, $request->images);        
            }

            NotificationsController::adminNotify($orderID);

            return apiResponse(1, [
                'ads'    => $Ads,
                'images' => $haveImage? $images: null,
            ]);

        }else
        {
            return apiResponse(0, 'advertisement doesn\'t created!!!');
        }

    }

    public function delete($id)
    {
        if(!$ads = advertisement::find($id))
            return apiResponse(0, 'advertisement is not exists!!');

        $this->authorize('delete', $ads, advertisement::class);

        ImagesController::delete($ads->id);

        if($ads->delete()){
            return apiResponse(1, 'advertisement deleted');
        }else{
            return apiResponse(0, 'advertisement doesn\'t deleted!!!'); 
        }
    }

    public function edit(EditAdvertisementRequest $request)
    {
        if(!$ads = advertisement::find($request->AdsID))
            return apiResponse(0, 'advertisement is not exists!!');

        $this->authorize('update', $ads, advertisement::class);

        $ads->name        = $request->name;
        $ads->description = $request->description;
        $ads->category    = $request->category;

        if($ads->save()){
            return apiResponse(1, $ads);
        }else{
            return apiResponse(0, 'Advertisement editing failed!!');
        }
    }

}
