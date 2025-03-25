<?php

namespace halestar\LaravelDropInCms\Controllers;

use halestar\LaravelDropInCms\Models\Page;
use halestar\LaravelDropInCms\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class FrontController
{
    public function index(Request $request, string $path = null)
    {
        //get the active site
        $site = Site::activeSite();
        if(!$site)
            return view('dicms::layouts.nosite');
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
        abort(404);
    }

    public function js($page)
    {
        $page = Page::findOrFail($page);
        $rsp = Response::make($page->JsText());
        $rsp->header('Content-Type', 'text/javascript');
        return $rsp;
    }

    public function siteJs($site)
    {
        $site = Site::findOrFail($site);
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

    public function siteCss($site)
    {
        $site = Site::findOrFail($site);
        $rsp = Response::make($site->CssText());
        $rsp->header('Content-Type', 'text/css');
        return $rsp;
    }

}
