<?php

namespace App\Http\Controllers\Category;

use App\category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CategorySellerController extends ApiController
{

    function __construct()
    {
        $this->middleware('client.credentials')->only(['index']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(category $category)
    {
        $sellers = $category->products()->with('seller')->get()
        ->pluck('seller')
        ->unique()
        ->values();
        return $this->showAll($sellers);
    }

}
