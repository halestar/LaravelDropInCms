<?php

namespace halestar\LaravelDropInCms\Policies;

use App\Models\User;
use halestar\LaravelDropInCms\Models\CssSheet;
use halestar\LaravelDropInCms\Models\Header;
use halestar\LaravelDropInCms\Models\JsScript;
use halestar\LaravelDropInCms\Models\Site;
use Illuminate\Support\Facades\Log;

class JsScriptPolicy
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
    public function view(User $user = null, JsScript $script = null): bool
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
    public function update(User $user = null, JsScript $script = null): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function delete(User $user = null, JsScript $script = null): bool
    {
        return true;
    }
}
