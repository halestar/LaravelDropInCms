<?php

namespace halestar\LaravelDropInCms\Controllers;

use halestar\LaravelDropInCms\Models\Page;
use halestar\LaravelDropInCms\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class FrontController
{
    public function index(Request $request, string $path = null)
    {
        //get the active site
        $site = Site::where('active', true)->first();
        if(!$site)
            abort(404);
        //ext, we get the page
        if($path == null)
            $path = $site->homepage_url;
        $page = $site->pages()->where('url', $path)->first();
        if($page)
            return view('dicms::layouts.front', compact('site', 'page'));

        //else, we check plugins.
        foreach(config('dicms.plugins', []) as $plugin)
        {
            if($plugin::hasPublicRoute($path))
            {
                $plugin_content = $plugin::getPublicContent($path);
                return view('dicms::layouts.front-plugin', compact('site', 'plugin_content'));
            }
        }
        abort(404);
    }

    public function js($page)
    {
        $page = Page::findOrFail($page);
        $rsp = Response::make($page->Js()->text()->get()->pluck('script')->join("\n"));
        $rsp->header('Content-Type', 'text/javascript');
        return $rsp;
    }

    public function siteJs($site)
    {
        $site = Site::findOrFail($site);
        $rsp = Response::make($site->siteJs()->text()->get()->pluck('script')->join("\n"));
        $rsp->header('Content-Type', 'text/javascript');
        return $rsp;
    }

    public function css($page)
    {
        $page = Page::findOrFail($page);
        $rsp = Response::make($page->Css()->text()->get()->pluck('sheet')->join("\n"));
        $rsp->header('Content-Type', 'text/css');
        return $rsp;
    }

    public function siteCss($site)
    {
        $site = Site::findOrFail($site);
        $rsp = Response::make($site->siteCss()->text()->get()->pluck('sheet')->join("\n"));
        $rsp->header('Content-Type', 'text/css');
        return $rsp;
    }
}
