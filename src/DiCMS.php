<?php

namespace halestar\LaravelDropInCms;
use halestar\LaravelDropInCms\Controllers\API\CssSheetApiController;
use halestar\LaravelDropInCms\Controllers\API\DataItemApiController;
use halestar\LaravelDropInCms\Controllers\API\FooterApiController;
use halestar\LaravelDropInCms\Controllers\API\HeaderApiController;
use halestar\LaravelDropInCms\Controllers\API\JsScriptApiController;
use halestar\LaravelDropInCms\Controllers\API\PageApiController;
use halestar\LaravelDropInCms\Controllers\API\SettingApiController;
use halestar\LaravelDropInCms\Controllers\API\SiteApiController;
use halestar\LaravelDropInCms\Controllers\BackupController;
use halestar\LaravelDropInCms\Controllers\CssSheetController;
use halestar\LaravelDropInCms\Controllers\DataItemController;
use halestar\LaravelDropInCms\Controllers\FooterController;
use halestar\LaravelDropInCms\Controllers\FrontController;
use halestar\LaravelDropInCms\Controllers\HeaderController;
use halestar\LaravelDropInCms\Controllers\JsScriptController;
use halestar\LaravelDropInCms\Controllers\PageController;
use halestar\LaravelDropInCms\Controllers\PreviewController;
use halestar\LaravelDropInCms\Controllers\SiteController;
use halestar\LaravelDropInCms\Controllers\WidgetController;
use halestar\LaravelDropInCms\Models\CssSheet;
use halestar\LaravelDropInCms\Models\DataItem;
use halestar\LaravelDropInCms\Models\Footer;
use halestar\LaravelDropInCms\Models\Header;
use halestar\LaravelDropInCms\Models\JsScript;
use halestar\LaravelDropInCms\Models\Page;
use halestar\LaravelDropInCms\Models\PageView;
use halestar\LaravelDropInCms\Models\Site;
use halestar\LaravelDropInCms\Widgets\PageViewsCounterWidget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

