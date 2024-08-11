<?php

namespace halestar\LaravelDropInCms\Policies;

use App\Models\User;
use halestar\LaravelDropInCms\Models\Page;

class DataItemPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user = null): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user = null, Page $page = null): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user = null): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user = null, Page $page = null): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function publish(User $user = null, Page $page = null): bool
    {
        return true;
    }


    /**
     * Determine whether the user can permanently delete the model.
     */
    public function delete(User $user = null, Page $page = null): bool
    {
        return true;
    }
}
