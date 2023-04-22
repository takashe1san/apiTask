<?php

namespace App\Http\Controllers;

use App\Models\advertisement;
use App\Models\Image;
use Illuminate\Http\Request;

class ImagesController extends Controller
{
    public static function add($AdsID, $images)
    {
        $paths = [];
        foreach($images as $image)
        {
            $path = $image->store('storage');

            Image::create([
                'path'         => $path,
                'advertisement' => $AdsID,
            ]);

            $paths[] = $path;
        }
        return $paths;
    }

    public static function delete($Ads)
    {
        $images = Image::where('advertisement', $Ads)->get();

        foreach($images as $image)
            unlink($image->path);
        
    }

    public static function edit($adsID, $imageID, $newImage)
    {
        if(!$image = Image::find($imageID))
        {
            return ['success' => false, 'message' => 'Can\'t find the Image'];
        }

        if($image->advertisement != $adsID)
        {
            return ['success' => false, 'message' => 'Can\t edit this Image'];
        }

        $newPath = $newImage->store('storage');
        unlink($image->path);
        $image->path = $newPath;
        if($image->save())
        {
            return ['success' => true, 'newPath' => $newPath];
        }
    }
}
