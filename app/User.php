<?php

namespace App;

use App\Transformers\UserTransformer;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens , SoftDeletes;
    protected $dates = ['deleted_at'];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

     const VERIFIED_USER = 1;
     const UNVERIFIED_USER = 0;

     const ADMIN_USER = 'true';
     const REGULAR_USER = 'false';


     protected $table = 'users';

     public $transformer = UserTransformer::class;

    protected $fillable = [
        'name', 'email', 'password','verified','verification_token','admin'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','verification_token',
    ];

    public function isVerified()
    {
        return $this->verified == User::VERIFIED_USER;
    }

    public function isAdmin()
    {
        return $this->admin == User::ADMIN_USER;
    }

    public static function generateVerificationCode()
    {
        return str_random(40);
    }

    public function setNameAttribute($name)
    {
        return $this->attributes['name'] = strtolower($name);
    }

    public function getNameAttribute($name)
    {
        return $this->attributes['name'] = ucwords($name);
    }


    public function setEmailAttribute($email)
    {
        return $this->attributes['email'] = strtolower($email);
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
