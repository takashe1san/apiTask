<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $users = User::select(['id', 'name', 'email', 'city', 'type'])->forPage($request->page, 5)->get();
        return response()->json($users);
    }

    public function show($id)
    {
        $user = User::select(['id', 'name', 'email', 'city', 'type'])->find($id);
        return response()->json($user);

    }

    public function personal()
    {
        $user = auth()->user();
        return response()->json($user);
    }

    public function update(Request $request)
    {
        $user = User::find(auth()->user()->id);

        $user->name    = $request->has('name')   ? $request->name   : $user->name;
        $user->address = $request->has('address')? $request->address: $user->address;
        $user->city    = $request->has('city')   ? $request->city   : $user->city;

        if($user->save())
            return response()->json([
                'msg'  => 'Information updated successfully ^_^ ',
                'info' => $user,
            ]);
        
        return response()->json(['error' => 'Something went wronge :(']);
    }
}
