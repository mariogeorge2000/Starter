<?php

namespace App\Http\Controllers\Front;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class UserController extends BaseController
{
 public function showAdminName()
    {
       return 'ahmed emam';
    }

    public function getIndex(){
        $data=[];
        $data['id']=5;
        $data['name']='ahmed emam';
        return view('welcome',$data);
    }
}
