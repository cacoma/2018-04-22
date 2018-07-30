<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Stock;
use App\User;
use App\Broker;
use App\DailyQuote;
use App\operationStock;

class operationStock extends Model
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
  
      public function investStock()
    {
        return $this->belongsTo(investStock::Class,'invest_id','id');
    }
  
      public function stock()
    {
        return $this->belongsTo(stock::Class);
    }
  
    public function dailyQuote()
    {
        return $this->hasMany(dailyQuote::Class, 'stock_id', 'stock_id');
    }
}
