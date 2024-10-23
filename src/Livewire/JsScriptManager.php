<?php

namespace halestar\LaravelDropInCms\Livewire;

use halestar\LaravelDropInCms\Interfaces\ContainsJsScripts;
use halestar\LaravelDropInCms\Models\JsScript;
use Illuminate\Support\Collection;
use Livewire\Component;

class JsScriptManager extends Component
{
    public ContainsJsScripts $container;
    public Collection $jsScripts;
    public int $siteId;
    public string $title;

    public function mount(ContainsJsScripts $container, int $siteId, string $title = null)
    {
        $this->container = $container;
        $this->jsScripts = $container->getJsScripts();
        $this->siteId = $siteId;
        $this->title = $title?? __('dicms::sites.scripts');
    }

    public function updateOrder($scripts)
    {
        foreach ($scripts as $script)
        {
            $jsScript = JsScript::find($script['value']);
            if($jsScript)
                $this->container->setJsScriptOrder($jsScript, $script['order']);
        }
        $this->jsScripts = $this->container->getJsScripts();
    }

    public function addJsScript($jsScriptId)
    {
        $jsScript = JsScript::find($jsScriptId);
        if($jsScript)
            $this->container->addJsScript($jsScript);
        $this->jsScripts = $this->container->getJsScripts();
    }

    public function removeJsScript($jsScriptId)
    {
        $jsScript = JsScript::find($jsScriptId);
        if($jsScript)
            $this->container->removeJsScript($jsScript);
        $this->jsScripts = $this->container->getJsScripts();
    }

    public function render()
    {
        return view('dicms::livewire.js-script-manager');
    }
}
