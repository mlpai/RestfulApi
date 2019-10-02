<?php

namespace App;

use App\Transformers\TransactionTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class transaction extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable =[
        'quantity',
        'buyer_id',
        'product_id',
    ];

    public $transformer = TransactionTransformer::class;


    public function product()
    {
        return $this->belongsTo('App\product');
    }

    public function buyer()
    {
        return $this->belongsTo('App\buyer');
    }
}
