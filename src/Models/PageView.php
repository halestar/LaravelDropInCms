<?php

namespace halestar\LaravelDropInCms\Models;

use halestar\LaravelDropInCms\Traits\BackUpable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

class PageView extends Model
{
    use BackUpable;
    public $incrementing = false;
    protected static function getTablesToBackup(): array
    {
        return
            [
                config('dicms.table_prefix') . "page_visitors",
            ];
    }


    protected $fillable = ['page_id', 'ip_address', 'views'];

    public function __construct(array $attributes = [])
    {
        $this->table = config('dicms.table_prefix') . "page_visitors";
        parent::__construct($attributes);
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id');
    }

    public static function pages(): Collection
    {
        $tPre = config('dicms.table_prefix');
        return Page::select($tPre  . 'pages.*')
            ->join($tPre . 'page_visitors',$tPre . 'page_visitors.page_id', '=',$tPre . 'pages.id')
            ->groupBy($tPre . 'pages.id')->get();
    }

    public static function totalViews(Page $page): int
    {
        return PageView::where('page_id', $page->id)->count();
    }
}
