<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Index extends Model
{
    //
   protected $fillable = [
        'symbol','bc_code', 'type',
    ];
   protected $hidden = [
        
    ];

}
