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
        $site = Site::defaultSite();
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
                $css = $plugin::getCssFiles($path);
                if(!$css)
                    $css = $site->siteCss;
                $js = $plugin::getJsFiles($path);
                if(!$js)
                    $js = $site->siteJs;
                $header = $plugin::getHeader($path);
                if(!$header)
                    $header = $site->defaultHeader;
                $footer = $plugin::getFooter($path);
                if(!$footer)
                    $footer = $site->defaultFooter;
                $plugin =
                    [
                        'content' => $plugin::getPublicContent($path),
                        'css' => $css,
                        'js' => $js,
                        'header' => $header,
                        'footer' => $footer,
                    ];

                return view('dicms::layouts.front-plugin', compact('site', 'plugin'));
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
