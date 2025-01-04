<?php

namespace halestar\LaravelDropInCms\Livewire;

use halestar\LaravelDropInCms\Classes\MetadataEntry;
use halestar\LaravelDropInCms\Interfaces\ContainsMetadata;
use halestar\LaravelDropInCms\Models\Site;
use Livewire\Component;

class MetadataEditor extends Component
{
    public ContainsMetadata $container;
    public array $metadata;

    public const TWITTER_META =
        [
            'twitter:card',
            'twitter:site',
            'twitter:creator',
            'twitter:title',
            'twitter:description',
            'twitter:image',
        ];

    public const OG_META =
        [
            'og:type',
            'og:title',
            'og:description',
            'og:image',
            'og:url',
            'og:site_name',
        ];

    public function mount(ContainsMetadata $container)
    {
        $this->container = $container;
        $this->metadata = $this->container->getMetadata()?? [];
    }

    public function addEntry()
    {
        $this->metadata[] = new MetadataEntry('', '');
        $this->container->setMetadata($this->metadata);
    }
    public function removeEntry($index)
    {
        $old = $this->metadata;
        $this->metadata = [];
        $idx = 0;
        foreach($old as $entry)
        {
            if($idx != $index)
                $this->metadata[] = $entry;
            $idx++;
        }
        $this->container->setMetadata($this->metadata);
    }

    public function updateName($idx, $name)
    {
        $this->metadata[$idx]->name = $name;
        $this->container->setMetadata($this->metadata);
    }

    public function updateContent($idx, $content)
    {
        $this->metadata[$idx]->content = $content;
        $this->container->setMetadata($this->metadata);
    }

    public function importFromSite()
    {
        $this->container->metadata = Site::defaultSite()->getMetadata();
        $this->container->setMetadata($this->metadata);
    }

    public function importTwitter()
    {
        foreach(self::TWITTER_META as $meta)
            $this->metadata[] = new MetadataEntry($meta, '');
        $this->container->setMetadata($this->metadata);
    }

    public function importOG()
    {
        foreach(self::OG_META as $meta)
            $this->metadata[] = new MetadataEntry($meta, '');
        $this->container->setMetadata($this->metadata);
    }

    public function clearAll()
    {
        $this->metadata = [];
        $this->container->setMetadata($this->metadata);
    }

    public function render()
    {
        return view('dicms::livewire.metadata-editor');
    }
}
