<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\MonthlyQuotes;
use App\Invests;
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
    
  public function invests()
    {
        return $this->hasMany(invests::Class,'id','stock_id');
    }
    
  
}