<?php

namespace App\Http\Controllers;

use App\Models\advertisement;
use App\Models\Image;
use Illuminate\Http\Request;

class ImagesController extends Controller
{
    public static function insert(advertisement $Ads, $path)
    {
        Image::create([
            'path'          => $path,
            'advertisement' => $Ads->id,
        ]);
    }

    public static function delete($Ads)
    {
        $images = Image::where('advertisement', $Ads)->get();

        foreach($images as $image)
            unlink($image->path);
        
    }
}
