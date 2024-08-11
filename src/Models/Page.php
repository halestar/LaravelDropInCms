<?php

namespace halestar\LaravelDropInCms\Models;

use halestar\LaravelDropInCms\DiCMS;
use halestar\LaravelDropInCms\Interfaces\ContainsCssSheets;
use halestar\LaravelDropInCms\Interfaces\ContainsJsScripts;
use halestar\LaravelDropInCms\Traits\BackUpable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class Page extends Model implements ContainsCssSheets, ContainsJsScripts
{
    use BackUpable;

    protected static function getTablesToBackup(): array
    {
        return
            [
                config('dicms.table_prefix') . "pages",
                config('dicms.table_prefix') . "pages_css_sheets",
                config('dicms.table_prefix') . "pages_js_scripts",
            ];
    }

    protected $fillable = ['name','slug','title', 'path', 'html', 'url', 'css', 'data'];

    protected function casts(): array
    {
        return
            [
                'published' => 'boolean',
            ];
    }

    public function __construct(array $attributes = [])
    {
        $this->table = config('dicms.table_prefix') . "pages";
        parent::__construct($attributes);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    public function defaultHeader(): BelongsTo
    {
        return $this->belongsTo(Header::class, "header_id");
    }

    public function defaultFooter(): BelongsTo
    {
        return $this->belongsTo(Footer::class, 'footer_id');
    }

    public function pageCss(): BelongsToMany
    {
        return $this->belongsToMany(CssSheet::class, config('dicms.table_prefix') . 'pages_css_sheets',
            'page_id', 'sheet_id')->withPivot('order_by')->orderByPivot('order_by');
    }

    public function pageJs(): BelongsToMany
    {
        return $this->belongsToMany(JsScript::class, config('dicms.table_prefix') . 'pages_js_scripts',
            'page_id', 'script_id')->withPivot('order_by')->orderByPivot('order_by');
    }

    //front content
    public function Title(): string
    {
        return $this->title?? $this->site->title;
    }

    public function Header(): BelongsTo
    {
        return $this->defaultHeader()->exists()? $this->defaultHeader(): $this->site->defaultHeader();
    }

    public function Footer(): BelongsTo
    {
        return $this->defaultFooter()->exists()? $this->defaultFooter(): $this->site->defaultFooter();
    }

    public function Css(): BelongsToMany
    {
        Log::debug("this->site: " . print_r($this->site, true));
        return ($this->pageCss()->count() > 0)? $this->pageCss(): $this->site->siteCss();
    }

    public function Js(): BelongsToMany
    {
        return ($this->pageJs()->count() > 0)? $this->pageJs(): $this->site->siteJs();
    }

    public static function url(int $id): string
    {
        $page = Page::findOrFail($id);
        return DiCMS::dicmsPublicRoute() . "/" . $page->url;
    }

    public function getCssSheets(): Collection
    {
        return $this->pageCss;
    }

    public function addCssSheet(CssSheet $cssSheet)
    {
        $order = $this->pageCss()->count();
        $this->pageCss()->attach($cssSheet->id, ['order_by' => $order]);
    }

    public function removeCssSheet(CssSheet $cssSheet)
    {
        $this->pageCss()->detach($cssSheet->id);
    }

    public function setCssSheetOrder(CssSheet $cssSheet, int $order)
    {
        $this->pageCss()->updateExistingPivot($cssSheet->id, ['order_by' => $order]);
    }

    public function getJsScripts(): Collection
    {
        return $this->pageJs;
    }

    public function addJsScript(JsScript $script)
    {
        $order = $this->pageJs()->count();
        $this->pageJs()->attach($script->id, ['order_by' => $order]);
    }

    public function removeJsScript(JsScript $script)
    {
        $this->pageJs()->detach($script->id);
    }

    public function setJsScriptOrder(JsScript $script, int $order)
    {
        $this->pageJs()->updateExistingPivot($script->id, ['order_by' => $order]);
    }
}
