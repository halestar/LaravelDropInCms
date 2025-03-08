<?php

namespace halestar\LaravelDropInCms\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
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
            'plugin_page' => $this->plugin_page,
            'plugin' => $this->plugin,
            'name' => $this->name,
            'slug' => $this->slug,
            'title' => $this->title,
            'path' => $this->path,
            'url' => $this->url,
            'override_css' => $this->override_css,
            'pageCss' => CssSheetResource::collection($this->pageCss),
            'override_js' => $this->override_js,
            'pageJs' => JsScriptResource::collection($this->pageJs),
            'override_header' => $this->override_header,
            'defaultHeader' => new HeaderResource($this->defaultHeader),
            'override_footer' => $this->override_footer,
            'defaultFooter' => new FooterResource($this->defaultFooter),
            'html' => $this->html,
            'css' => $this->css,
            'data' => $this->data,
            'published' => $this->published,
            'metadata' => $this->metadata,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
