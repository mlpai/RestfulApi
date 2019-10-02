<?php

namespace App\Http\Controllers\Product;

use App\product;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\transaction;
use App\Transformers\TransactionTransformer;
use App\User;
use Illuminate\Support\Facades\DB;

class ProductBuyerTransactionController extends ApiController
{
    function __construct()
    {
        parent::__construct();
        $this->middleware('transform.input:'.TransactionTransformer::class)->only(['store']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, product $product, User $buyer)
    {

        $rules = [
            'quantity' => 'required|integer|min:1',
        ];

        $this->validate($request,$rules);

        if($buyer->id == $product->seller->id)
        {
            return  $this->errorResponse("The buyer must be diffrent from seller",409);
        }

        if(!$buyer->isVerified())
        {
            return $this->errorResponse("User must be verified Buyer",409);
        }

        if(!$product->seller->isVerified())
        {
            return $this->errorResponse("Seller must be verified User",409);
        }

        if(!$product->isAvailable())
        {
            return $this->errorResponse("Product is not available",409);
        }

        if($product->quantity <  $request->quantity)
        {
            return $this->errorResponse("This product does not have enough quantity",409);
        }

        return DB::transaction(function() use($request, $product, $buyer) {
            $product->quantity -= $request->quantity;
            if($product->quantity == 0 && $product->isAvailable())
                {
                    $product->status = product::UNAVAILABLE_PRODUCT;
                }
            $product->save();

            $transaction = transaction::create([
                'quantity' => $request->quantity,
                'buyer_id' => $buyer->id,
                'product_id' => $product->id,
            ]);

            return $this->showOne($transaction,201);
        });

    }

}
