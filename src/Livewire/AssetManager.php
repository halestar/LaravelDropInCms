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
    public ?DataItem $selectedFolder = null;

    private array $thumbableMimes =
        [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/bmp',
            'image/tiff',
        ];

    public function mount($mini = false, $selectAction = null)
    {
        //load all the assets in the system
        $assets = DataItem::root();
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
                            'parent_id' => null,
                            'path' => $file,
                            'url' => Storage::disk(config('dicms.media_upload_disk'))->url($file),
                            'mime' => Storage::disk(config('dicms.media_upload_disk'))->mimeType($file),
                            'is_folder' => false,
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
        if($this->selectedFolder)
            $query = DataItem::where('parent_id', $this->selectedFolder->id);
        else
            $query = DataItem::whereNull('parent_id');
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
        $newItem->is_folder = false;
        $newItem->parent_id = $this->selectedFolder? $this->selectedFolder->id: null;
        $newItem->save();
        return $newItem;
    }

    public function addFile()
    {
        //we need the manager to convert the images.
        $manager = new ImageManager(new Driver());
        if(is_array($this->dataItem))
        {
            //first, since we're uploading multiple files, we will need to go through each one.
            foreach($this->dataItem as $file)
            {
                //if it's not an image, we don't really need to do anything.
                if(!preg_match('/image\/.+/', $file->getMimeType()))
                    continue;

                // if it IS an image and it is not an image that we can manipulate, then just store the file.
                if(!in_array($file->getMimeType(), $this->thumbableMimes))
                {
                    //store the file
                    $path = $file->store('', config('dicms.media_upload_disk'));
                }
                else
                {
                    // else, we will first convert it into a PNG, scale it to our settings and save it.
                    $img = $manager->read($file->get());
                    if ($img)
                        $img->scaleDown(height: config('dicms.img_max_height'));
                    $path = pathinfo($file->hashName(), PATHINFO_FILENAME) . ".png";
                    Storage::disk(config('dicms.media_upload_disk'))->put($path, $img->toPng());
                }
                // with this new item, we create the data item.
                $this->createDataItem(
                    [
                        'name' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                        'path' => $path,
                        'mime' => in_array($file->getMimeType(), $this->thumbableMimes)?  "image/png": $file->getMimeType(),
                        'url' => Storage::disk(config('dicms.media_upload_disk'))->url($path),
                    ]);
            }
        }
        elseif(preg_match('/image\/.+/', $this->dataItem->getMimeType()))
        {
            //if we're doing a single image file and we can't thumb it, then store it.
            if(!in_array($this->dataItem->getMimeType(), $this->thumbableMimes))
            {
                //store the file
                $path = $this->dataItem->store('', config('dicms.media_upload_disk'));
            }
            else
            {
                //for a single file that IS thubable, convert it into a PNG, scale it and store it.
                $img = $manager->read($this->dataItem->get());
                if ($img)
                    $img->scaleDown(height: config('dicms.img_max_height'));
                $path = pathinfo($this->dataItem->hashName(), PATHINFO_FILENAME) . ".png";
                Storage::disk(config('dicms.media_upload_disk'))->put($path, $img->toPng());
            }
            // with this new item, we create the data item.
            $this->createDataItem(
                [
                    'name' => pathinfo($this->dataItem->getClientOriginalName(), PATHINFO_FILENAME),
                    'path' => $path,
                    'mime' => in_array($this->dataItem->getMimeType(), $this->thumbableMimes)?  "image/png": $this->dataItem->getMimeType(),
                    'url' => Storage::disk(config('dicms.media_upload_disk'))->url($path),
                ]);
        }
        //after uploading, we refresh the assets.
        $this->refreshAssets();
    }

    public function addFolder()
    {
        $newItem = new DataItem();
        $newItem->name = __('dicms::assets.folder.new');
        $newItem->path = null;
        $newItem->mime = null;
        $newItem->url = null;
        $newItem->is_folder = true;
        $newItem->parent_id = $this->selectedFolder? $this->selectedFolder->id: null;
        $newItem->save();
        return $newItem;
    }

    function removeDataItem(DataItem $item)
    {
        $item->delete();
        $this->refreshAssets();
    }

    function moveDataItem(DataItem $item, DataItem $folder)
    {
        $item->parent_id = $folder->id;
        $item->save();
        $this->refreshAssets();
    }

    function moveToRoot(DataItem $item)
    {
        $item->parent_id = null;
        $item->save();
        $this->refreshAssets();
    }

    function viewItem(DataItem $item)
    {
        $this->viewingItem = $item;
    }

    function viewFolder(DataItem $folder)
    {
        if($folder->is_folder)
        {
            $this->selectedFolder = $folder;
            $this->refreshAssets();
        }
    }

    public function viewRoot()
    {
        $this->selectedFolder = null;
        $this->refreshAssets();
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
