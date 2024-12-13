<?php

namespace halestar\LaravelDropInCms\Livewire;

use halestar\LaravelDropInCms\Models\Page;
use halestar\LaravelDropInCms\Models\PageView;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class PageViewsCounterConfig extends Component
{
    use WithPagination;
    public Collection $pages;
    public ?Page $selectedPage = null;

    public function mount()
    {
        $this->pages = PageView::pages();
    }

    public function viewPage(Page $page)
    {
        $this->selectedPage = $page;
    }
    public function render()
    {
        if($this->selectedPage)
            $viewers =  PageView::where('page_id', $this->selectedPage->id)->paginate();
        else
            $viewers = new Collection();
        return view('dicms::livewire.page-views-counter-config', ['viewers' => $viewers]);
    }
}
