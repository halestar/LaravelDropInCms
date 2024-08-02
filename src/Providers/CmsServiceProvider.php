<?php

namespace halestar\LaravelDropInCms\Providers;

use halestar\LaravelDropInCms\View\Components\ErrorDisplay;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class CmsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/dicms.php' => config_path('dicms.php'),
        ], 'dicms');

        $this->publishesMigrations([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'dicms');

        foreach(config('dicms.policies', []) as $objClass => $policyClass)
            Gate::policy($objClass, $policyClass);
        $this->loadViewsFrom(__DIR__.'/../views', 'dicms');
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'dicms');
        Blade::component('error-display', ErrorDisplay::class);
    }
}
