<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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

    public function username() //el method elly bynadi 3aleha 3ashan y3rf enta ht3ml login b aih ?
    {
        //request de helper method btsa3edni a5ot el data elly fel form f shakl array
        $value= request() -> input('identify');

       $field = filter_var($value,FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';

       request()->merge([$field=>$value]);
       return $field;
    }
}
