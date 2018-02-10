<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class APIRegisterController extends Controller {
     
    public function verify(Request $request, $token) 
    { 
        // The verified method has been added to the user model and chained here 
        // for better readability 
        User::where('email_token', $token)->firstOrFail()->is_verified();
        return response()->json('verified'); 
    } 
}

