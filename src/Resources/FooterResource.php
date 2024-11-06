<?php

namespace halestar\LaravelDropInCms\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FooterResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'html' => $this->html,
            'css' => $this->css,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
