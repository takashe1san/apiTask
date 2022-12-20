<?php

namespace App\Http\Controllers;

use App\Models\advertisement;
use App\Models\Image;
use Illuminate\Http\Request;

class ImagesController extends Controller
{
    public function insert(advertisement $Ads, $path){
        Image::create([
            'path'          => $path,
            'advertisement' => $Ads->id,
        ]);
    }
}
