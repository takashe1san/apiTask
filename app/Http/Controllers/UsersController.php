<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function getAll(Request $request)
    {
        $users = User::select(['id', 'name', 'email', 'city', 'type'])->forPage($request->page, 5)->get();
        return apiResponse(1, $users);
    }

    public function get($id)
    {
        if($user = User::select(['id', 'name', 'email', 'city', 'type'])->find($id))
            return apiResponse(1, $user);
        else
            return apiResponse(0, 'Can\'t find this user!!');

    }

    public function personal()
    {
        $user = auth()->user();
        return apiResponse(1, $user);
    }

    public function edit(Request $request)
    {
        $user = User::find(auth()->user()->id);

        $user->name    = $request->has('name')   ? $request->name   : $user->name;
        $user->address = $request->has('address')? $request->address: $user->address;
        $user->city    = $request->has('city')   ? $request->city   : $user->city;

        if($user->save())
            return apiResponse(1, $user);
        else
            return apiResponse(0, 'Your info editing failed');
    }
}
