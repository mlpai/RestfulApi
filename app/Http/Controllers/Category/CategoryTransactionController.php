<?php

namespace App\Http\Controllers\Category;

use App\category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CategoryTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(category $category)
    {
        $transactions = $category->products()->with('transactions')->get()
        ->pluck('transactions')
        ->collapse()
        ->unique()
        ->values();
        return $this->showAll($transactions);
    }


}
