<?php

namespace halestar\LaravelDropInCms\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SiteResource extends JsonResource
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
            'title' => $this->title,
            'body_attr' => $this->body_attr,
            'active' => $this->active,
            'archived' => $this->archived,
            'defaultHeader' => new HeaderResource($this->defaultHeader),
            'defaultFooter' => new FooterResource($this->defaultFooter),
            'homepage_url' => $this->homepage_url,
            'favicon' => $this->favicon,
            'tag' => $this->tag,
            'options' => $this->options,
            'siteCss' => CssSheetResource::collection($this->siteCss),
            'siteJs' => JsScriptResource::collection($this->siteJs),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
