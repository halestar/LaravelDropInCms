<?php

namespace halestar\LaravelDropInCms\Interfaces;

use halestar\LaravelDropInCms\Models\CssSheet;
use Illuminate\Support\Collection;

interface ContainsCssSheets
{
    public function getCssSheetPool(): Collection;
    public function getCssSheets(): Collection;
    public function addCssSheet(CssSheet $cssSheet);
    public function removeCssSheet(CssSheet $cssSheet);
    public function setCssSheetOrder(CssSheet $cssSheet, int $order);
}
