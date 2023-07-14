<?php
// Copyright (C) 2023 jmh
// SPDX-License-Identifier: GPL-3.0-only

namespace AuthPro\Utility;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Exception\CommonMarkException;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\ExternalLink\ExternalLinkExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkRenderer;
use League\CommonMark\Extension\TableOfContents\TableOfContentsExtension;
use League\CommonMark\MarkdownConverter;

class MarkdownRenderer
{
    private readonly Environment $environment;

    private static array $config = [
        "external_link" => [
            "internal_hosts" => DOMAIN_NAME,
            "open_in_new_window" => true,
            "nofollow" => "external",
            "noopener" => "external",
            "noreferrer" => "external",
        ],
        "heading_permalink" => [
            "html_class" => "permalink",
            "id_prefix" => "",
            "fragment_prefix" => "",
            "insert" => "after",
            "min_heading_level" => 2,
            "max_heading_level" => 6,
            "title" => "Permalink",
            "symbol" => HeadingPermalinkRenderer::DEFAULT_SYMBOL,
            "aria_hidden" => true,
        ]
    ];

    public function __construct()
    {
        $this->environment = new Environment(self::$config);
        $this->environment->addExtension(new CommonMarkCoreExtension());
        $this->environment->addExtension(new GithubFlavoredMarkdownExtension());
        $this->environment->addExtension(new HeadingPermalinkExtension());
        $this->environment->addExtension(new ExternalLinkExtension());
        $this->environment->addExtension(new TableOfContentsExtension());
    }

    /**
     * @throws CommonMarkException
     */
    public function render(string $markdown): string
    {
        $converter = new MarkdownConverter($this->environment);
        return $converter->convert($markdown)->getContent();
    }
}
