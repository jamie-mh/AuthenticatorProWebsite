<?php
// Copyright (C) 2023 jmh
// SPDX-License-Identifier: GPL-3.0-only

namespace Stratum\Controller;

use Stratum\Core\Controller;
use Stratum\Core\Response;
use Stratum\Core\Response\PageResponse;
use Stratum\Service\GitHubService;

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
        $res->meta->description = "Download the open-source Stratum app for free";
        $res->setView("download/index.twig");
        $res->viewData = ["latestTag" => $latestTag];
        return $res;
    }
}
