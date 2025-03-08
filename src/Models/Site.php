<?php

namespace halestar\LaravelDropInCms\Models;

use halestar\LaravelDropInCms\DiCMS;
use halestar\LaravelDropInCms\Enums\WrapperTagType;
use halestar\LaravelDropInCms\Interfaces\ContainsCssSheets;
use halestar\LaravelDropInCms\Interfaces\ContainsJsScripts;
use halestar\LaravelDropInCms\Interfaces\ContainsMetadata;
use halestar\LaravelDropInCms\Models\Scopes\AvailableOnlyScope;
use halestar\LaravelDropInCms\Models\Scopes\OrderByNameScope;
use halestar\LaravelDropInCms\Traits\BackUpable;
use halestar\LaravelDropInCms\Traits\HasMetadata;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

#[ScopedBy([AvailableOnlyScope::class, OrderByNameScope::class])]
class Site extends Model implements ContainsCssSheets, ContainsJsScripts, ContainsMetadata
{
    use BackUpable, HasMetadata;

    public const CURRENT_SITE_KEY = "sites.current_site_id";

    protected static function getTablesToBackup(): array
        {
            return
                [
                    config('dicms.table_prefix') . "sites",
                    config('dicms.table_prefix') . "sites_css_sheets",
                    config('dicms.table_prefix') . "sites_js_scripts",
                ];
        }
    protected function casts(): array
    {
        return
            [
                'active' => 'boolean',
                'archived' => 'boolean',
                'headers_count' => 'boolean',
                'tag' => WrapperTagType::class,
                'metadata' => 'array',
                'created_at' => 'datetime:Y-m-d H:i:s',
                'updated_at' => 'datetime:Y-m-d H:i:s',
            ];
    }


    protected $fillable = ['name', 'title', 'description', 'body_attr', 'favicon', 'active', 'archived', 'homepage_url',
        'tag','options', 'header_id', 'footer_id', 'metadata'];

    public function __construct(array $attributes = [])
    {
        $this->table = config('dicms.table_prefix') . "sites";
        parent::__construct($attributes);
    }

    public function defaultHeader(): BelongsTo
    {
        return $this->belongsTo(Header::class, "header_id");
    }

    public function defaultFooter(): BelongsTo
    {
        return $this->belongsTo(Footer::class, 'footer_id');
    }

    public function siteCss(): BelongsToMany
    {
        return $this->belongsToMany(CssSheet::class, config('dicms.table_prefix') . 'sites_css_sheets',
            'site_id', 'sheet_id')->withPivot('order_by')->orderByPivot('order_by');
    }

    public function CssLinks(): Collection
    {
        $links = $this->siteCss()->links()->get()->pluck('href');
        $links->push(DiCMS::dicmsPublicCss($this));
        return $links;
    }

    public function CssText(): string
    {
        return $this->siteCss()->text()->get()->pluck('sheet')->join("\n");
    }

    public function siteJs(): BelongsToMany
    {
        return $this->belongsToMany(JsScript::class, config('dicms.table_prefix') . 'sites_js_scripts',
            'site_id', 'script_id')->withPivot('order_by')->orderByPivot('order_by');
    }

    public function JsLinks(): Collection
    {
        $links = $this->siteJs()->links()->get()->pluck('href');
        $links->push(DiCMS::dicmsPublicJs($this));
        return $links;
    }

    public function JsText(): string
    {
        return $this->siteJs()->text()->get()->pluck('script')->join("\n");
    }

    public function getCssSheets(): Collection
    {
        return $this->siteCss;
    }

    public function addCssSheet(CssSheet $cssSheet)
    {
        $order = $this->siteCss()->count();
        $this->siteCss()->attach($cssSheet->id, ['order_by' => $order]);
    }

    public function removeCssSheet(CssSheet $cssSheet)
    {
        $this->siteCss()->detach($cssSheet->id);
    }

    public function setCssSheetOrder(CssSheet $cssSheet, int $order)
    {
        $this->siteCss()->updateExistingPivot($cssSheet->id, ['order_by' => $order]);
    }

    public function getJsScripts(): Collection
    {
        return $this->siteJs;
    }

    public function addJsScript(JsScript $script)
    {
        $order = $this->siteJs()->count();
        $this->siteJs()->attach($script->id, ['order_by' => $order]);
    }

    public function removeJsScript(JsScript $script)
    {
        $this->siteJs()->detach($script->id);
    }

    public function setJsScriptOrder(JsScript $script, int $order)
    {
        $this->siteJs()->updateExistingPivot($script->id, ['order_by' => $order]);
    }

    public static function defaultSite(): ?Site
    {
        return Site::where('active', true)->first();
    }

    public static function currentSite(): ?Site
    {
        $settings = config('dicms.settings_class');
        //first, do we have a saved current site?
        $id = $settings::get(Site::CURRENT_SITE_KEY, null);
        if($id)
        {
            //we do, but is it valid?
            $site = Site::find($id);
            if($site)
                return $site;
        }
        //not saved (or invalid), is there an active site?
        $site = Site::defaultSite();
        if($site)
        {
            //there is, save it and return it.
            $settings::set(Site::CURRENT_SITE_KEY, $site->id);
            return $site;
        }
        //there isn't, do we have A site?
        $site = Site::first();
        if($site)
        {
            //there is, save it and return it.
            $settings::set(Site::CURRENT_SITE_KEY, $site->id);
            return $site;
        }
        //nope, system is empty.
        return null;
    }

    public function makeCurrent(): void
    {
        $settings = config('dicms.settings_class');
        $settings::set(Site::CURRENT_SITE_KEY, $this->id);
    }

    public function dupe(): Site
    {
        $dupeSite = new Site();
        $dupeSite->name = $this->name . "-" . __('dicms::admin.copy');
        $dupeSite->description = $this->description;
        $dupeSite->title = $this->title . "-" . __('dicms::admin.copy');
        $dupeSite->body_attr = $this->body_attr;
        $dupeSite->homepage_url = $this->homepage_url;
        $dupeSite->active = false;
        $dupeSite->archived = false;
        $dupeSite->favicon = $this->favicon;
        $dupeSite->tag = $this->tag;
        $dupeSite->options = $this->options;
        $dupeSite->header_id = $this->header_id;
        $dupeSite->footer_id = $this->footer_id;
        $dupeSite->metadata = $this->metadata;
        $dupeSite->save();

        foreach($this->siteCss as $css)
            $dupeSite->siteCss()->attach($css->id, ['order_by' => $css->pivot->order_by]);
        foreach($this->siteJs as $js)
            $dupeSite->siteJs()->attach($js->id, ['order_by' => $css->pivot->order_by]);
        //now we return the new object.
        return $dupeSite;
    }

    public function getMetadata(): array
    {
        return $this->metadata;
    }

    public function setMetadata(array $metadata)
    {
        $this->metadata = $metadata;
        $this->save();
    }
}
