<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\investFund;

class Fund extends Model
{
    //
    public function investFund()
    {
        return $this->hasMany(investFund::Class,'id','fund_id');
    }
}
