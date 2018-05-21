<?php

namespace App\Policies;

use App\User;
use App\Invest;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvestPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the invest.
     *
     * @param  \App\User  $user
     * @param  \App\Invest  $invest
     * @return mixed
     */
    public function view(User $user, Invest $invest)
    {
        //
      return true;
    }

    /**
     * Determine whether the user can create invests.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
      return true;
    }

    /**
     * Determine whether the user can update the invest.
     *
     * @param  \App\User  $user
     * @param  \App\Invest  $invest
     * @return mixed
     */
    public function update(User $user, Invest $invest)
    {
        //
      return $user->id == $invest->user_id or $user->role_id == '1';
    }

    /**
     * Determine whether the user can delete the invest.
     *
     * @param  \App\User  $user
     * @param  \App\Invest  $invest
     * @return mixed
     */
    public function delete(User $user, Invest $invest)
    {
        //
      return $user->role_id == '1';
    }
}
