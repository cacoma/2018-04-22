<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Invests;

class Security extends Model
{
    //
    protected $dates = ['created_at', 'updated_at'];

    public function invests()
      {
          return $this->hasMany(invests::Class,'id','security_id');
      }
}
