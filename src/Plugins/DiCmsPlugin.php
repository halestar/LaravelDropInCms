<?php

namespace halestar\LaravelDropInCms\Plugins;
interface DiCmsPlugin
{
    /**
     * This function registers all the admin routes the wil be accessible through the admin cms
     * section
     * @return void
     */
    public static function adminRoutes(): void;

    /**
     * This function return true if the plugin has a route for the passed path.
     * @return void
     */
    public static function hasPublicRoute($path): bool;

    /**
     * This function will return the content, in string form, for the HTML to place in the
     * content area of the CMS.
     */
    public static function getPublicContent($path): string;

    /**
     * This function will return all the public pages that this plugin will allow attachments to.
     * @return array of DiCMSPluginPage
     */
    public static function getPublicPages(): array;

    /**
     * This function will return the Home object that the main system will use to direct the users
     * to the entry point of the plugin.
     */
    public static function getEntryPoint(): DiCmsPluginHome;

    /**
     *  This function returns a list of classes that have the trait BackUpable that this plugin needs
     * to be able to save to go back to normal in case of a wipe.
     */
    public static function getBackUpableTables(): array;

}
