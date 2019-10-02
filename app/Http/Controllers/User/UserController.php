<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Mail\UserCreated;
use App\Transformers\UserTransformer;
use App\User;
use Illuminate\Support\Facades\Mail;

class UserController extends ApiController
{
    function __construct()
    {
        $this->middleware('client.credentials')->only(['store','resend']);
        $this->middleware('tranformer.input:'.UserTransformer::class)->only(['store','update']);
    }
    public function index()
    {
        $users = User::all();
        return $this->showAll($users);
    }


    public function store(Request $request)
    {
        $user_info = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        $user_info['password'] = bcrypt($request->password);
        $user_info['verified'] = User::UNVERIFIED_USER;
        $user_info['verification_token'] = User::generateVerificationCode();
        $user_info['admin'] = User::REGULAR_USER;

        $user=User::create($user_info);

        return $this->showOne($user,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $this->showOne($user);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,User $user)
    {
        $user_info = [
            'email' => 'email|unique:users,email'.$user->id,
            'password' => 'min:8|confirmed',
            'admin' => 'in:' . User::ADMIN_USER .','. User::REGULAR_USER,
        ];

        if($request->has('name'))
        {
            $user->name = $request->name;
        }

        if($request->has('email') && $user->email != $request->email)
        {
            $user->verified = User::UNVERIFIED_USER;
            $user->verification_token = User::generateVerificationCode();
            $user->email = $request->email;
        }

        if($request->has('password'))
        {
            $user->password = bcrypt($request->password);
        }

        if($request->has('admin'))
        {
            if(!$user->isVerified())
            {
                return $this->errorResponse('Only verified users can modify the admin field',409);
            }
        }

        if(!$user->isDirty())
        {
            return $this->errorResponse('You need to specifie fields',422);
        }

        $user->save();

        return $this->showone($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return $this->showOne($user);
    }

    public function verify($token)
    {
        $user = User::Where('verification_token',$token)->firstorfail();
        $user->verified = User::VERIFIED_USER;
        $user->verification_token = null;
        $user->save();

        return $this->showMessage(' The has been verified successfully');

    }

    public function resend(User $user)
    {
        if($user->isVerified())
        {
            return $this->errorResponse("User is already Verified",409);
        }

         retry(5, function() use($user) {
             Mail::to($user)->send(new UserCreated($user));
            }, 100 );

        return $this->showMessage('Email has been resent successfully');

    }
}
