<?php

namespace App\Providers;

use App\Mail\UserCreated;
use App\Mail\UserEmailChanged;
use App\product;
use App\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Schema::defaultStringLength(191);

        User::created(function($user)
        {
             retry(5, function() use($user) {
                 Mail::to($user)->send(new UserCreated($user));
            },100);
        });

        User::updated(function($user)
        {
            if($user->isDirty('email'))
            {
                retry(5, function() use($user) {
                Mail::to($user)->send(new UserEmailChanged($user));
                },100);
            }
        });

    }
}
