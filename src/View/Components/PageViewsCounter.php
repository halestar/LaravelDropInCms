<?php

namespace halestar\LaravelDropInCms\View\Components;

use Closure;
use halestar\LaravelDropInCms\Models\Page;
use halestar\LaravelDropInCms\Models\PageView;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PageViewsCounter extends Component
{
    public int $views = 0;
    /**
     * Create a new component instance.
     */
    public function __construct
        (
            public Page $page
        )
    {
        $ip = request()->ip();
        $pageView = PageView::where('ip_address', $ip)->where('page_id', $this->page->id)->first();
        if(!$pageView)
        {
            $pageView = new PageView();
            $pageView->ip_address = $ip;
            $pageView->page_id = $this->page->id;
            $pageView->views = 1;
            $pageView->save();
        }
        else
        {
            $pageView->views = $pageView->views + 1;
            $pageView->save();
        }
        $this->views = PageView::where('page_id', $this->page->id)->count();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('dicms::components.page-views-counter');
    }
}
