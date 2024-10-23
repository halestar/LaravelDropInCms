<?php

namespace halestar\LaravelDropInCms\Interfaces;

interface DiCmsSetting
{
    /**
     * Gets the setting by key. Optional default value.
     */
    public static function get($key, $default = null): mixed;

    /**
     * Set the setting by key. If the setting does not exist, create one.
     */
    public static function set($key, mixed $value): void;
}
