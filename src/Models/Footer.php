<?php

namespace halestar\LaravelDropInCms\Models;

use halestar\LaravelDropInCms\Classes\GrapesJsEditableItem;
use halestar\LaravelDropInCms\Models\Scopes\OrderByNameScope;
use halestar\LaravelDropInCms\Traits\BackUpable;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;

#[ScopedBy([OrderByNameScope::class])]
class Footer extends GrapesJsEditableItem
{
    use BackUpable;

    protected static function getTablesToBackup(): array { return [ config('dicms.table_prefix') . "footers" ]; }

    protected $fillable = ['name','description', 'html', 'data', 'css'];
    protected function casts(): array
    {
        return
            [
                'data' => 'array',
            ];
    }
    public function __construct(array $attributes = [])
    {
        $this->table = config('dicms.table_prefix') . "footers";
        parent::__construct($attributes);
    }


    public function dupe(): Footer
    {
        $newFooter = new Footer();
        $newFooter->name = $this->name . "-" . __('dicms::admin.copy');
        $newFooter->description = $this->description;
        $newFooter->html = $this->html;
        $newFooter->css = $this->css;
        $newFooter->data = $this->data;
        $newFooter->save();
        return $newFooter;
    }

}
