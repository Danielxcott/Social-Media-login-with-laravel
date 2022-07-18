<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    //gg
    public function redirectToGoogle(){
        return Socialite::driver('google')->redirect();
    }
    //fb
    public function redirectToFacebook(){
        return Socialite::driver('facebook')->redirect();
    }
    //github
    public function redirectToGithub(){
        return Socialite::driver('github')->redirect();
    }

    //all callback fn
    //gg
    public function handleGoogleCallback(){
        $user = Socialite::driver('google')->user();
        $this->_registerOrLoginUser($user);
        return redirect()->route('home');
    }
    //fb
    public function handleFacebookCallback(){
        $user = Socialite::driver('facebook')->user();
        $this->_registerOrLoginUser($user);
        return redirect()->route('home');
    }
    //github
    public function handleGithubCallback(){
        $user = Socialite::driver('github')->user();
        $this->_registerOrLoginUser($user);
        return redirect()->route('home');
    }

    public function _registerOrLoginUser($data)
    {
        $user = User::where("email",$data->email)->first();
        if(!$user){
            $user = new User();
            $user->name = $data->name;
            $user->email = $data->email;
            $user->password = Hash::make(uniqid());
            $user->provider_id = $data->id;
            $user->avator = $data->avatar;
            $user->save();
        }
        Auth::login($user);
    }
}
