<?php

namespace halestar\LaravelDropInCms\Classes;

use halestar\LaravelDropInCms\DiCMS;
use halestar\LaravelDropInCms\Models\Page;
use halestar\LaravelDropInCms\Models\Site;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;

abstract class GrapesJsEditableItem extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    public function CssLinks(): Collection
    {
        $links = $this->site->siteCss()->links()->get()->pluck('href');
        $links->push(DiCMS::dicmsPublicCss($this->site));
        return $links;
    }

    public function JsLinks(): Collection
    {
        $links = $this->site->siteJs()->links()->get()->pluck('href');
        $links->push(DiCMS::dicmsPublicJs($this->site));
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
