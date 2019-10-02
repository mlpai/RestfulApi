<?php

namespace App\Http\Controllers\Transaction;

use App\transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class TransactionSellerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(transaction $transaction)
    {
        $sellers = $transaction->product->seller;
        return $this->showOne($sellers);
    }
}
