<?php

namespace AuthPro\Entity;

class SitemapItem
{
    public string $uri;
    public string $changeFrequency;
    public float $priority;

    public function __construct(string $uri, string $changeFrequency, float $priority)
    {
        $this->uri = $uri;
        $this->changeFrequency = $changeFrequency;
        $this->priority = $priority;
    }
}