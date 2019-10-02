<?php

namespace App\Http\Controllers\Buyer;

use App\buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(buyer $buyer)
    {
        //dd($buyer->product);
        $categories = $buyer->transactions()->with('product.categories')->get()
        ->pluck('product.categories')
        ->collapse()
        ->unique('id')
        ->values();
        return $this->showAll($categories);
    }
}
