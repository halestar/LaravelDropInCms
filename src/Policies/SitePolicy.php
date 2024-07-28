<?php

namespace halestar\LaravelDropInCms\Policies;

use App\Models\User;
use halestar\LaravelDropInCms\Models\Site;
use Illuminate\Support\Facades\Log;

class SitePolicy
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
    public function view(User $user = null, Site $site = null): bool
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
    public function update(User $user = null, Site $site = null): bool
    {
        return true;
    }

    /**
     * Determine whether the user can archive or unarchive the model.
     */
    public function archive(User $user = null, Site $site = null): bool
    {
        return true;
    }

    /**
     * Determine whether the user can activate or deactivate the model.
     */
    public function activate(User $user = null, Site $site = null): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function delete(User $user = null, Site $site = null): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently backup and restore the model
     */
    public function backup(User $user = null): bool
    {
        return true;
    }
}