final class DiCMS
{
    public static function adminRoutes(): void
    {
        Route::name('dicms.admin.')
            ->group(function ()
        {


            Route::get('/', [SiteController::class, 'show'])->name('home');

            Route::post('/upload', function(Request $request)
            {
                $request->validate(['files.*' => 'required|file|mimes:' . implode(',', config('dicms.img_mimes_allowed', []))]);
                $imgs = $request->file('files');
                $urls = [];
                foreach($imgs as $img)
                {
                    $path = $img->store("", config('dicms.media_upload_disk'));
                    $urls[] = Storage::disk(config('dicms.media_upload_disk'))->url($path);
                }
                return response()->json($urls, 200);
            })->name('upload');

            Route::controller(BackupController::class)
                ->prefix('backup')
                ->name('backups.')
                ->group(function ()
                {
                    Route::get('/', 'index')->name('index');
                    Route::get('/export', 'export')->name('export');
                    Route::post('/', 'restore')->name('restore');
                });

            Route::post('/content/update', [SiteController::class, 'updateContent'])->name('content.update');

            //site functions
            Route::get('/sites/{site}/current', [SiteController::class, 'currentSite'])->name('sites.current');
            Route::put('/sites/{site}/update/settings', [SiteController::class, 'updateSettings'])->name('sites.update.settings');
            Route::get('/sites/{site}/enable', [SiteController::class, 'enableSite'])->name('sites.enable');
            Route::get('/sites/{site}/disable', [SiteController::class, 'disableSite'])->name('sites.disable');
            Route::get('/sites/{site}/archive', [SiteController::class, 'archiveSite'])->name('sites.archive');
            Route::get('/sites/{site}/restore', [SiteController::class, 'restoreSite'])->name('sites.restore');
            Route::get('/sites/{site}/metadata', [SiteController::class, 'editMetadata'])->name('sites.metadata');
            Route::get('/sites/{site}/duplicate', [SiteController::class, 'duplicateSite'])->name('sites.duplicate');

            //Site Resource Controller
            Route::resource('sites', SiteController::class);

            //Special case for pages
            Route::resource('pages', PageController::class)->except(['index', 'create', 'store']);
            Route::put('/pages/{page}/update/settings', [PageController::class, 'updateSettings'])->name('pages.update.settings');
            Route::get('/pages/{page}/publish', [PageController::class, 'publishPage'])->name('pages.publish');
            Route::get('/pages/{page}/unpublish', [PageController::class, 'unpublishPage'])->name('pages.unpublish');
            Route::get('/pages/{page}/duplicate', [PageController::class, 'duplicatePage'])->name('pages.dupe');
            Route::get('/pages/{page}/metadata', [PageController::class, 'editMetadata'])->name('pages.metadata');

            //Routes for Pages, Headers, Footers, Css Sheets and JS Scripts
            Route::prefix('sites/{site}')
                ->group(function ()
                {
                    //headers
                    Route::resource('headers', HeaderController::class)->except('show');
                    Route::get('/headers/{header}/duplicate', [HeaderController::class, 'duplicate'])->name('headers.duplicate');
                    Route::get('/headers/import', [HeaderController::class, 'import'])->name('headers.import.show');
                    Route::post('/headers/import', [HeaderController::class, 'doImport'])->name('headers.import');

                    //footers
                    Route::resource('footers', FooterController::class)->except('show');
                    Route::get('/footers/{footer}/duplicate', [FooterController::class, 'duplicate'])->name('footers.duplicate');
                    Route::get('/footers/import', [FooterController::class, 'import'])->name('footers.import.show');
                    Route::post('/footers/import', [FooterController::class, 'doImport'])->name('footers.import');

                    //css sheets
                    Route::resource('sheets', CssSheetController::class)->except('show');
                    Route::get('/sheets/{sheet}/duplicate', [CssSheetController::class, 'duplicate'])->name('sheets.duplicate');
                    Route::get('/sheets/import', [CssSheetController::class, 'import'])->name('sheets.import.show');
                    Route::post('/sheets/import', [CssSheetController::class, 'doImport'])->name('sheets.import');

                    //js scripts
                    Route::resource('scripts', JsScriptController::class)->except('show');
                    Route::get('/scripts/{script}/duplicate', [JsScriptController::class, 'duplicate'])->name('scripts.duplicate');
                    Route::get('/scripts/import', [JsScriptController::class, 'import'])->name('scripts.import.show');
                    Route::post('/scripts/import', [JsScriptController::class, 'doImport'])->name('scripts.import');

                    //pages
                    Route::resource('pages', PageController::class)->only(['index', 'create', 'store']);
                    Route::get('/pages/import', [PageController::class, 'import'])->name('pages.import.show');
                    Route::post('/pages/import', [PageController::class, 'doImport'])->name('pages.import');

                    //Preview Route
                    Route::any('/preview/{path?}', [PreviewController::class, 'index'])
                        ->where('path', '.*')
                        ->name('preview');
                });

            //Asset Controller
            Route::get('/assets', [DataItemController::class, 'index'])->name('assets.index');

            foreach(config('dicms.plugins') as $plugin)
            {
                $plugin::adminRoutes();
            }

            //widgets
            Route::controller(WidgetController::class)
                ->prefix('widgets')
                ->name('widgets.')
                ->group(function ()
                {
                    Route::get('/', 'index')->name('index');
                    Route::get('/{widget}', 'config')->name('config');
                });
        });
    }

