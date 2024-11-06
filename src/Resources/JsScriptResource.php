<?php

namespace halestar\LaravelDropInCms\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JsScriptResource extends JsonResource
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
            'script' => $this->script,
            'href' => $this->href,
            'link_type' => $this->link_type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
