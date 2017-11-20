<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as HelpController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends HelpController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function AdminValidator()
    {
        $user = Auth()->user();
        
        if ($user->permission == 0) {
            abort(403);
        }
    }

    public function LoginValidator()
    {
    	if(auth()->user() == null) abort(403); 
    }


}
