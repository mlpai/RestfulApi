<?php

namespace App\Http\Controllers\Transaction;

use App\transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class TransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactions = transaction::all();
        return $this->showAll($transactions);
    }


    public function show(transaction $transaction)
    {
        return $this->showOne($transaction);
    }


}
