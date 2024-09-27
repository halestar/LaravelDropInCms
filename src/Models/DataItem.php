<?php

namespace halestar\LaravelDropInCms\Models;

use halestar\LaravelDropInCms\Traits\BackUpable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;

class DataItem extends Model
{
    use BackUpable;

    protected static function getTablesToBackup(): array { return [ config('dicms.table_prefix') . "data_items" ]; }

    protected $fillable = ['name','path','url', 'mime'];
    public function __construct(array $attributes = [])
    {
        $this->table = config('dicms.table_prefix') . "data_items";
        parent::__construct($attributes);
    }

    public function thumb(): string
    {
        if($this->thumb)
            return $this->thumb;

        if(preg_match('/image\/.+/', $this->mime))
        {
            $manager = new ImageManager(new Driver());
            $thmb = $manager->read(Storage::disk(config('dicms.media_upload_disk'))->get($this->name));
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
        Storage::disk(config('dicms.media_upload_disk'))->delete($this->path);
        $thmb_path = config('dicms.thumb_folder') . "/" . pathinfo($this->path, PATHINFO_FILENAME) . ".png";
        Storage::disk(config('dicms.media_upload_disk'))->delete($thmb_path);
        return parent::delete();
    }


}
