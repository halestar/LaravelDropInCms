<?php

namespace halestar\LaravelDropInCms;
use halestar\LaravelDropInCms\Controllers\BackupController;
use halestar\LaravelDropInCms\Controllers\CssSheetController;
use halestar\LaravelDropInCms\Controllers\FooterController;
use halestar\LaravelDropInCms\Controllers\FrontController;
use halestar\LaravelDropInCms\Controllers\HeaderController;
use halestar\LaravelDropInCms\Controllers\JsScriptController;
use halestar\LaravelDropInCms\Controllers\MenuController;
use halestar\LaravelDropInCms\Controllers\PageController;
use halestar\LaravelDropInCms\Controllers\SiteController;
use halestar\LaravelDropInCms\Models\CssSheet;
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
        Route::name('dicms.admin.')->group(function ()
        {
            Route::get('/', [SiteController::class, 'index'])->name('home');

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


            Route::put('/sites/{site}/update/settings', [SiteController::class, 'updateSettings'])->name('sites.update.settings');
            Route::get('/sites/{site}/enable', [SiteController::class, 'enableSite'])->name('sites.enable');
            Route::get('/sites/{site}/disable', [SiteController::class, 'disableSite'])->name('sites.disable');
            Route::get('/sites/{site}/archive', [SiteController::class, 'archiveSite'])->name('sites.archive');
            Route::get('/sites/{site}/restore', [SiteController::class, 'restoreSite'])->name('sites.restore');
            Route::post('/sites/{site}/css/add', [SiteController::class, 'addCss'])->name('sites.css.add');
            Route::get('/sites/{site}/css/{cssSheet}/remove', [SiteController::class, 'removeCss'])->name('sites.css.remove');
            Route::post('/sites/{site}/js/add', [SiteController::class, 'addJs'])->name('sites.js.add');
            Route::get('/sites/{site}/js/{jsScript}/remove', [SiteController::class, 'removeJs'])->name('sites.js.remove');

            Route::put('/sites/headers/{header}/content', [HeaderController::class, 'updateContent'])->name('sites.headers.update.content');
            Route::put('/sites/footers/{footer}/content', [FooterController::class, 'updateContent'])->name('sites.footers.update.content');
            Route::put('/sites/menu/{menu}/content', [MenuController::class, 'updateContent'])->name('sites.menus.update.content');

            Route::get('/pages/{page}/edit/settings', [PageController::class, 'editSettings'])->name('sites.pages.edit.settings');
            Route::put('/pages/{page}/update/settings', [PageController::class, 'updateSettings'])->name('sites.pages.update.settings');
            Route::put('/pages/{page}/update/contents', [PageController::class, 'updateContent'])->name('sites.pages.update.content');

            Route::post('/pages/{page}/css/add', [PageController::class, 'addCss'])->name('sites.pages.css.add');
            Route::get('/pages/{page}/css/{cssSheet}/remove', [PageController::class, 'removeCss'])->name('sites.pages.css.remove');
            Route::post('/pages/{page}/js/add', [PageController::class, 'addJs'])->name('sites.pages.js.add');
            Route::get('/pages/{page}/js/{jsScript}/remove', [PageController::class, 'removeJs'])->name('sites.pages.js.remove');

            Route::get('/pages/{page}/publish', [PageController::class, 'publishPage'])->name('sites.pages.publish');
            Route::get('/pages/{page}/unpublish', [PageController::class, 'unpublishPage'])->name('sites.pages.unpublish');

            Route::resource('sites', SiteController::class);
            Route::resource('sites.headers', HeaderController::class)->except('show');
            Route::resource('sites.footers', FooterController::class)->except('show');
            Route::resource('sites.sheets', CssSheetController::class)->except('show');
            Route::resource('sites.scripts', JsScriptController::class)->except('show');
            Route::resource('sites.pages', PageController::class);
            Route::resource('sites.menus', MenuController::class);

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

            Route::get('/site/{site}/script.js', [FrontController::class, 'siteJs'])->name('front.js.site');
            Route::get('/site/{site}/style.css', [FrontController::class, 'siteCss'])->name('front.css.site');
            Route::get('/{page}/script.js', [FrontController::class, 'js'])->name('front.js');
            Route::get('/{page}/style.css', [FrontController::class, 'css'])->name('front.css');

            Route::get('/cms/posts/show/{post}', [\App\Http\Controllers\CMS\BlogPostController::class, 'show'])->name('blog.show');

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
            Menu::class,
            JsScript::class,
            CssSheet::class,
            Site::class,
            Page::class,
        ];
    }
}
