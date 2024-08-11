<?php

namespace halestar\LaravelDropInCms\Controllers;

use halestar\LaravelDropInCms\Models\DataItem;
use halestar\LaravelDropInCms\Models\Site;
use Illuminate\Support\Facades\Gate;

class DataItemController
{
    public function index(Site $site)
    {
        Gate::authorize('viewAny', DataItem::class);
        return view('dicms::data_items.index');
    }
}
