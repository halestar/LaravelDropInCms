<?php

namespace halestar\LaravelDropInCms\Livewire;

use halestar\LaravelDropInCms\Models\DataItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;
use Livewire\Component;
use Livewire\WithFileUploads;

class AssetManager extends Component
{
    use WithFileUploads;

    public Collection $assets;

    public $dataItem;
    public ?DataItem $viewingItem = null;
    public bool $mini = false;
    public $selectAction;
    public $filterTerms;

    public function mount($mini = false, $selectAction = null)
    {
        //load all the assets in the system
        $assets = DataItem::all();
        $files =  Storage::disk(config('dicms.media_upload_disk'))->files();
        if($assets->count() != count($files))
        {
            //let do a reconciliation
            foreach($files as $file)
            {
                //we will be checking by path.
                if(!DataItem::where('path', $file)->exists())
                {
                    DataItem::create(
                        [
                            'name' => basename($file),
                            'path' => $file,
                            'url' => Storage::disk(config('dicms.media_upload_disk'))->url($file),
                            'mime' => Storage::disk(config('dicms.media_upload_disk'))->mimeType($file),
                        ]
                    );
                }
            }
        }

        $this->assets = $assets;
        $this->mini = $mini;
        $this->selectAction = $selectAction;
        $this->filterTerms = "";
    }

    public function refreshAssets()
    {
        $query = DataItem::orderBy('name');
        if($this->filterTerms)
            $query->where('name', 'like', '%'.$this->filterTerms.'%');
        $this->assets = $query->get();
    }

    public function clearFiter()
    {
        $this->filterTerms = "";
    }

    public function updateName(DataItem $asset, $name)
    {
        $asset->name = $name;
        $asset->save();
        $this->refreshAssets();
    }

    private function createDataItem(array $attributes): DataItem
    {
        $newItem = new DataItem();
        $newItem->name = $attributes['name'];
        $newItem->path = $attributes['path'];
        $newItem->mime = $attributes['mime'];
        $newItem->url = $attributes['url'];
        $newItem->save();
        return $newItem;
    }

    public function addFile()
    {
        $manager = new ImageManager(new Driver());
        if(is_array($this->dataItem))
        {
            foreach($this->dataItem as $file)
            {
                if(!preg_match('/image\/.+/', $file->getMimeType()))
                    continue;

                if($file->getMimeType() == "image/x-icon")
                {
                    //store the file
                    $path = $file->store('', config('dicms.media_upload_disk'));
                }
                else
                {
                    $img = $manager->read($file->get());
                    if ($img)
                        $img->scaleDown(height: config('dicms.img_max_height'));
                    $path = pathinfo($file->hashName(), PATHINFO_FILENAME) . ".png";
                    Storage::disk(config('dicms.media_upload_disk'))->put($path, $img->toPng());
                }
                $this->createDataItem(
                    [
                        'name' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                        'path' => $path,
                        'mime' => ($file->getMimeType() == "image/x-icon")?? "image/png",
                        'url' => Storage::disk(config('dicms.media_upload_disk'))->url($path),
                    ]);
            }
        }
        elseif(preg_match('/image\/.+/', $this->dataItem->getMimeType()))
        {
            if($this->dataItem->getMimeType() == "image/x-icon")
            {
                //store the file
                $path = $this->dataItem->store('', config('dicms.media_upload_disk'));
            }
            else
            {
                $img = $manager->read($this->dataItem->get());
                if ($img)
                    $img->scaleDown(height: config('dicms.img_max_height'));
                $path = pathinfo($this->dataItem->hashName(), PATHINFO_FILENAME) . ".png";
                Storage::disk(config('dicms.media_upload_disk'))->put($path, $img->toPng());
            }
            $this->createDataItem(
                [
                    'name' => pathinfo($this->dataItem->getClientOriginalName(), PATHINFO_FILENAME),
                    'path' => $path,
                    'mime' => ($this->dataItem->getMimeType() == "image/x-icon")?? "image/png",
                    'url' => Storage::disk(config('dicms.media_upload_disk'))->url($path),
                ]);
        }
        $this->refreshAssets();
    }

    function removeDataItem(DataItem $item)
    {
        $item->delete();
        $this->refreshAssets();
    }

    function viewItem(DataItem $item)
    {
        $this->viewingItem = $item;
    }

    function closeView()
    {
        $this->viewingItem = null;
    }
    public function render()
    {
        $this->refreshAssets();
        return view('dicms::livewire.asset-manager');
    }
}
