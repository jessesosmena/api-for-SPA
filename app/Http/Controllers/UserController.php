<?php

namespace App\Http\Controllers;

use Mail;
use App\User;
use Validator;
use Illuminate\Http\Request;
use App\Mail\EmailVerification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;


class UserController extends Controller
{

    public function store(Request $request){

        $v = validator($request->only('email', 'username', 'password'), [
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($v->fails()) {
            return response()->json($v->errors()->all(), 400);
        }

        $data = request()->only('email','username','password');

        $user = \App\User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'email_token' => str_random(10),
        ]);

        // After creating the user send an email with the random token generated in the create method above 
        $email = new EmailVerification(new User(['email_token' => $user->email_token, 'username' => $user->username])); 
  
        Mail::to($user->email)->send($email); 
  
        $client = \Laravel\Passport\Client::where('password_client', 1)->first();

        $request->request->add([
            'grant_type'    => 'password',
            'client_id'     => $client->id,
            'client_secret' => $client->secret,
            'username'      => $data['email'],
            'password'      => $data['password'],
            'scope'         => null,
        ]);
        
        // Fire off the internal request. 
        $proxy = Request::create(
            'oauth/token',
            'POST'
        );

        return Route::dispatch($proxy);
    }

    public function update(Request $request, $id){

        $data = request()->only('email','username','password');

        $userData = ([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $user = User::find($id);

        $user->update($userData); 

        return response()->json($user); 
    }
}
