<?php

namespace halestar\LaravelDropInCms\Models;

use halestar\LaravelDropInCms\Models\Scopes\OrderByNameScope;
use halestar\LaravelDropInCms\Traits\BackUpable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;

#[ScopedBy(OrderByNameScope::class)]
class DataItem extends Model implements Arrayable
{
    use BackUpable;

    protected static function getTablesToBackup(): array { return [ config('dicms.table_prefix') . "data_items" ]; }

    protected $fillable = ['parent_id', 'name','path','url', 'mime', 'thumb','is_folder'];

    protected function casts(): array
    {
        return
            [
                'is_folder' => 'boolean',
                'created_at' => 'datetime:Y-m-d H:i:s',
                'updated_at' => 'datetime:Y-m-d H:i:s',
            ];
    }
    public function __construct(array $attributes = [])
    {
        $this->table = config('dicms.table_prefix') . "data_items";
        parent::__construct($attributes);
    }

    public function thumb(): string
    {
        if($this->is_folder)
            return 'data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M64 480H448c35.3 0 64-28.7 64-64V160c0-35.3-28.7-64-64-64H288c-10.1 0-19.6-4.7-25.6-12.8L243.2 57.6C231.1 41.5 212.1 32 192 32H64C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64z"/></svg>';

        if($this->thumb)
            return $this->thumb;

        if($this->mime == "image/png")
        {
            $manager = new ImageManager(new Driver());
            $thmb = $manager->read(Storage::disk(config('dicms.media_upload_disk'))->get($this->path));
            if($thmb)
                $thmb->scaleDown(height: config('dicms.thumb_max_height'));
            $path = config('dicms.thumb_folder') . "/" . pathinfo($this->path, PATHINFO_FILENAME) . ".png";
            Storage::disk(config('dicms.media_upload_disk'))->put($path, $thmb->toPng());
            $this->thumb = Storage::disk(config('dicms.media_upload_disk'))->url($path);
            $this->save();
            return $this->thumb;
        }
        return 'data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M464 256A208 208 0 1 0 48 256a208 208 0 1 0 416 0zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm169.8-90.7c7.9-22.3 29.1-37.3 52.8-37.3l58.3 0c34.9 0 63.1 28.3 63.1 63.1c0 22.6-12.1 43.5-31.7 54.8L280 264.4c-.2 13-10.9 23.6-24 23.6c-13.3 0-24-10.7-24-24l0-13.5c0-8.6 4.6-16.5 12.1-20.8l44.3-25.4c4.7-2.7 7.6-7.7 7.6-13.1c0-8.4-6.8-15.1-15.1-15.1l-58.3 0c-3.4 0-6.4 2.1-7.5 5.3l-.4 1.2c-4.4 12.5-18.2 19-30.6 14.6s-19-18.2-14.6-30.6l.4-1.2zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"/></svg>';
    }

    public function delete()
    {
        if(!$this->is_folder)
        {
            Storage::disk(config('dicms.media_upload_disk'))->delete($this->path);
            $thmb_path = config('dicms.thumb_folder') . "/" . pathinfo($this->path, PATHINFO_FILENAME) . ".png";
            Storage::disk(config('dicms.media_upload_disk'))->delete($thmb_path);
        }
        return parent::delete();
    }

    public static function root(): Collection
    {
        return DataItem::whereNull('parent_id')->get();
    }

    public function items(): HasMany
    {
        return $this->hasMany(DataItem::class, 'parent_id');
    }

    public function itemParent(): BelongsTo
    {
        return $this->belongsTo(DataItem::class, 'parent_id');
    }

    public function toArray()
    {
        return
        [
            'id' => $this->id,
            'name' => $this->name,
            'is_folder' => $this->is_folder,
            'parent_id' => $this->parent_id,
            'path' => $this->path,
            'url' => $this->url,
            'mime' => $this->mime,
            'thumb' => $this->thumb,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }

}
