<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Treasury;
use App\User;
use App\Broker;
use App\TreasuryQuote;
use App\operationTreasury;

class operationTreasury extends Model
{
    //
    use SoftDeletes;
  
    protected $dates = ['created_at', 'updated_at', 'date_operation', 'date_invest'];
  
      public function user()
    {
        return $this->belongsTo(User::Class,'user_id','id');
    }    

  public function broker()
    {
        return $this->belongsTo(broker::Class,'broker_id','id');
    }  
  
      public function investTreasury()
    {
        return $this->belongsTo(investTreasury::Class,'invest_id','id');
    }
  
      public function treasury()
    {
        return $this->belongsTo(treasury::Class);
    }  
  
      public function treasuryQuote()
    {
        return $this->hasMany(treasuryQuote::Class, 'treasury_id', 'treasury_id');
    }
}
