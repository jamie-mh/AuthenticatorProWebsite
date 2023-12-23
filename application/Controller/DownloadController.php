<?php
// Copyright (C) 2023 jmh
// SPDX-License-Identifier: GPL-3.0-only

namespace AuthPro\Controller;

use AuthPro\Core\Controller;
use AuthPro\Core\Response;
use AuthPro\Core\Response\PageResponse;
use AuthPro\Service\GitHubService;

class DownloadController extends Controller
{
    private readonly GitHubService $gitHubService;

    public function __construct()
    {
        $this->gitHubService = new GitHubService();
    }

    public function index(): Response
    {
        $latestTag = $this->gitHubService->getLatestReleaseTag();

        $res = new PageResponse();
        $res->meta->title = "Download";
        $res->meta->description = "Download the open-source Authenticator Pro app for free";
        $res->setView("download/index.twig");
        $res->viewData = ["latestTag" => $latestTag];
        return $res;
    }
}
