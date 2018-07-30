<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Invests;
use App\investFund;
use App\Fund;

class FundQuote extends Model
{
    //
    protected $table = 'fund_quotes';
  protected $dates = ['comp_date','created_at','updated_at'];
  
  public function fund()
    {
        return $this->belongsTo(fund::Class,'fund_id','id');
    }
    
    public function invest()
        {
            return $this->belongsTo(invest::Class, 'fund_id', 'fund_id');
        }    
  
  public function investFund()
        {
            return $this->belongsTo(investFund::Class, 'fund_id', 'fund_id');
        }
}
