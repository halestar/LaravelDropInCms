<?php

namespace halestar\LaravelDropInCms\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DataItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return
            [
                'id' => $this->id,
                'parent_id' => $this->parent_id,
                'name' => $this->name,
                'path' => $this->path,
                'url' => $this->url,
                'mime' => $this->mime,
                'thumb' => $this->thumb(),
                'is_folder' => $this->is_folder,
                'created_at' => $this->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            ];
    }
}