    public static function apiRoutes(): void
    {
        Route::name('dicms.api.')
            ->group(function ()
            {
                Route::apiResources(
                    [
                        'headers' => HeaderApiController::class,
                        'footers' => FooterApiController::class,
                        'sheets' => CssSheetApiController::class,
                        'scripts' => JsScriptApiController::class,
                        'pages' => PageApiController::class,
                        'data-items' => DataItemApiController::class,
                        'sites' => SiteApiController::class,
                    ]);
                Route::get('/settings', [SettingApiController::class, 'get']);
                Route::post('/settings', [SettingApiController::class, 'set']);
                //page linking
                Route::post('/pages/{page}/link/css', [PageApiController::class, 'linkCss'])->name('pages.link.css');
                Route::post('/pages/{page}/link/js', [PageApiController::class, 'linkJs'])->name('pages.link.js');
                //site linking
                Route::post('/sites/{site}/link/css', [SiteApiController::class, 'linkCss'])->name('sites.link.css');
                Route::post('/sites/{site}/link/js', [SiteApiController::class, 'linkJs'])->name('sites.link.js');
                //settings
                Route::get('/settings', [SettingApiController::class, 'get'])->name('settings.get');
                Route::post('/settings', [SettingApiController::class, 'set'])->name('settings.set');
                foreach (config('dicms.plugins') as $plugin)
                {
                    $plugin::apiRoutes();
                }
            });
    }

    public static function publicRoutes(): void
    {
        Route::name('dicms.')->group(function ()
        {
            Route::prefix('dicms')
                ->group(function ()
                {
                    Route::get('/site/{site}/script.js', [FrontController::class, 'siteJs'])->name('front.js.site');
                    Route::get('/site/{site}/style.css', [FrontController::class, 'siteCss'])->name('front.css.site');
                    Route::get('/{page}/script.js', [FrontController::class, 'js'])->name('front.js');
                    Route::get('/{page}/style.css', [FrontController::class, 'css'])->name('front.css');
                });

            Route::any('/{path?}', [FrontController::class, 'index'])->where('path', '.*')->name('front');
        });
    }
    public static function routeNamePrefix(): string
    {
        if(Cache::has('dicms.route.prefix'))
            return Cache::get('dicms.route.prefix');

        $route = app('router')->getRoutes()->match(app('request')->create(action([SiteController::class, 'index'])));
        $name = $route->action['as'];
        $matches = [];
        preg_match("/^(.*)dicms\.(.*)$/", $name, $matches);
        $prefix =  $matches[1];
        Cache::put('dicms.route.prefix', $prefix);
        return $prefix;
    }

    public static function dicmsRoute(string $route_name, array $params = null): string
    {
        return route(static::routeNamePrefix() . "dicms." . $route_name, $params);
    }

    public static function dicmsPublicRoute(): string
    {
        return action([FrontController::class, 'index']);
    }

    public static function dicmsPublicJs($ref): string
    {
        if($ref instanceof Page)
            return action([FrontController::class, 'js'], ['page' => $ref->id]);
        elseif($ref instanceof Site)
            return action([FrontController::class, 'siteJs'], ['site' => $ref->id]);
        return "";
    }

    public static function dicmsPublicCss($ref): string
    {
        if($ref instanceof Page)
            return action([FrontController::class, 'css'], ['page' => $ref->id]);
        elseif($ref instanceof Site)
            return action([FrontController::class, 'siteCss'], ['site' => $ref->id]);
        return "";
    }

    public static function inAdminModule($module)
    {
        $name = Route::currentRouteName();
        $matches = [];
        return preg_match("/^(.*)dicms\.admin\." . $module . ".*$/", $name);
    }

    public static function assets(): array
    {
        $files =  Storage::disk(config('dicms.media_upload_disk'))->files();
        $urls = [];
        foreach ($files as $file)
            $urls[] = Storage::disk(config('dicms.media_upload_disk'))->url($file);
        return $urls;
    }

    public static function assetsJs()
    {
        $assets = [];
        $i = 1;
        foreach(static::assets() as $asset)
        {
            $assets[] = "{ src: '" . $asset . "', height: 350, width:250, name: 'img_" . $i . "'}";
            $i++;
        }
        return implode(", ", $assets);
    }

    public static function widgets(): array
    {
        $widgets = [PageViewsCounterWidget::class];
        foreach(config('dicms.plugins') as $plugin)
            $widgets = array_merge($widgets, $plugin::widgets());
        return $widgets;
    }

    public static function getCoreBackupTables(): array
    {
        return
        [
            config('dicms.settings_class'),
            Footer::class,
            Header::class,
            JsScript::class,
            CssSheet::class,
            Site::class,
            Page::class,
            DataItem::class,
            PageView::class,
        ];
    }
}
