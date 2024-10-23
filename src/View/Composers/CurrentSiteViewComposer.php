<?php

namespace halestar\LaravelDropInCms\View\Composers;

use halestar\LaravelDropInCms\Models\Site;
use Illuminate\View\View;

class CurrentSiteViewComposer
{
	public function compose(View $view)
	{
        $view->with('allSites', Site::all());
        $view->with('currentSite', Site::currentSite());
	}
}
