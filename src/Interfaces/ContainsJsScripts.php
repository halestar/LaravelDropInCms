<?php

namespace halestar\LaravelDropInCms\Interfaces;

use halestar\LaravelDropInCms\Models\JsScript;
use Illuminate\Support\Collection;

interface ContainsJsScripts
{
    public function getJsScriptPool(): Collection;
    public function getJsScripts(): Collection;
    public function addJsScript(JsScript $script);
    public function removeJsScript(JsScript $script);
    public function setJsScriptOrder(JsScript $script, int $order);
}
