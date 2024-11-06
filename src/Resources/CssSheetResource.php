<?php

namespace halestar\LaravelDropInCms\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CssSheetResource extends JsonResource
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
            'type' => $this->type->value,
            'name' => $this->name,
            'description' => $this->description,
            'sheet' => $this->sheet,
            'href' => $this->href,
            'link_type' => $this->link_type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
