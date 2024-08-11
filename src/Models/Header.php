<?php

namespace halestar\LaravelDropInCms\Models;

use halestar\LaravelDropInCms\Traits\BackUpable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Header extends Model
{
    use BackUpable;
    protected $fillable = ['name','description','html', 'options', 'data', 'css'];
    protected static function getTablesToBackup(): array { return [ config('dicms.table_prefix') . "headers" ]; }
    protected function casts(): array
    {
        return
            [
                'data' => 'array',
            ];
    }
    public function __construct(array $attributes = [])
    {
        $this->table = config('dicms.table_prefix') . "headers";
        parent::__construct($attributes);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'site_id');
    }
}
