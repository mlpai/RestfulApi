<?php

namespace App\Http\Controllers\Buyer;

use App\buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $buyers = buyer::has('transactions')->get();
        return $this->showAll($buyers);
    }


    public function show(buyer $buyer)
    {
        //$buyer = buyer::has('transactions')->findorfail($id);
        return $this->showOne($buyer);
    }

}
