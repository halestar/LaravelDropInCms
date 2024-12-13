<?php

namespace halestar\LaravelDropInCms\Classes;

use halestar\LaravelDropInCms\DiCMS;
use halestar\LaravelDropInCms\Models\Page;
use halestar\LaravelDropInCms\Models\Site;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;

abstract class GrapesJsEditableItem extends Model
{
    protected ?Site $currentSite;
    public function __construct(array $attributes = [])
    {
        $this->currentSite = Site::currentSite();
        parent::__construct($attributes);
    }

    public function CssLinks(): Collection
    {
        if($this->currentSite)
        {
            $links = $this->currentSite->siteCss()->links()->get()->pluck('href');
            $links->push(DiCMS::dicmsPublicCss($this->currentSite));
        }
        else
            $links = new Collection();
        return $links;
    }

    public function JsLinks(): Collection
    {
        if($this->currentSite)
        {
            $links = $this->currentSite->siteJs()->links()->get()->pluck('href');
            $links->push(DiCMS::dicmsPublicJs($this->currentSite));
        }
        else
            $links = new Collection();
        return $links;
    }

    public function projectData(): string
    {
        return $this->data?? "";
    }

    public function projectHtml(Page $page): string
    {
        return Blade::render($this->html, ['page' => $page]);
    }

    public function projectCss(): string
    {
        return $this->css?? "";
    }

    public function setProjectData($data): void
    {
        $this->data = $data;
    }

    public function setProjectHtml($html): void
    {
        $this->html = $html;
    }

    public function setProjectCss($css): void
    {
        $this->css = $css;
    }
}
