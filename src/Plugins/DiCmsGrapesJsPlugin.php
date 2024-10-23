<?php

namespace halestar\LaravelDropInCms\Plugins;

use halestar\LaravelDropInCms\Classes\GrapesJsEditableItem;
use Illuminate\View\View;

/**
 * This interface exposes the public pages of a plugin.
 */
abstract class DiCmsGrapesJsPlugin
{
    /**
     * Thi function returns name of the plugin that will be included in the instance section of the
     *  GrapesJS editor, under which plugins to load.
     * @return string The name of the plugin
     */
    abstract public function getPluginName(): string;

    /**
     * This function returns whether this plugin should be included in the instance of
     * GrapesJs being rendered. As GrapesJs will only be used when editing an HTML-component,
     * the components (object) being edited will be passed. This will be expecting something
     * like a Page, Header, or Footer object, but it could be something custom built.
     * @param mixed $objEditing The object being edited
     * @return bool Whether this plugin should be included
     */
    abstract public function shouldInclude(GrapesJsEditableItem $objEditing): bool;

    /**
     * This function will return an \Illuminate\View\View (usually gotten from a view() declaration)
     * based on the $objEditing being passed. This declaration should always be preceded by
     * a shouldInclude($objEditing) call.
     * @param mixed $objEditing
     * @return View
     */
    abstract public function getConfigView(GrapesJsEditableItem $objEditing): string;

}
