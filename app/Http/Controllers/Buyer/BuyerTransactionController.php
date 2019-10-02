<?php

namespace App\Http\Controllers\Buyer;

use App\buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\transaction;

class BuyerTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(buyer $buyer)
    {
        $transations = $buyer->transactions;
        return $this->showAll($transations);
    }

}
