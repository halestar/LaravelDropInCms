<?php

namespace halestar\LaravelDropInCms\View\Components;

use Closure;
use halestar\LaravelDropInCms\Classes\GrapesJsEditableItem;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class WebEditor extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct
        (
            public GrapesJsEditableItem $editableObj,
            public string $title,
            public string $help,

        ){}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('dicms::components.web-editor');
    }
}
