<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens; // allow to inspect authenticated user token

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'email_token', 'verified'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // Set the verified status to true and make the email token null 
  
    public function is_verified() 
    { 
        $this->verified = 1; 
        $this->email_token = null; 
      
        $this->save(); 
    }

    public function findForPassport($username){

      return $this->where('email', $username)->orWhere('username', $username)->first();

    }

}
