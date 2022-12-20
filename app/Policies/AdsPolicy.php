<?php

namespace App\Policies;

use App\Models\User;
use App\Models\advertisement;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class AdsPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\advertisement  $advertisement
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, advertisement $advertisement)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->type == 'user';
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\advertisement  $advertisement
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, advertisement $advertisement)
    {
        return $user->id == $advertisement->user;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\advertisement  $advertisement
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, advertisement $advertisement)
    {
        return ($user->id == $advertisement->user || $user->type == 'admin');
    }

}
