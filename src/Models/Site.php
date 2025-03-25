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
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

#[ScopedBy([AvailableOnlyScope::class, OrderByNameScope::class])]
class Site extends Model implements ContainsCssSheets, ContainsJsScripts, ContainsMetadata
{
    use BackUpable, HasMetadata;

    protected static function getTablesToBackup(): array
        {
            return
                [
                    config('dicms.table_prefix') . "sites",
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

    public function siteCss(): HasMany
    {
        return $this->hasMany(CssSheet::class, 'site_id')->orderBy('order_by');
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

    public function siteJs(): HasMany
    {
        return $this->hasMany(JsScript::class, 'site_id')->orderBy('order_by');
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
        return $this->siteCss()->where('active', true)->get();
    }

    public function addCssSheet(CssSheet $cssSheet)
    {
        $cssSheet->active = true;
        $cssSheet->save();
    }

    public function removeCssSheet(CssSheet $cssSheet)
    {
        $cssSheet->active = false;
        $cssSheet->save();
    }

    public function setCssSheetOrder(CssSheet $cssSheet, int $order)
    {
        $cssSheet->order_by = $order;
        $cssSheet->save();
    }

    public function getJsScripts(): Collection
    {
        return $this->siteJs()->where('active', true)->get();
    }

    public function addJsScript(JsScript $script)
    {
        $script->active = true;
        $script->save();
    }

    public function removeJsScript(JsScript $script)
    {
        $script->active = false;
        $script->save();
    }

    public function setJsScriptOrder(JsScript $script, int $order)
    {
        $script->order_by = $order;
        $script->save();
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
            $css->dupe($dupeSite);
        foreach($this->siteJs as $js)
            $js->dupe($dupeSite);
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

    public static function activeSite(): ?Site
    {
        return Site::where('active', true)->first();
    }

    public function headers(): HasMany
    {
        return $this->hasMany(Header::class, 'site_id');
    }

    public function footers(): HasMany
    {
        return $this->hasMany(Footer::class, 'site_id');
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class, 'site_id');
    }

    public function getCssSheetPool(): Collection
    {
        return $this->siteCss;
    }

    public function getJsScriptPool(): Collection
    {
        return $this->siteJs;
    }
}
