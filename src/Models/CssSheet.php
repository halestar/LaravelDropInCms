<?php

namespace halestar\LaravelDropInCms\Models;

use halestar\LaravelDropInCms\Enums\HeadElementType;
use halestar\LaravelDropInCms\Models\Scopes\OrderByNameScope;
use halestar\LaravelDropInCms\Traits\BackUpable;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[ScopedBy([OrderByNameScope::class])]
class CssSheet extends Model
{
    use BackUpable;

    protected static function getTablesToBackup(): array { return [ config('dicms.table_prefix') . "css_sheets" ]; }
    protected function casts(): array
    {
        return
            [
                'type' => HeadElementType::class,
                'created_at' => 'datetime:Y-m-d H:i:s',
                'updated_at' => 'datetime:Y-m-d H:i:s',
            ];
    }

    protected $fillable = ['name','description','sheet', 'href', 'link_type','type'];
    public function __construct(array $attributes = [])
    {
        $this->table = config('dicms.table_prefix') . "css_sheets";
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

    public function dupe(Site $site = null): CssSheet
    {
        $dupeSheet = new CssSheet();
        $dupeSheet->name = $this->name . "-" . __('dicms::admin.copy');
        $dupeSheet->type = $this->type;
        $dupeSheet->description = $this->description;
        $dupeSheet->sheet = $this->sheet;
        $dupeSheet->href = $this->href;
        $dupeSheet->link_type = $this->link_type;
        $dupeSheet->save();
        return $dupeSheet;
    }

    public function toArray(): array
    {
        return
            [
                'id' => $this->id,
                'type' => $this->type->value,
                'name' => $this->name,
                'description' => $this->description,
                'sheet' => $this->sheet,
                'href' => $this->href,
                'link_type' => $this->link_type,
                'created_at' => $this->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            ];
    }

}
