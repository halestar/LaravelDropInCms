<?php

namespace halestar\LaravelDropInCms\Models;

use halestar\LaravelDropInCms\Enums\HeadElementType;
use halestar\LaravelDropInCms\Models\Scopes\OrderByNameScope;
use halestar\LaravelDropInCms\Traits\BackUpable;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[ScopedBy([OrderByNameScope::class])]
class JsScript extends Model
{
    use BackUpable;

    protected static function getTablesToBackup(): array { return [ config('dicms.table_prefix') . "js_scripts" ]; }
    protected function casts(): array
    {
        return
            [
                'type' => HeadElementType::class,
                'created_at' => 'datetime:Y-m-d H:i:s',
                'updated_at' => 'datetime:Y-m-d H:i:s',
            ];
    }
    protected $fillable = ['name','description','script', 'href', 'link_type','type', 'active', 'order_by'];
    public function __construct(array $attributes = [])
    {
        $this->table = config('dicms.table_prefix') . "js_scripts";
        parent::__construct($attributes);
    }

    public function scopeLinks(Builder $query): void
    {
        $query->where('type', '=', HeadElementType::Link);
    }

    public function scopeText(Builder $query): void
    {
        $query->where('type', '=', HeadElementType::Text);
    }

    public function dupe(Site $site = null): JsScript
    {
        $dupeScript = new JsScript();
        $dupeScript->site_id = $site? $site->id: $this->site_id;
        $dupeScript->name = $this->name . "-" . __('dicms::admin.copy');
        $dupeScript->type = $this->type;
        $dupeScript->description = $this->description;
        $dupeScript->script = $this->script;
        $dupeScript->href = $this->href;
        $dupeScript->link_type = $this->link_type;
        $dupeScript->active = $this->active;
        $dupeScript->order_by = $this->order_by;
        $dupeScript->save();
        return $dupeScript;
    }

    public function toArray(): array
    {
        return
            [
                'id' => $this->id,
                'site_id' => $this->site_id,
                'type' => $this->type->value,
                'name' => $this->name,
                'description' => $this->description,
                'script' => $this->script,
                'href' => $this->href,
                'link_type' => $this->link_type,
                'active' => $this->active,
                'order_by' => $this->order_by,
                'created_at' => $this->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            ];
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    public function pages(): BelongsToMany
    {
        return $this->belongsToMany(Page::class, config('dicms.table_prefix') . 'page_js_scripts');
    }
}
