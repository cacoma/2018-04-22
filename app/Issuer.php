<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Invest;
use App\investSecurity;

class Issuer extends Model
{
    //
   protected $fillable = [
        'name', 'cnpj',
    ];
   protected $hidden = [
        
    ];
  public function invests()
    {
        return $this->hasMany(Invest::Class,'id','issuer_id');
    }  
  
  public function investSecurity()
    {
        return $this->hasMany(investSecurity::Class,'id','issuer_id');
    }
}
