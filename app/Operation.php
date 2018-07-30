<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Invests;
use App\User;
use App\Broker;
use App\MonthlyQuote;
use App\TreasuryQuote;
use App\FundQuote;
use App\DailyQuote;
use App\Stock;
use App\Treasury;
use App\Issuer;
use App\Fund;
use App\Security;

class Operation extends Model
{
    //
    protected $dates = ['created_at', 'updated_at'];
  
    public function user()
    {
        return $this->belongsTo(User::Class,'user_id','id');
    }    
  public function invest()
    {
        return $this->belongsTo(Invest::Class,'inv_id','id');
    }

  public function broker()
    {
        return $this->belongsTo(broker::Class,'broker_id','id');
    }  
  
  public function issuer()
    {
        return $this->belongsTo(Issuer::Class,'issuer_id','id');
    }

    public function stock()
    {
        return $this->belongsTo(stock::Class);
    }

    public function treasury()
    {
        return $this->belongsTo(treasury::Class);
    }    
  
  public function security()
    {
        return $this->belongsTo(security::Class);
    }  
  
  public function fund()
    {
        return $this->belongsTo(fund::Class);
    }

    public function monthlyQuote()
    {
        return $this->hasMany(monthlyQuote::Class, 'stock_id', 'stock_id');
    }    
  
    public function treasuryQuote()
    {
        return $this->hasMany(treasuryQuote::Class, 'treasury_id', 'treasury_id');
    }
  
  public function fundQuote()
    {
        return $this->hasMany(fundQuote::Class, 'fund_id', 'fund_id');
    }
  public function dailyQuote()
    {
        return $this->hasMany(dailyQuote::Class, 'stock_id', 'stock_id');
    }

  public function intradayQuote()
    {
        return $this->hasMany(dailyQuote::Class, 'stock_id', 'stock_id');
    }

    public function lastMonthlyQuote()
    {
      return $this->monthlyQuote()->last();
    }

  public function lastIntradayQuote()
    {
      return $this->monthlyQuote()->last();
    }
}
