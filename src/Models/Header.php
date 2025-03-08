<?php

namespace halestar\LaravelDropInCms\Models;

use halestar\LaravelDropInCms\Classes\GrapesJsEditableItem;
use halestar\LaravelDropInCms\Models\Scopes\OrderByNameScope;
use halestar\LaravelDropInCms\Traits\BackUpable;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;

#[ScopedBy([OrderByNameScope::class])]
class Header extends GrapesJsEditableItem
{
    use BackUpable;
    protected $fillable = ['name','description', 'html', 'data', 'css'];
    protected static function getTablesToBackup(): array { return [ config('dicms.table_prefix') . "headers" ]; }
    protected function casts(): array
    {
        return
            [
                'data' => 'array',
                'created_at' => 'datetime:Y-m-d H:i:s',
                'updated_at' => 'datetime:Y-m-d H:i:s',
            ];
    }
    public function __construct(array $attributes = [])
    {
        $this->table = config('dicms.table_prefix') . "headers";
        parent::__construct($attributes);
    }

    public function dupe(Site $site = null): Header
    {
        $newHeader = new Header();
        $newHeader->name = $this->name . "-" . __('dicms::admin.copy');
        $newHeader->description = $this->description;
        $newHeader->html = $this->html;
        $newHeader->css = $this->css;
        $newHeader->data = $this->data;
        $newHeader->save();
        return $newHeader;
    }
}
