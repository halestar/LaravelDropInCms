<?php

namespace halestar\LaravelDropInCms\Models;

use halestar\LaravelDropInCms\Enums\HeadElementType;
use halestar\LaravelDropInCms\Traits\BackUpable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JsScript extends Model
{
    use BackUpable;

    protected static function getTablesToBackup(): array { return [ config('dicms.table_prefix') . "js_scripts" ]; }
    protected function casts(): array
    {
        return
            [
                'type' => HeadElementType::class,
            ];
    }
    protected $fillable = ['name','description','script', 'href', 'link_type','type'];
    public function __construct(array $attributes = [])
    {
        $this->table = config('dicms.table_prefix') . "js_scripts";
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
