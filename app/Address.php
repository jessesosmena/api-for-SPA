<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
       
      'name', 'address', 'city', 'zip', 'country', 'phone', 'user_id', 'amount'
      
    ];
}
