<?php

namespace halestar\LaravelDropInCms\Policies;

use halestar\LaravelDropInCms\Models\Site;

class SitePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny($user = null): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view($user = null, Site $site = null): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create($user = null): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update($user = null, Site $site = null): bool
    {
        return true;
    }

    /**
     * Determine whether the user can archive or unarchive the model.
     */
    public function archive($user = null, Site $site = null): bool
    {
        return true;
    }

    /**
     * Determine whether the user can activate or deactivate the model.
     */
    public function activate($user = null, Site $site = null): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function delete($user = null, Site $site = null): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently backup and restore the model
     */
    public function backup($user = null): bool
    {
        return true;
    }

    public function preview($user = null, Site $site = null): bool
    {
        return true;
    }

    public function widgets($user = null): bool
    {
        return true;
    }
}
