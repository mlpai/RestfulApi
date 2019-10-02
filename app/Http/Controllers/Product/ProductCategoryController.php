<?php

namespace App\Http\Controllers\Product;

use App\category;
use App\product;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class ProductCategoryController extends ApiController
{
    function __construct()
    {
        $this->middleware('client.credentials')->only(['index']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index(product $product)
    {
        $categories = $product->categories;
        return $this->showAll($categories);
    }




    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, product $product, category $category)
    {
        $product->categories()->syncWithoutDetaching([$category->id]);

        return $this->showAll($product->categories);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(product $product, category $category)
    {
        if(!$product->categories()->find($category->id))
        {
            return $this->errorResponse('The specified category does not exists in this product',404);
        }

        $product->categories()->detach([$category->id]);

        return $this->showAll($product->categories);

    }
}
