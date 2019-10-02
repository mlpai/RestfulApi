<?php

namespace App;

use App\Transformers\ProductTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class product extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    const AVAILABLE_PRODUCT = 'available';
    const UNAVAILABLE_PRODUCT = 'unavailable';

    protected $fillable =[
        'name',
        'description',
        'quantity',
        'status',
        'image',
        'seller_id',
    ];

    public $transformer = ProductTransformer::class;


    protected $hidden = [
        'pivot'
    ];

    public function seller()
    {
        return $this->belongsTo('App\seller');
    }

    public function transactions()
    {
        return $this->hasMany('App\transaction');
    }

    public function categories()
    {
        return $this->belongsToMany('App\category');
    }

    public function isAvailable()
    {
        return $this->status = product::AVAILABLE_PRODUCT;
    }
}
