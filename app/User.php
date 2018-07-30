<?php
namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\invest;
use App\investTreasury;
use App\investStock;
use App\investFund;
use App\investSecurity;


class User extends Authenticatable
{
    use Notifiable;
    protected $table = 'users';
    protected $dates = ['created_at', 'updated_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','role_id'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function invests()
    {
        return $this->hasMany(Invest::Class,'id','user_id');
    }    
  public function investStock()
    {
        return $this->hasMany(investStock::Class,'id','user_id');
    }    
  public function investTreasury()
    {
        return $this->hasMany(investTreasury::Class,'id','user_id');
    }    
  public function investSecurity()
    {
        return $this->hasMany(investSecurity::Class,'id','user_id');
    }    
  public function investFund()
    {
        return $this->hasMany(investFund::Class,'id','user_id');
    }
}