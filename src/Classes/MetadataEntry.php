<?php

namespace halestar\LaravelDropInCms\Classes;

class MetadataEntry
{
    public string $name;
    public string $content;

    public function __construct(string $name = "", string $content = "")
    {
        $this->name = $name;
        $this->content = $content;
    }

    public function toHTML(): string
    {
        if(str_starts_with($this->name,"og:"))
            return '<meta property="' . $this->name . '" content="' . $this->content . '" />';
        return '<meta name="' . $this->name . '" content="' . $this->content . '" />';
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'content' => $this->content,
        ];
    }
}
