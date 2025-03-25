<?php

namespace halestar\LaravelDropInCms\Controllers;

use Barryvdh\Debugbar\Facades\Debugbar;
use halestar\LaravelDropInCms\Models\Page;
use halestar\LaravelDropInCms\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;

class PreviewController
{
    public function index(Request $request, Site $site,  string $path = null)
    {

        Debugbar::disable();
        Gate::authorize('preview', $site);
        if($request->input('wrapper', 'false') == 'false')
            return view('dicms::layouts.preview.preview', compact('site'));

        //next, we get the page
        if($path == null)
            $path = $site->homepage_url;
        $page = $site->pages()->where('url', $path)->first();
        if($page)
            return view('dicms::layouts.front', compact('site', 'page'));
        //else, we check plugins.
        foreach(config('dicms.plugins', []) as $plugin)
        {
            if($page = $plugin::hasPublicRoute($path))
            {
                return view('dicms::layouts.front', compact('site', 'page'));
            }
        }

        return view('dicms::layouts.preview.site', compact('site'));
    }

    public function js($page)
    {
        $page = Page::findOrFail($page);
        $rsp = Response::make($page->JsText());
        $rsp->header('Content-Type', 'text/javascript');
        return $rsp;
    }

    public function pluginJs(Request $request)
    {
        $plugin = $request->input('plugin', null);
        if(!$plugin)
            return abort(404);
        $path = $request->input('path', null);
        if(!$path)
            return abort(404);
        $text = $plugin::getJsFiles($path)->where('type', '=', \halestar\LaravelDropInCms\Enums\HeadElementType::Text)->pluck('script')->join("\n");
        $rsp = Response::make($text);
        $rsp->header('Content-Type', 'text/javascript');
        return $rsp;
    }

    public function siteJs()
    {
        $site = Site::currentSite();
        Gate::authorize('preview', $site);
        $rsp = Response::make($site->JsText());
        $rsp->header('Content-Type', 'text/javascript');
        return $rsp;
    }

    public function css($page)
    {
        $page = Page::findOrFail($page);
        $rsp = Response::make($page->CssText());
        $rsp->header('Content-Type', 'text/css');
        return $rsp;
    }

    public function pluginCss(Request $request)
    {
        $plugin = $request->input('plugin', null);
        if(!$plugin)
            return abort(404);
        $path = $request->input('path', null);
        if(!$path)
            return abort(404);
        $text = $plugin::getCssFiles($path)->where('type', '=', \halestar\LaravelDropInCms\Enums\HeadElementType::Text)->pluck('sheet')->join("\n");
        $rsp = Response::make($text);
        $rsp->header('Content-Type', 'text/css');
        return $rsp;
    }

    public function siteCss()
    {
        $site = Site::currentSite();
        Gate::authorize('preview', $site);
        $rsp = Response::make($site->CssText());
        $rsp->header('Content-Type', 'text/css');
        return $rsp;
    }
}
