<?php

namespace halestar\LaravelDropInCms\Models;

use halestar\LaravelDropInCms\Traits\BackUpable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Footer extends Model
{
    use BackUpable;

    protected static function getTablesToBackup(): array { return [ config('dicms.table_prefix') . "footers" ]; }

    protected $fillable = ['name','description','html', 'options', 'data', 'css'];
    protected $casts =
        [
            'data' => 'json',
        ];
    public function __construct(array $attributes = [])
    {
        $this->table = config('dicms.table_prefix') . "footers";
        parent::__construct($attributes);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'site_id');
    }
}
