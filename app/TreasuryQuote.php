<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Invests;

class TreasuryQuote extends Model
{
    //
  protected $table = 'treasury_quotes';
  protected $dates = ['timestamp','created_at','updated_at'];
  
  public function treasury()
    {
        return $this->belongsTo(stock::Class,'treasury_id','id');
    }
    
    public function invest()
        {
            return $this->belongsTo(invest::Class, 'treasury_id', 'treasury_id');
        }
  
}
