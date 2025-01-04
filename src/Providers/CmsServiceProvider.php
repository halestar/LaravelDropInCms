<?php

namespace halestar\LaravelDropInCms\Providers;

use halestar\LaravelDropInCms\Classes\MetadataEntrySynth;
use halestar\LaravelDropInCms\Commands\BackupCms;
use halestar\LaravelDropInCms\Commands\RestoreCms;
use halestar\LaravelDropInCms\Livewire\AssetManager;
use halestar\LaravelDropInCms\Livewire\CssSheetManager;
use halestar\LaravelDropInCms\Livewire\JsScriptManager;
use halestar\LaravelDropInCms\Livewire\MetadataEditor;
use halestar\LaravelDropInCms\Livewire\PageViewsCounterConfig;
use halestar\LaravelDropInCms\View\Components\ErrorDisplay;
use halestar\LaravelDropInCms\View\Components\PageViewsCounter;
use halestar\LaravelDropInCms\View\Components\WebEditor;
use halestar\LaravelDropInCms\View\Composers\CurrentSiteViewComposer;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class CmsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/dicms.php' => config_path('dicms.php'),
        ], 'dicms');

        $this->publishes(
            [
                __DIR__.'/../Policies' => app_path('Policies/DiCms')
            ], 'dicms-policies'
        );

        $this->publishesMigrations([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'dicms');


        foreach(config('dicms.policies', []) as $objClass => $policyClass)
            Gate::policy($objClass, $policyClass);


        $this->loadViewsFrom(__DIR__.'/../views', 'dicms');
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'dicms');
        Blade::component('error-display', ErrorDisplay::class);
        Blade::component('web-editor', WebEditor::class);
        Blade::component('page-views-counter', PageViewsCounter::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                BackupCms::class,
                RestoreCms::class,
            ]);
        }
        Livewire::component('css-sheet-manager', CssSheetManager::class);
        Livewire::component('js-script-manager', JsScriptManager::class);
        Livewire::component('asset-manager', AssetManager::class);
        Livewire::component('page-views-counter-config', PageViewsCounterConfig::class);
        Livewire::component('metadata-editor', MetadataEditor::class);
        Livewire::propertySynthesizer(MetadataEntrySynth::class);

        View::composer('dicms::*', CurrentSiteViewComposer::class);
    }
}
