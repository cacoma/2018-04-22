<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\MonthlyQuotes;
use App\Invests;
use App\investStock;

class stock extends Model
{
    //
      use Notifiable;
  
  protected $fillable = [
        'symbol', 'type',
    ];
   protected $hidden = [
    ];
  
  protected $dates = ['created_at', 'updated_at'];
  
  public function monthlyQuotes()
    {
        return $this->hasMany(monthlyQuotes::Class,'id','stock_id');
    }  
  public function dailyQuotes()
    {
        return $this->hasMany(dailyQuotes::Class,'id','stock_id');
    }
  
  public function intradayQuotes()
    {
        return $this->hasMany(dailyQuotes::Class,'id','stock_id');
    }
    
  public function invests()
    {
        return $this->hasMany(invests::Class,'id','stock_id');
    }  
  public function investStock()
    {
        return $this->hasMany(investStock::Class,'id','stock_id');
    }
    
  
}