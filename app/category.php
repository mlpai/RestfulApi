<?php

namespace App;

use App\Transformers\CategoryTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class category extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable =[
        'name',
        'description',
    ];

    public $transformer = CategoryTransformer::class;


    protected $hidden = [
        'pivot',
    ];

    public function products()
    {
        return $this->belongsToMany('App\product');
    }
}
