<?php

namespace halestar\LaravelDropInCms\Models;

use halestar\LaravelDropInCms\Enums\HeadElementType;
use halestar\LaravelDropInCms\Models\Scopes\OrderByNameScope;
use halestar\LaravelDropInCms\Traits\BackUpable;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

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
            ];
    }
    protected $fillable = ['name','description','script', 'href', 'link_type','type'];
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
        $dupeScript->name = $this->name . "-" . __('dicms::admin.copy');
        $dupeScript->type = $this->type;
        $dupeScript->description = $this->description;
        $dupeScript->script = $this->script;
        $dupeScript->href = $this->href;
        $dupeScript->link_type = $this->link_type;
        $dupeScript->save();
        return $dupeScript;
    }
}
