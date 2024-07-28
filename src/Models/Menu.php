<?php

namespace halestar\LaravelDropInCms\Models;

use halestar\LaravelDropInCms\Traits\BackUpable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Menu extends Model
{
    use BackUpable;

    protected static function getTablesToBackup(): array { return [ config('dicms.table_prefix') . "menus" ]; }
    protected function casts(): array
    {
        return [ 'menu' => 'array'];
    }
    protected $fillable = ['name','description','menu', 'element_classes', 'container_classes', 'nav_classes', 'link_classes'];
    public function __construct(array $attributes = [])
    {
        $this->table = config('dicms.table_prefix') . "menus";
        parent::__construct($attributes);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'site_id');
    }
}
