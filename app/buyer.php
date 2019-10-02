<?php

namespace App;

use App\Scopes\BuyerScope;
use App\Transformers\BuyerTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;

class buyer extends User
{

    public $transformer = BuyerTransformer::class;


    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new BuyerScope);
    }

    public function transactions()
    {
        return $this->hasMany('App\transaction');
    }
}

