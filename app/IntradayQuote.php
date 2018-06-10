<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IntradayQuote extends Model
{
    //
      protected $table = 'intraday_quotes';
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
