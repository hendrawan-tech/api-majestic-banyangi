<?php

namespace App\Policies;

use App\Models\Like;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LikePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the like can view any models.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('list likes');
    }

    /**
     * Determine whether the like can view the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Like  $model
     * @return mixed
     */
    public function view(User $user, Like $model)
    {
        return $user->hasPermissionTo('view likes');
    }

    /**
     * Determine whether the like can create models.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create likes');
    }

    /**
     * Determine whether the like can update the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Like  $model
     * @return mixed
     */
    public function update(User $user, Like $model)
    {
        return $user->hasPermissionTo('update likes');
    }

    /**
     * Determine whether the like can delete the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Like  $model
     * @return mixed
     */
    public function delete(User $user, Like $model)
    {
        return $user->hasPermissionTo('delete likes');
    }

    /**
     * Determine whether the user can delete multiple instances of the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Like  $model
     * @return mixed
     */
    public function deleteAny(User $user)
    {
        return $user->hasPermissionTo('delete likes');
    }

    /**
     * Determine whether the like can restore the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Like  $model
     * @return mixed
     */
    public function restore(User $user, Like $model)
    {
        return false;
    }

    /**
     * Determine whether the like can permanently delete the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Like  $model
     * @return mixed
     */
    public function forceDelete(User $user, Like $model)
    {
        return false;
    }
}
