<?php

namespace halestar\LaravelDropInCms\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TagSelector extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct
    (
        public string $name,
        public array $tags,
        public ?string $selectedTag,
        public ?string $options,
    ){}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('dicms::components.tag-selector');
    }
}
