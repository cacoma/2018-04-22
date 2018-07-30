<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Invest;
use App\investTreasury;
use App\investStock;
use App\investFund;
use App\investSecurity;

class broker extends Model
{
    //
  
    protected $fillable = [
        'name', 'cnpj',
    ];
   protected $hidden = [
        
    ];
  public function invests()
    {
        return $this->hasMany(Invest::Class,'id','broker_id');
    }
    public function investStock()
    {
        return $this->hasMany(investStock::Class,'id','broker_id');
    }    
  public function investTreasury()
    {
        return $this->hasMany(investTreasury::Class,'id','broker_id');
    }    
  public function investSecurity()
    {
        return $this->hasMany(investSecurity::Class,'id','broker_id');
    }    
  public function investFund()
    {
        return $this->hasMany(investFund::Class,'id','broker_id');
    }
}