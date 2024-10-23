<?php

namespace halestar\LaravelDropInCms\Middleware;

use Closure;
use halestar\LaravelDropInCms\DiCMS;
use halestar\LaravelDropInCms\Models\Site;
use Illuminate\Http\Request;

class CurrentSiteExistsMiddleware
{
	public function handle(Request $request, Closure $next)
	{
        if(!Site::currentSite() && $request->url() != DiCMS::dicmsRoute('admin.sites.create') &&
            $request->url() != DiCMS::dicmsRoute('admin.sites.store') &&
            $request->url() != DiCMS::dicmsRoute('admin.backups.index') &&
            $request->url() != DiCMS::dicmsRoute('admin.backups.restore'))

                return redirect(DiCMS::dicmsRoute('admin.sites.create'));
		return $next($request);
	}
}
