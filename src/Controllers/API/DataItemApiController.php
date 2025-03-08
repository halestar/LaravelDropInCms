<?php

namespace halestar\LaravelDropInCms\Controllers\API;

use App\Http\Controllers\Controller;
use halestar\LaravelDropInCms\Models\DataItem;
use halestar\LaravelDropInCms\Resources\DataItemResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;

class DataItemApiController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(!Gate::allows('viewAny', DataItem::class))
            return response()->json([], Response::HTTP_FORBIDDEN);
        return DataItemResource::collection(DataItem::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(!Gate::allows('create', DataItem::class))
            return response()->json([], Response::HTTP_FORBIDDEN);
        $validator = Validator::make($request->all(),
            [
                'name' => 'required|max:255',
                'parent_id' => 'nullable|exists:' . config('dicms.table_prefix') . 'data_items,id',
                'is_folder' => 'required|boolean',
                'file' => ['required_if:is_folder,false', File::types(['png','jpg','jpeg','svg', 'gif'])],
            ]);
        if($validator->fails())
            return response()->json($validator->errors()->toArray(), Response::HTTP_BAD_REQUEST);
        //if there is a parent_id, make sure it resolces to a folder
        if($validator->getValue('parent_id'))
        {
            $parent = DataItem::find($validator->getValue('parent_id'));
            if(!$parent || !$parent->is_folder)
                return response()->json(['parent_id' => 'The parent must be a folder'], Response::HTTP_BAD_REQUEST);
        }
        if($validator->getValue('is_folder'))
        {
            $data_item = new DataItem();
            $data_item->name = $validator->getValue('name');
            $data_item->is_folder = true;
            $data_item->parent_id = $validator->getValue('parent_id');
            $data_item->thumb = null;
            $data_item->save();
            return DataItemResource::make($data_item)
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);
        }
        else
        {
            $file = $request->file('file');
            if (preg_match('/image\/.+/', $file->getMimeType()))
            {
                $manager = new ImageManager(new Driver());
                $img = $manager->read($file->get());
                if ($img)
                    $img->scaleDown(height: config('dicms.img_max_height'));
                $path = pathinfo($file->hashName(), PATHINFO_FILENAME) . ".png";
                Storage::disk(config('dicms.media_upload_disk'))->put($path, $img->toPng());
                // with this new item, we create the data item.
                $data_item = new DataItem();
                $data_item->name = $validator->getValue('name');
                $data_item->thumb = null;
                $data_item->is_folder = false;
                $data_item->parent_id = $validator->getValue('parent_id');
                $data_item->path = $path;
                $data_item->url = Storage::disk(config('dicms.media_upload_disk'))->url($path);
                $data_item->mime = "image/png";
                $data_item->save();
                return DataItemResource::make($data_item)
                    ->response()
                    ->setStatusCode(Response::HTTP_CREATED);
            }
        }
        return response()->json([], Response::HTTP_BAD_REQUEST);
    }

    /**
     * Display the specified resource.
     */
    public function show(DataItem $data_item)
    {
        if(!Gate::allows('view', $data_item))
            return response()->json([], Response::HTTP_FORBIDDEN);
        return new DataItemResource($data_item);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DataItem $data_item)
    {
        if(!Gate::allows('update', $data_item))
            return response()->json([], Response::HTTP_FORBIDDEN);
        $validator = Validator::make($request->all(),
            [
                'name' =>
                    [
                        'required',
                        'max:255',
                        Rule::unique(config('dicms.table_prefix') . 'data_items')->ignore($data_item->id),
                    ],
                'parent_id' => 'nullable|exists:' . config('dicms.table_prefix') . 'data_items,id',
                'is_folder' => 'required|boolean',
                'file' => ['nullable', File::types(['png','jpg','jpeg','svg', 'gif'])],
            ]);
        if($validator->fails())
            return response()->json($validator->errors()->toArray(), Response::HTTP_BAD_REQUEST);
        if($validator->getValue('name'))
            $data_item->name = $validator->getValue('name');
        $data_item->parent_id = $validator->getValue('parent_id');
        //replkace teh fil if we need to
        if(!$data_item->is_folder && $validator->getValue('file'))
        {
            $file = $request->file('file');
            if (preg_match('/image\/.+/', $file->getMimeType()))
            {
                $manager = new ImageManager(new Driver());
                $img = $manager->read($file->get());
                if ($img)
                    $img->scaleDown(height: config('dicms.img_max_height'));
                Storage::disk(config('dicms.media_upload_disk'))->put($data_item->path, $img->toPng());
            }
        }
        $data_item->save();
        return new DataItemResource($data_item);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DataItem $data_item)
    {
        if(!Gate::allows('delete', $data_item))
            return response()->json([], Response::HTTP_FORBIDDEN);
        $data_item->delete();
        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
