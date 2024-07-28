<?php

namespace halestar\LaravelDropInCms\Models;

use halestar\LaravelDropInCms\Enums\HeadElementType;
use halestar\LaravelDropInCms\Traits\BackUpable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CssSheet extends Model
{
    use BackUpable;

    protected static function getTablesToBackup(): array { return [ config('dicms.table_prefix') . "css_sheets" ]; }
    protected $casts =
        [
            'type' => HeadElementType::class,
        ];
    protected $fillable = ['name','description','sheet', 'href', 'link_type','type'];
    public function __construct(array $attributes = [])
    {
        $this->table = config('dicms.table_prefix') . "css_sheets";
        parent::__construct($attributes);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    public function scopeLinks(Builder $query): void
    {
        $query->where('type', '=', HeadElementType::Link);
    }

    public function scopeText(Builder $query): void
    {
        $query->where('type', '=', HeadElementType::Text);
    }

}
