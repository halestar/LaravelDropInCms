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

    /**
     * SCOPES
     */

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

    /**
     * Relationships
     */

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
        //if we override the title, return that.
        if($this->title != null)
            return $this->title;
        //is this a plugin page?
        if($this->plugin_page)
        {
            $activeSite = Site::activeSite();
            if($activeSite)
                return $activeSite->title?? "";
            return "";
        }
        //easy case, we return the site title.
        return $this->site->title?? "";
    }

    public function Header(): ?Header
    {
        if($this->override_header)
            return $this->defaultHeader;
        if($this->plugin_page)
        {
            $activeSite = Site::activeSite();
            if($activeSite)
                return $activeSite->defaultHeader;
            return null;
        }
        return $this->site->defaultHeader;
    }

    public function Footer(): ?Footer
    {
        if($this->override_footer)
            return $this->defaultFooter;
        if($this->plugin_page)
        {
            $activeSite = Site::activeSite();
            if($activeSite)
                return $activeSite->defaultFooter;
            return null;
        }
        return $this->site->defaultFooter;
    }

    public function Css(): Collection
    {
        if($this->override_css)
            return $this->pageCss()->links()->get();
        if($this->plugin_page)
        {
            $activeSite = Site::activeSite();
            if($activeSite)
                return $activeSite->siteCss()->links()->get()->merge($this->pageCss()->links()->get());
            else
                return $this->pageCss()->links()->get();
        }
        return $this->site->siteCss()->links()->get()->merge($this->pageCss()->links()->get());
    }

    public function CssLinks(): Collection
    {
        if($this->override_css)
            $links = $this->pageCss()->links()->get()->pluck('href');
        else
        {
            //is this a pluging page?
            if($this->plugin_page)
            {
                //try to get the active site
                $activeSite = Site::activeSite();
                if($activeSite) //easy case, we use the active site css
                    $links = $activeSite->siteCss()->links()->get()->pluck('href');
                else //no links.
                    $links = new Collection();
            }
            else //In this case, we use the page's site links.
                $links = $this->site->siteCss()->links()->get()->pluck('href');
            $links->merge($this->pageCss()->links()->get()->pluck('href'));
        }
        $links->push(DiCMS::dicmsPublicCss($this));
        return $links;
    }

    public function CssText(): string
    {
        if($this->override_css)
            return $this->pageCss()->text()->get()->pluck('sheet')->join("\n");

        if($this->plugin_page)
        {
            $activeSite = Site::activeSite();
            if($activeSite)
                return $activeSite->siteCss()->text()->get()->pluck('sheet')->join("\n") . "\n" .
                    $this->pageCss()->text()->get()->pluck('sheet')->join("\n");
            else
                return $this->pageCss()->text()->get()->pluck('sheet')->join("\n");
        }
        return $this->site->siteCss()->text()->get()->pluck('sheet')->join("\n") . "\n" .
            $this->pageCss()->text()->get()->pluck('sheet')->join("\n");
    }

    public function Js(): Collection
    {
        if($this->override_js)
            return $this->pageJs()->links()->get();
        if($this->plugin_page)
        {
            $activeSite = Site::activeSite();
            if($activeSite)
                return $activeSite->siteJs()->links()->get()->merge($this->pageJs()->links()->get());
            else
                return $this->pageJs()->links()->get();
        }
        return $this->site->siteJs()->links()->get()->merge($this->pageJs()->links()->get());
    }

    public function JsLinks(): Collection
    {
        if($this->override_js)
            $links = $this->pageJs()->links()->get()->pluck('href');
        else
        {
            //is this a pluging page?
            if($this->plugin_page)
            {
                //try to get the active site
                $activeSite = Site::activeSite();
                if($activeSite) //easy case, we use the active site css
                    $links = $activeSite->siteJs()->links()->get()->pluck('href');
                else //no links.
                    $links = new Collection();
            }
            else //In this case, we use the page's site links.
                $links = $this->site->siteJs()->links()->get()->pluck('href');
            $links->merge($this->pageJs()->links()->get()->pluck('href'));
        }
        $links->push(DiCMS::dicmsPublicJs($this));
        return $links;
    }

    public function JsText(): string
    {
        if($this->override_js)
            return $this->pageJs()->text()->get()->pluck('script')->join("\n");

        if($this->plugin_page)
        {
            $activeSite = Site::activeSite();
            if($activeSite)
                return $activeSite->siteJs()->text()->get()->pluck('script')->join("\n") . "\n" .
                    $this->pageJs()->text()->get()->pluck('script')->join("\n");
            else
                return $this->pageJs()->text()->get()->pluck('script')->join("\n");
        }
        return $this->site->siteJs()->text()->get()->pluck('script')->join("\n") . "\n" .
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
        $dupe->site_id = $site? $site->id: $this->site_id;
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
            return $this->site->getMetadata();
        return $this->metadata;
    }

    public function setMetadata(array $metadata)
    {
        $this->metadata = $metadata;
        $this->save();
    }

    public function getCssSheetPool(): Collection
    {
        //is this a plugin page?
        if($this->plugin_page)
        {
            // is there an active site?
            $site = Site::activeSite();
            if($site)
                return $site->siteCss;
            //else, we can return nothing.
            return new Collection();
        }
        //if now, we return the site css
        return $this->site->siteCss;
    }

    public function getJsScriptPool(): Collection
    {
        //is this a plugin page?
        if($this->plugin_page)
        {
            // is there an active site?
            $site = Site::activeSite();
            if($site)
                return $site->siteJs;
            //else, we can return nothing.
            return new Collection();
        }
        //if now, we return the site css
        return $this->site->siteJs;
    }
}
