<?php

namespace halestar\LaravelDropInCms\Models;

use halestar\LaravelDropInCms\Traits\BackUpable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Site extends Model
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
    protected $casts =
        [
            'active' => 'boolean',
            'archived' => 'boolean',
        ];

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
            'site_id', 'sheet_id')->withPivot('order_by');
    }

    public function jsScripts():HasMany
    {
        return $this->hasMany(JsScript::class, 'site_id');
    }

    public function siteJs(): BelongsToMany
    {
        return $this->belongsToMany(JsScript::class, config('dicms.table_prefix') . 'sites_js_scripts',
            'site_id', 'script_id')->withPivot('order_by');
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class, 'site_id');
    }

}
