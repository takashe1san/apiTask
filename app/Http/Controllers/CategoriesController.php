<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCategoryRequest;
use App\Http\Requests\EditCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api','verified'], ['except' => ['getAll', 'get']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getAll(Request $request)
    {
        $categories = Category::forPage($request->page, 5)->get();
        return apiResponse(1, $categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add(AddCategoryRequest $request)
    {
        $this->authorize('create', Category::class);

        $category = new Category([
            'name'        => $request->name,
            'description' => $request->description,
            'image'       => $request->image->store('storage'),
        ]);

        if($category->save())
            return apiResponse(1, $category);
        else
            return apiResponse(0, 'Category doesn\'t created!!!');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function get($id)
    {
        if( $category = Category::find($id))
            return apiResponse(1, $category);
        else
            return apiResponse(0, 'Category is not exists!!');

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EditCategoryRequest $request)
    {
        if($category = Category::find($request->id)){

            $this->authorize('update', Category::class);

            $category->description = $request->has('description')? $request->description: null;

            if($request->hasFile('image')){
                unlink($category->image);
                $category->image = $request->image->store('storage');
            }

            if($category->save())
                return apiResponse(1, $category);
            else
                return apiResponse(0, 'Category editing failed!!');

        }

        return apiResponse(0, 'Category doesn\'t exist!!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        if($category = Category::find($id))
        {
            $this->authorize('delete', Category::class);

            unlink($category->image);
            if($category->delete())
                return apiResponse(1, 'Category deleted successfully');
            else
                return apiResponse(0, 'Category deleting failed!!!');
        }

        return apiResponse(0, 'Category doesn\'t exist!!');
    }
}
