<?php

namespace App\Http\Controllers\Seller;

use App\seller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class SellerCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(seller $seller)
    {
        $categories = $seller->products()->with('categories')
        ->get()
        ->pluck('categories')
        ->collapse()
        ->unique('id')
        ->values();
        return $this->showAll($categories);
    }




}
