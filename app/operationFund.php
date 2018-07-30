<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Fund;
use App\User;
use App\Broker;
use App\FundQuote;
use App\operationFund;

class operationFund extends Model
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
  
    public function investFund()
    {
        return $this->belongsTo(investFund::Class,'invest_id','id');
    }
  
    public function fund()
    {
        return $this->belongsTo(fund::Class);
    }
  
    public function fundQuote()
    {
        return $this->hasMany(fundQuote::Class, 'fund_id', 'fund_id');
    }
}
