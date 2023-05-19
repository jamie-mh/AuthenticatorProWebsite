<?php

namespace AuthPro\Controller;

use AuthPro\Core\Controller;
use AuthPro\Core\Response;
use AuthPro\Core\Response\PageResponse;
use AuthPro\Util\MarkdownRenderer;
use Redis;

class WikiController extends Controller
{
    private readonly MarkdownRenderer $_markdownRenderer;

    public function __construct()
    {
        $this->_markdownRenderer = new MarkdownRenderer();
    }

    public function index(): Response
    {
        $res = new PageResponse();
        $res->meta->title = "Wiki";
        $res->meta->description = "Welcome to the Authenticator Pro wiki";
        $res->setView("wiki/index.twig");
        return $res;
    }

    public function faq(): Response
    {
        $content = $this->getWikiPage("Frequently-Asked-Questions.md");
        return $this->page("F.A.Q.", "Questions and answers for frequently asked questions", $content);
    }

    public function backupFormat(): Response
    {
        $content = $this->getMarkdownPage("doc/BACKUP_FORMAT.md");
        return $this->page("Backup Format", "File format and encryption for Authenticator Pro backup files", $content);
    }

    public function googleAuthenticator(): Response
    {
        $content = $this->getWikiPage("Importing-from-Google-Authenticator.md");
        return $this->page("Import from Google Authenticator", "Here's how to transfer your accounts from Google Authenticator to Authenticator Pro", $content);
    }

    public function authy(): Response
    {
        $content = $this->getWikiPage("Importing-from-Authy.md");
        return $this->page("Import from Authy", "Here's how to transfer your accounts from Authy to Authenticator Pro", $content);
    }

    public function blizzardAuthenticator(): Response
    {
        $content = $this->getWikiPage("Importing-from-Blizzard-Authenticator.md");
        return $this->page("Import from Blizzard Authenticator", "Here's how to transfer your accounts from Blizzard Authenticator to Authenticator Pro", $content);
    }

    public function steam(): Response
    {
        $content = $this->getWikiPage("Importing-from-Steam.md");
        return $this->page("Import from Steam", "Here's how to transfer your accounts from Steam mobile app to Authenticator Pro", $content);
    }

    private function page(string $title, string $description, string $content): Response {
        $viewData["title"] = $title;
        $viewData["description"] = $description;
        $viewData["content"] = $content;

        $res = new PageResponse();
        $res->meta->title = $title;
        $res->meta->description = $description;
        $res->viewData = $viewData;
        $res->setView("wiki/page.twig");
        return $res;
    }

    private function getWikiPage(string $fileName): string {
        $url = "https://raw.githubusercontent.com/wiki/jamie-mh/AuthenticatorPro/$fileName";
        return $this->getMarkdownFile($url);
    }

    private function getMarkdownPage(string $filePath): string {
        $url = "https://raw.githubusercontent.com/jamie-mh/AuthenticatorPro/master/$filePath";
        $content = $this->getMarkdownFile($url);
        return preg_replace("#<h1>(.*?)</h1>#", "", $content);
    }

    private function getMarkdownFile(string $url) {
        $redis = new Redis();
        $redis->connect(REDIS_HOST, REDIS_PORT);

        $key = "wiki:$url";
        $value = $redis->get($key);

        if ($value === false) {
            $markdown = file_get_contents($url);
            $value = $this->_markdownRenderer->render($markdown);

            $redis->set($key, $value);
            $redis->expire($key, 86400);
        }

        return $value;
    }
}