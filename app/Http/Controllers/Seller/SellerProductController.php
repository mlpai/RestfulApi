<?php

namespace App\Http\Controllers\Seller;

use App\seller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\product;
use App\Transformers\ProductTransformer;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;
class SellerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        parent::__construct();
        $this->middleware('tranformer.input:'.ProductTransformer::class)->only(['store','update']);
    }

    public function index(seller $seller)
    {
        $products = $seller->products;
        return $this->showAll($products);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,seller $seller)
    {
        $data = $request->validate([
            'name'=> 'required',
            'description'=> 'required',
            'quantity'=> 'required|integer|min:1',
            'image'=> 'required|image',
        ]);
        $data['status'] = product::UNAVAILABLE_PRODUCT;
        $data['image'] = $request->image->store('');
//        $data['seller_id'] = $seller->id;

        $product = $seller->products()->create($data);

        return $this->showOne($product);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, seller $seller,product $product)
    {
        $rules = [
            'quantity' => 'integer|min:1',
            'status' => 'in:'.product::AVAILABLE_PRODUCT.','.product::UNAVAILABLE_PRODUCT,
            'image'=> 'image',
        ];

        $this->validate($request,$rules);

        $this->checkSeller($seller, $product);

        $product->fill($request->only([
            'name',
            'description',
            'quantity',
        ]));

        if($request->has('image'))
        {
            Storage::delete($product->image);
            $product->image = $request->image->store('');
        }



        if ($product->isClean()) {
            return $this->errorResponse("you need to specify a diffrent value to update",422);
        }

        if($request->has('status'))
        {
            $product->status = $request->status;
            if ($product->isAvailable() && $product->categories()->count() == 0) {
                return $this->errorResponse("An active product must have at least one  category",409);
            }
        }

        $product->save();

        return $this->showOne($product);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function destroy(seller $seller,product $product)
    {
        $this->checkSeller($seller,$product);
        $product->delete();
        Storage::delete($product->image);

        return $this->showOne($product);

    }

    protected function checkSeller(seller $seller, product $product)
    {
        if ($seller->id != $product->seller_id) {
            throw new HttpException(422,"The Specific Seller is not the actual seller of the product");
        }
    }

}
