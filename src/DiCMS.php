<?php

namespace halestar\LaravelDropInCms;
use halestar\LaravelDropInCms\Controllers\BackupController;
use halestar\LaravelDropInCms\Controllers\CssSheetController;
use halestar\LaravelDropInCms\Controllers\DataItemController;
use halestar\LaravelDropInCms\Controllers\FooterController;
use halestar\LaravelDropInCms\Controllers\FrontController;
use halestar\LaravelDropInCms\Controllers\HeaderController;
use halestar\LaravelDropInCms\Controllers\JsScriptController;
use halestar\LaravelDropInCms\Controllers\MenuController;
use halestar\LaravelDropInCms\Controllers\PageController;
use halestar\LaravelDropInCms\Controllers\PreviewController;
use halestar\LaravelDropInCms\Controllers\SiteController;
use halestar\LaravelDropInCms\Middleware\CurrentSiteExistsMiddleware;
use halestar\LaravelDropInCms\Models\CssSheet;
use halestar\LaravelDropInCms\Models\DataItem;
use halestar\LaravelDropInCms\Models\Footer;
use halestar\LaravelDropInCms\Models\Header;
use halestar\LaravelDropInCms\Models\JsScript;
use halestar\LaravelDropInCms\Models\Menu;
use halestar\LaravelDropInCms\Models\Page;
use halestar\LaravelDropInCms\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

final class DiCMS
{
    public static function adminRoutes(): void
    {
        Route::name('dicms.admin.')
            ->middleware(CurrentSiteExistsMiddleware::class)
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


            Route::get('/sites/{site}/current', [SiteController::class, 'currentSite'])->name('sites.current');
            Route::put('/sites/{site}/update/settings', [SiteController::class, 'updateSettings'])->name('sites.update.settings');
            Route::get('/sites/{site}/enable', [SiteController::class, 'enableSite'])->name('sites.enable');
            Route::get('/sites/{site}/disable', [SiteController::class, 'disableSite'])->name('sites.disable');
            Route::get('/sites/{site}/archive', [SiteController::class, 'archiveSite'])->name('sites.archive');
            Route::get('/sites/{site}/restore', [SiteController::class, 'restoreSite'])->name('sites.restore');
            Route::get('/sites/{site}/duplicate', [SiteController::class, 'duplicateSite'])->name('sites.duplicate');

            Route::put('/headers/{header}/content', [HeaderController::class, 'updateContent'])->name('headers.update.content');
            Route::get('/headers/{header}/duplicate', [HeaderController::class, 'duplicate'])->name('headers.duplicate');
            Route::put('/footers/{footer}/content', [FooterController::class, 'updateContent'])->name('footers.update.content');
            Route::get('/footers/{footer}/duplicate', [FooterController::class, 'duplicate'])->name('footers.duplicate');
            Route::put('/menu/{menu}/content', [MenuController::class, 'updateContent'])->name('menus.update.content');
            Route::get('/menu/{menu}/duplicate', [MenuController::class, 'duplicate'])->name('menus.duplicate');

            Route::get('/sheets/{sheet}/duplicate', [CssSheetController::class, 'duplicate'])->name('sheets.duplicate');
            Route::get('/scripts/{script}/duplicate', [JsScriptController::class, 'duplicate'])->name('scripts.duplicate');

            Route::put('/pages/{page}/update/settings', [PageController::class, 'updateSettings'])->name('pages.update.settings');
            Route::put('/pages/{page}/update/contents', [PageController::class, 'updateContent'])->name('pages.update.content');

            Route::get('/pages/{page}/publish', [PageController::class, 'publishPage'])->name('pages.publish');
            Route::get('/pages/{page}/unpublish', [PageController::class, 'unpublishPage'])->name('pages.unpublish');
            Route::get('/pages/{page}/duplicate', [PageController::class, 'duplicatePage'])->name('pages.dupe');

            Route::resource('sites', SiteController::class);
            Route::resource('headers', HeaderController::class)->except('show');
            Route::resource('footers', FooterController::class)->except('show');
            Route::resource('menus', MenuController::class)->except('show');
            Route::resource('sheets', CssSheetController::class)->except('show');
            Route::resource('scripts', JsScriptController::class)->except('show');
            Route::resource('pages', PageController::class);


            Route::get('/assets', [DataItemController::class, 'index'])->name('assets.index');

            Route::prefix('preview')
                ->name('preview.')
                ->controller(PreviewController::class)
                ->group(function ()
                {
                    Route::get('/script.js', 'siteJs')->name('js.site');
                    Route::get('/style.css', 'siteCss')->name('css.site');
                    Route::get('/plugin/script.js', 'pluginJs')->name('js.plugin');
                    Route::get('/plugin/style.css', 'pluginCss')->name('css.plugin');
                    Route::get('/{page}/script.js', 'js')->name('js');
                    Route::get('/{page}/style.css', 'css')->name('css');
                    Route::any('/{path?}', 'index')->where('path', '.*')->name('home');
                });

            foreach(config('dicms.plugins') as $plugin)
            {
                $plugin::adminRoutes();
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
                    Route::get('/plugin/script.js', [FrontController::class, 'pluginJs'])->name('front.js.plugin');
                    Route::get('/plugin/style.css', [FrontController::class, 'pluginCss'])->name('front.css.plugin');
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
        ];
    }
}
