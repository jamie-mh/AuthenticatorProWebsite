<?php
// Copyright (C) 2023 jmh
// SPDX-License-Identifier: GPL-3.0-only

namespace Stratum\Controller;

use Stratum\Core\Controller;
use Stratum\Core\Response;
use Stratum\Core\Response\PageResponse;
use Stratum\Service\GitHubService;
use Stratum\Utility\MarkdownRenderer;

class WikiController extends Controller
{
    private readonly GitHubService $gitHubService;
    private readonly MarkdownRenderer $markdownRenderer;

    public function __construct()
    {
        $this->gitHubService = new GitHubService();
        $this->markdownRenderer = new MarkdownRenderer();
    }

    public function index(): Response
    {
        $res = new PageResponse();
        $res->meta->title = "Wiki";
        $res->meta->description = "Welcome to the Stratum wiki";
        $res->setView("wiki/index.twig");
        return $res;
    }

    public function faq(): Response
    {
        $content = $this->gitHubService->getWikiPage("Frequently-Asked-Questions.md");
        return $this->page("F.A.Q.", "Questions and answers for frequently asked questions", $content);
    }

    public function backupFormat(): Response
    {
        $content = $this->gitHubService->getMarkdownPage("doc/BACKUP_FORMAT.md");
        return $this->page("Backup Format", "File format and encryption for Stratum backup files", $content);
    }

    public function googleAuthenticator(): Response
    {
        $content = $this->gitHubService->getWikiPage("Importing-from-Google-Authenticator.md");
        return $this->page(
            "Import from Google Authenticator",
            "Here's how to transfer your accounts from Google Authenticator to Stratum",
            $content
        );
    }

    public function authy(): Response
    {
        $content = $this->gitHubService->getWikiPage("Importing-from-Authy.md");
        return $this->page(
            "Import from Authy",
            "Here's how to transfer your accounts from Authy to Stratum",
            $content
        );
    }

    public function blizzardAuthenticator(): Response
    {
        $content = $this->gitHubService->getWikiPage("Importing-from-Blizzard-Authenticator.md");
        return $this->page(
            "Import from Blizzard Authenticator",
            "Here's how to transfer your accounts from Blizzard Authenticator to Stratum",
            $content
        );
    }

    public function steam(): Response
    {
        $content = $this->gitHubService->getWikiPage("Importing-from-Steam.md");
        return $this->page(
            "Import from Steam",
            "Here's how to transfer your accounts from Steam mobile app to Stratum",
            $content
        );
    }

    private function page(string $title, string $description, string $markdown): Response
    {
        $viewData["title"] = $title;
        $viewData["description"] = $description;
        $viewData["content"] = $this->markdownRenderer->render($markdown);

        $res = new PageResponse();
        $res->meta->title = $title;
        $res->meta->description = $description;
        $res->viewData = $viewData;
        $res->setView("wiki/page.twig");
        return $res;
    }
}
