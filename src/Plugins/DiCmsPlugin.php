<?php

namespace halestar\LaravelDropInCms\Plugins;
use halestar\LaravelDropInCms\Models\Page;

interface DiCmsPlugin
{
    /**
     * This function registers all the admin routes the wil be accessible through the admin cms
     * section
     * @return void
     */
    public static function adminRoutes(): void;

    /**
     * This function registers all the API routes the wil be accessible
     * @return void
     */
    public static function apiRoutes(): void;

    /**
     * THe URl to the entry point of your plugin from the ADMIN side.
     * @return string The actual URL
     */
    public static function getAdminUrl(): string;

    /**
     * The name that will be displayed in the menu. Should be internaliionalized.
     * @return string the name.
     */
    public static function getPluginMenuName(): string;

    /**
     * This function returns the object that the main system will match agaainst the 'viewAny' permission, which is
     * required to views the menu from the admin site.
     */
    public static function getPolicyModel(): string;

    /**
     *  This is the route prefix that is applied to your routes. Mainly used to check if the user is in your
     * section to highlight certain items.
     */
    public static function getRoutePrefix(): string;

    /**
     * This function return null if there is no page for that url, else it will return the
     * Page object to render.
     * @param string|null $path The path that the user is trying to reach
     * @return Page|null Null if it doesn't have a path, else the Page to display
     */
    public static function hasPublicRoute(?string $path): ?Page;


    /**
     * This function will return all the public pages that this plugin will allow attachments to.
     * @return array of Page objects
     */
    public static function getPublicPages(): array;

    /**
     *  This function returns a list of classes that have the trait BackUpable that this plugin needs
     * to be able to save to go back to normal in case of a wipe.
     */
    public static function getBackUpableTables(): array;

    /**
     * This function returns an array of DiCmsGrapesJsPlugin objects to insert into the
     * grapesjs editor.
     */
    public static function getGrapesJsPlugins():array;

    /**
     * This function will take a Page object that we're trying to render that is
     * listed to belong to a plugin. It is expected to return the CSS that is supposed to be
     * rendered for the page. You can return the same data directly from $page->css, or
     * return something specific for your plugin
     */
    public static function projectCss(Page $page): string;

    /**
     *  Similar to projectCss function, except this one is for the HTMl to be rendered.
     */
    public static function projectHtml(Page $page): string;

    /**
     * Similar to projectCss only this will get custom metadata from the plugin,
     * based on a passed page.
     */
    public static function projectMetadata(Page $page): array;

    /**
     * This function returns an array of all the widgets that this plugin
     * provides. The array must contain objects extending the
     * DiCmsWidget interface
     * @see DiCmsWidget
     */
    public static function widgets(): array;


}
