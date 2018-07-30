<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Fund;
use App\User;
use App\Broker;
use App\FundQuote;
use App\operationFund;

class investFund extends Model
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
    public function fund()
    {
        return $this->belongsTo(fund::Class);
    }
      public function operationFund()
    {
        return $this->hasMany(operationFund::Class,'invest_id','id');
    }
}
