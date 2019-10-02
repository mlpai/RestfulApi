<?php

namespace App\Http\Controllers\Buyer;

use App\buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerSellerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(buyer $buyer)
    {
        $seller = $buyer->transactions()->with('product.seller')->get()
        ->pluck('product.seller')
        ->unique('id')
        ->values();
        return $this->showAll($seller);
    }
}
