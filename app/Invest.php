<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
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
use App\Operation;

class invest extends Model
{
    //
  use SoftDeletes;
  protected $table = 'invests';
  //Accessors and mutators allow you to format Eloquent attribute values when you retrieve or set them on model instances.
  //The date will properly be stored and converted on output so long as you add the field to your $dates property on your model.
  protected $dates = ['created_at','updated_at','date_invest','deleted_at'];

  protected $fillable = [
    'type', 'symbol', 'quant', 'price',	'created_at',	'user_id', 'date_invest', 'broker_fee','broker_id',
  ];

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
  
    public function operation()
    {
        return $this->hasMany(operation::Class,'inv_id','id');
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
