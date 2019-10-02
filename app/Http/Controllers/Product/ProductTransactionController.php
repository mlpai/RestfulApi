<?php

namespace App\Http\Controllers\Product;

use App\product;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class ProductTransactionController extends ApiController
{

    public function index(product $product)
    {
        $buyers = $product->transactions;
        return $this->showAll($buyers);
    }


}
