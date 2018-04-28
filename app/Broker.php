<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Invest;

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
}