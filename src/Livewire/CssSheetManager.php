<?php

namespace halestar\LaravelDropInCms\Livewire;

use halestar\LaravelDropInCms\Interfaces\ContainsCssSheets;
use halestar\LaravelDropInCms\Models\CssSheet;
use Illuminate\Support\Collection;
use Livewire\Component;

class CssSheetManager extends Component
{
    public ContainsCssSheets $container;
    public Collection $cssSheets;

    public string $title;
    public ?string $manageLink = null;

    public function mount(ContainsCssSheets $container, string $title = null, string $manageLink = null)
    {
        $this->container = $container;
        $this->cssSheets = $container->getCssSheets();
        $this->title = $title?? __('dicms::sites.sheets');
        $this->manageLink = $manageLink;
    }

    public function updateOrder($sheets)
    {
        foreach ($sheets as $sheet)
        {
            $cssSheet = CssSheet::find($sheet['value']);
            if($cssSheet)
                $this->container->setCssSheetOrder($cssSheet, $sheet['order']);
        }
        $this->cssSheets = $this->container->getCssSheets();
    }

    public function addCssSheet($cssSheetId)
    {
        $cssSheet = CssSheet::find($cssSheetId);
        if($cssSheet)
            $this->container->addCssSheet($cssSheet);
        $this->cssSheets = $this->container->getCssSheets();
    }

    public function removeCssSheet($cssSheetId)
    {
        $cssSheet = CssSheet::find($cssSheetId);
        if($cssSheet)
            $this->container->removeCssSheet($cssSheet);
        $this->cssSheets = $this->container->getCssSheets();
    }

    public function render()
    {
        return view('dicms::livewire.css-sheet-manager');
    }
}
