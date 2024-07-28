<?php

namespace halestar\LaravelDropInCms\Plugins;

/**
 * This interface exposes the public pages of a plugin.
 */
class DiCmsPluginPage
{
    /**
     * the name of the page. SHouldbe inernationalized.
     */
    public string $name;

    /**
     * The URL of the page.
     */
    public string $url;

    public function __construct(string $name, string $url)
    {
        $this->name = $name;
        $this->url = $url;
    }
}
