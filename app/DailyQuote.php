<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Invest;
use App\Stock;

class DailyQuote extends Model
{
    //
    protected $table = 'daily_quotes';
  protected $dates = ['timestamp','created_at','updated_at'];
  
  public function stock()
    {
        return $this->belongsTo(stock::Class,'stock_id','id');
    }
    
    public function invest()
        {
            return $this->belongsTo(invest::Class, 'stock_id', 'stock_id');
        }
  
}
