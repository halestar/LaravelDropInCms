<?php

namespace halestar\LaravelDropInCms\Models;

use halestar\LaravelDropInCms\Interfaces\ContainsCssSheets;
use halestar\LaravelDropInCms\Interfaces\ContainsJsScripts;
use halestar\LaravelDropInCms\Traits\BackUpable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Site extends Model implements ContainsCssSheets, ContainsJsScripts
{
    use BackUpable;


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
            ];
    }


    protected $fillable = ['name', 'title'];

    public function __construct(array $attributes = [])
    {
        $this->table = config('dicms.table_prefix') . "sites";
        parent::__construct($attributes);
    }

    public function headers(): HasMany
    {
        return $this->hasMany(Header::class, 'site_id');
    }

    public function defaultHeader(): BelongsTo
    {
        return $this->belongsTo(Header::class, "header_id");
    }

    public function footers(): HasMany
    {
        return $this->hasMany(Footer::class, 'site_id');
    }

    public function defaultFooter(): BelongsTo
    {
        return $this->belongsTo(Footer::class, 'footer_id');
    }

    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class, 'site_id');
    }

    public function defaultMenu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function cssSheets():HasMany
    {
        return $this->hasMany(CssSheet::class, 'site_id');
    }

    public function siteCss(): BelongsToMany
    {
        return $this->belongsToMany(CssSheet::class, config('dicms.table_prefix') . 'sites_css_sheets',
            'site_id', 'sheet_id')->withPivot('order_by')->orderByPivot('order_by');
    }

    public function jsScripts():HasMany
    {
        return $this->hasMany(JsScript::class, 'site_id');
    }

    public function siteJs(): BelongsToMany
    {
        return $this->belongsToMany(JsScript::class, config('dicms.table_prefix') . 'sites_js_scripts',
            'site_id', 'script_id')->withPivot('order_by')->orderByPivot('order_by');
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class, 'site_id');
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

    public static function defaultSite(): Site
    {
        return Site::where('active', true)->first();
    }
}
