<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api','verified'], ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = Category::forPage($request->page, 5)->get();
        return response()->json($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Category::class);

        $request->validate([
            'name'        => 'string|required|unique:categories,name',
            'description' => 'string|nullable',
            'image'       => 'image|required',
        ]);

        $temp = [
            'name'        => $request->name,
            'description' => $request->description,
            'image'       => $request->image->store('storage'),
        ];

        if($category = Category::create($temp))
            return response()->json([
                'msg'      => 'Category created successfully',
                'category' => $category,
            ]);
        
        return response()->json(['msg' => 'Something went wronge!!!']);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::find($id);
        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if($category = Category::find($request->id)){

            $this->authorize('update', Category::class);

            $request->validate([
                'description' => 'string|nullable',
                'image'       => 'image|nullable',
            ]);

            $category->description = $request->has('description')? $request->description: null;

            if($request->hasFile('image')){
                unlink($category->image);
                $category->image = $request->image->store('storage');
            }

            if($category->save())
                return response()->json([
                    'msg'      => 'Category updated successfully :) ',
                    'category' => $category,
                ]);

        }

        return response()->json(['error' => 'Category doesn\'t exist!!']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if($category = Category::find($request->id))
        {
            $this->authorize('delete', Category::class);

            unlink($category->image);
            $category->delete();

            return response()->json(['msg' => 'Category deleted successfully']);
        }

        return response()->json(['error' => 'Category doesn\'t exist!!']);
    }
}
