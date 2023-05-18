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
        $res->meta->description = "test";
        $res->setView("wiki/index.twig");
        return $res;
    }

    public function faq(): Response
    {
        return $this->page("Frequently-Asked-Questions", "F.A.Q.", "Questions and answers for frequently asked questions");
    }

    public function googleAuthenticator(): Response
    {
        return $this->page("Importing-from-Google-Authenticator", "Import from Google Authenticator", "Here's how to transfer your accounts from Google Authenticator to Authenticator Pro");
    }

    public function authy(): Response
    {
        return $this->page("Importing-from-Authy", "Import from Authy", "Here's how to transfer your accounts from Authy to Authenticator Pro");
    }

    public function blizzardAuthenticator(): Response
    {
        return $this->page("Importing-from-Blizzard-Authenticator", "Import from Blizzard Authenticator", "Here's how to transfer your accounts from Blizzard Authenticator to Authenticator Pro");
    }

    public function steam(): Response
    {
        return $this->page("Importing-from-Steam", "Import from Steam", "Here's how to transfer your accounts from Steam mobile app to Authenticator Pro");
    }

    private function page(string $fileName, string $title, string $description): Response {
        $viewData["title"] = $title;
        $viewData["description"] = $description;
        $viewData["content"] = $this->getWikiPage($fileName);

        $res = new PageResponse();
        $res->meta->title = $title;
        $res->meta->description = $description;
        $res->viewData = $viewData;
        $res->setView("wiki/page.twig");
        return $res;
    }

    private function getWikiPage(string $fileName): string {
        $redis = new Redis();
        $redis->connect(REDIS_HOST, REDIS_PORT);

        $key = "wiki:$fileName";
        $value = $redis->get($key);

        if ($value === false) {
            $url = "https://raw.githubusercontent.com/wiki/jamie-mh/AuthenticatorPro/$fileName.md";
            $markdown = file_get_contents($url);
            $value = $this->_markdownRenderer->render($markdown);

            $redis->set($key, $value);
            $redis->expire($key, 86400);
        }

        return $value;
    }
}