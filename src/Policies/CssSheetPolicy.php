<?php

namespace halestar\LaravelDropInCms\Policies;

use halestar\LaravelDropInCms\Models\CssSheet;

class CssSheetPolicy
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
    public function view($user = null, CssSheet $sheet = null): bool
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
    public function update($user = null, CssSheet $sheet = null): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function delete($user = null, CssSheet $sheet = null): bool
    {
        return true;
    }
}
