<?php

namespace halestar\LaravelDropInCms\Interfaces;

interface ContainsMetadata
{
    public function getMetadata(): array;
    public function setMetadata(array $metadata);

}
