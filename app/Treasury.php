<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Invests;

class Treasury extends Model
{
    //
    protected $fillable = [
          '', '',
      ];
     protected $hidden = [
      ];

    protected $dates = ['created_at', 'updated_at', 'due_date'];

    public function invests()
      {
          return $this->hasMany(invests::Class,'id','stock_id');
      }
}
