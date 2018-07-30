<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Security;
use App\User;
use App\Broker;
use App\Issuer;
use App\operationSecurity;

class investSecurity extends Model
{
    //
  use SoftDeletes;
  
  protected $dates = ['created_at','updated_at','date_invest','deleted_at'];
  
    public function user()
    {
        return $this->belongsTo(User::Class,'user_id','id');
    }

  public function broker()
    {
        return $this->belongsTo(broker::Class,'broker_id','id');
    }  
    public function issuer()
    {
        return $this->belongsTo(Issuer::Class,'issuer_id','id');
    }
    public function security()
    {
        return $this->belongsTo(security::Class);
    } 
      public function operationSecurity()
    {
        return $this->hasMany(operationSecurity::Class,'invest_id','id');
    }
}
