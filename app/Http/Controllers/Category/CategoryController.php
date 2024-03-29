<?php

namespace App\Http\Controllers\Category;

use App\category;
use Illuminate\Http\Request;

use App\Http\Controllers\ApiController;
use App\Transformers\CategoryTransformer;

class CategoryController extends ApiController
{

    function __construct()
    {
        $this->middleware('client.credentials')->only(['index','show']);
        $this->middleware('transform.input:'.CategoryTransformer::class)->only(['store','update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = category::all();
        return $this->showAll($categories);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $category = $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

       $newCategory = category::create($category);

        return $this->showOne($newCategory);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(category $category)
    {
        return $this->showOne($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, category $category)
    {
        $category->fill($request->only([
            'name',
            'description',
        ]));

        if($category->isClean())
        {
            return $this->errorResponse('You need to specify and diffrent value',422);
        }

        $category->save();

        return $this->showOne($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(category $category)
    {
        $category->delete();
        return $this->showOne($category);
    }
}
