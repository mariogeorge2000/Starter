<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
   public function redirect($service) //3ashan whatever the app we use (facebook, twitter...)
   {
     return Socialite::driver($service)->redirect(); //2olna service 3ashan yb2a dynamic msh 7aga mo3ayana..mmkn yb2a faceboo aw ay 7aga tania
   }

    public function callback($service)
    {
      return $user= Socialite::with($service)-> user();
    }
}
