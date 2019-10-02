<?php

namespace App\Http\Controllers\Buyer;

use App\buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\product;

class BuyerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(buyer $buyer)
    {
        $products = $buyer->transactions()->with('product')->get()->pluck('product');
        return $this->showAll($products);
    }

}
