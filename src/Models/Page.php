<?php

namespace halestar\LaravelDropInCms\Models;

use halestar\LaravelDropInCms\Classes\GrapesJsEditableItem;
use halestar\LaravelDropInCms\DiCMS;
use halestar\LaravelDropInCms\Interfaces\ContainsCssSheets;
use halestar\LaravelDropInCms\Interfaces\ContainsJsScripts;
use halestar\LaravelDropInCms\Interfaces\ContainsMetadata;
use halestar\LaravelDropInCms\Models\Scopes\OrderByNameScope;
use halestar\LaravelDropInCms\Traits\BackUpable;
use halestar\LaravelDropInCms\Traits\HasMetadata;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

#[ScopedBy([OrderByNameScope::class])]
class Page extends GrapesJsEditableItem implements ContainsCssSheets, ContainsJsScripts, ContainsMetadata
{
    use BackUpable, HasMetadata;

    protected static function getTablesToBackup(): array
    {
        return
            [
                config('dicms.table_prefix') . "pages",
                config('dicms.table_prefix') . "pages_css_sheets",
                config('dicms.table_prefix') . "pages_js_scripts",
            ];
    }

    protected $fillable = ['name','slug','title', 'path', 'html', 'css', 'data','override_css',
        'override_js','override_header', 'override_footer', 'header_id', 'published','metadata',
        'footer_id', 'url'];

    protected function casts(): array
    {
        return
            [
                'plugin_page' => 'boolean',
                'published' => 'boolean',
                'override_css' => 'boolean',
                'override_js' => 'boolean',
                'override_header' => 'boolean',
                'override_footer' => 'boolean',
                'metadata' => 'array',
                'created_at' => 'datetime:Y-m-d H:i:s',
                'updated_at' => 'datetime:Y-m-d H:i:s',
            ];
    }

    public function __construct(array $attributes = [])
    {
        $this->table = config('dicms.table_prefix') . "pages";
        parent::__construct($attributes);
    }

    public function scopePlugin(Builder $query): void
    {
        $query->where('plugin_page', true);
    }

    public function scopePublished(Builder $query): void
    {
        $query->where('published', true);
    }

    public function scopeNormal(Builder $query): void
    {
        $query->where('plugin_page', false);
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
        return $this->title?? ($this->currentSite->title?? "");
    }

    public function Header(): ?Header
    {
        return ($this->override_header || !$this->currentSite)? $this->defaultHeader: $this->currentSite->defaultHeader;
    }

    public function Footer(): ?Footer
    {
        return ($this->override_footer || !$this->currentSite)? $this->defaultFooter: $this->currentSite->defaultFooter;
    }

    public function CssLinks(): Collection
    {
        if($this->override_css || !$this->currentSite)
            $links = $this->pageCss()->links()->get()->pluck('href');
        else
        {
            $links = $this->currentSite->siteCss()->links()->get()->pluck('href');
            $links->merge($this->pageCss()->links()->get()->pluck('href'));
        }
        $links->push(DiCMS::dicmsPublicCss($this));
        return $links;
    }

    public function CssText(): string
    {
        if($this->override_css || !$this->currentSite)
            return $this->pageCss()->text()->get()->pluck('sheet')->join("\n");

        return $this->currentSite->siteCss()->text()->get()->pluck('sheet')->join("\n") . "\n" .
            $this->pageCss()->text()->get()->pluck('sheet')->join("\n");
    }

    public function JsLinks(): Collection
    {
        $links = new Collection();
        if($this->override_js || !$this->currentSite)
            $links = $this->pageJs()->links()->get()->pluck('href');
        else
        {
            $links = $this->currentSite->siteJs()->links()->get()->pluck('href');
            $links->merge($this->pageJs()->links()->get()->pluck('href'));
        }
        $links->push(DiCMS::dicmsPublicJs($this));
        return $links;
    }

    public function JsText(): string
    {
        if($this->override_js || !$this->currentSite)
            return $this->pageJs()->text()->get()->pluck('script')->join("\n");
        return $this->currentSite->siteJs()->text()->get()->pluck('script')->join("\n") . "\n" .
            $this->pageJs()->text()->get()->pluck('script')->join("\n");
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

    public function dupe(Site $site = null): Page
    {
        $dupe = new Page();
        $dupe->name = $this->name . "-" . __('dicms::admin.copy');
        $dupe->slug = $this->slug . "-" . __('dicms::admin.copy');
        $dupe->title = $this->title . "-" . __('dicms::admin.copy');
        $dupe->path = $this->path;
        $dupe->url = ($dupe->path? $dupe->path . "/": '' ) . $dupe->slug;
        $dupe->override_css = $this->override_css;
        $dupe->override_js = $this->override_js;
        $dupe->override_header = $this->override_header;
        $dupe->header_id = $this->header_id;
        $dupe->override_footer = $this->override_footer;
        $dupe->footer_id = $this->footer_id;
        $dupe->html = $this->html;
        $dupe->css = $this->css;
        $dupe->data = $this->data;
        $dupe->published = false;
        $dupe->metadata = $this->metadata;
        $dupe->save();
        foreach($this->pageCss as $pageCss)
            $dupe->pageCss()->attach($pageCss->id, ['order_by' => $pageCss->pivot->order_by]);

        foreach($this->pageJs as $pageJs)
            $dupe->pageJs()->attach($pageJs->id, ['order_by' => $pageJs->pivot->order_by]);

        return $dupe;
    }

    public function projectHtml(Page $page = null): string
    {
        if($this->plugin_page)
            return $this->plugin::projectHtml($this);
        return parent::projectHtml($this);
    }

    public function projectCss(): string
    {
        if($this->plugin_page)
            return $this->plugin::projectCss($this);
        return parent::projectCss();
    }
    public function getMetadata(): array
    {
        if($this->plugin_page)
            return $this->plugin::projectMetadata($this);
        if(count($this->metadata) == 0)
            return Site::currentSite()->getMetadata();
        return $this->metadata;
    }

    public function setMetadata(array $metadata)
    {
        $this->metadata = $metadata;
        $this->save();
    }
}
