<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\seller;

class SellerController extends ApiController
{
    function __construct()
    {
        $this->middleware('client.credentials')->only(['index','show']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sellers = seller::has('products')->get();
        return $this->showAll($sellers);
    }

    public function show(Seller $seller)
    {
        return $this->showOne($seller);
    }

}
