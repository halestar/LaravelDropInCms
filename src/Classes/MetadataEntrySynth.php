<?php

namespace halestar\LaravelDropInCms\Classes;

use Livewire\Mechanisms\HandleComponents\Synthesizers\Synth;

class MetadataEntrySynth extends Synth
{
    public static $key = "metadataEntry";
	static function match($target)
	{
		return $target instanceof MetadataEntry;
	}

    public function dehydrate($target)
    {
        return [[
            'name' => $target->name,
            'content' => $target->content,
        ], []];
    }

    public function hydrate($value)
    {
        $instance = new MetadataEntry();

        $instance->name = $value['name'];
        $instance->content = $value['content'];

        return $instance;
    }
}
