<?php

namespace halestar\LaravelDropInCms\Plugins;

use Illuminate\Database\Eloquent\Model;

/**
 * This object is the representation of a simple menu item that will return the entry point to your plugin.
 * This will be the only connection between the main CMS system and your plugin that should run in its own
 * space.
 */
interface DiCmsPluginHome
{
    /**
     * THe URl to the entry point of your plugin from the ADMIN side.
     * @return string The actual URL
     */
    public function getAdminUrl(): string;

    /**
     * The name that will be displayed in the menu. Should be internaliionalized.
     * @return string the name.
     */
    public function getPluginMenuName(): string;

    /**
     * This function returns the object that the main system will match agaainst the 'viewAny' permission, which is
     * required to views the menu from the admin site.
     */
    public function getPolicyModel(): string;

    /**
     *  This is the route prefix that is applied to your routes. Mainly used to check if the user is in your
     * section to highlight certain items.
     */
    public function getRoutePrefix(): string;

}
