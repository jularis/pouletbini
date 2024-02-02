<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController; 

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $activeTemplate;

    public function __construct()
    {
        $this->activeTemplate = activeTemplate(); 
    }

    public function userType($id){
         $user = User::findOrFail($id);
         if($user->user_type=='staff'){
            return $type = "staff";
         }
         if($user->user_type=='manager')
         {
            return $type ='manager';
         }
    }

}
