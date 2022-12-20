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
        // $this->middleware('auth:api');
    }

    public function insertAds(Request $request){

        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'category' => 'required|exists:categories,id',
            'user'  => 'required|exists:users,id',
        ]);

        $ads = advertisement::create($request->toArray());
        return response()->json(['msg' => 'advertisement inserted successfully!', 'ads' => $ads]);
    }
}
