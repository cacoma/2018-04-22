<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Invest;

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
}
