<?php

namespace halestar\LaravelDropInCms\Plugins;

interface DiCmsWidget
{

    /**
     * @return string a unique id for this widget, which will be some string (usually the name)
     * with no spaces. Best practice is for the name in lowercase with '-' instead of spaces.
     */
    public static function widgetId(): string;

    /**
     * @return string The name of the widget. No spaces.
     */
    public static function widgetName(): string;

    /**
     * @return string The icon that will be displayed for this widget in
     * the HTML editor
     */
    public static function widgetIcon(): string;

    /**
     * @return string The description of this widget
     */
    public static function widgetDescription(): string;

    /**
     * @return string The HTML representation of the component in "dummy"
     * form, meaning that this should not be functional (as no page is passed)
     * but rather a representation of what the widget should appear to users.
     */
    public static function widgetControlHtml(): string;

    /**
     * @return string The HTML representation of the component that will
     * be directly pasted in the page. There will be an HTML variable, $page
     * available to the widget through Blade.
     */
    public static function widgetHtml(): string;

    /**
     * @return string This function will return the HTML representation of the configuration widget. This
     * widget is a livewire (or blade) component that will be used to configure this
     * widget. It will be set in a specific route as a full-page component.
     */
    public static function widgetConfig(): string;
}
