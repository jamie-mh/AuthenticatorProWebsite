<?php
// Copyright (C) 2023 jmh
// SPDX-License-Identifier: GPL-3.0-only

namespace AuthPro\Controller;

use AuthPro\Core\Controller;
use AuthPro\Core\Response;
use AuthPro\Core\Response\PageResponse;

class DownloadController extends Controller
{
    public function index(): Response
    {
        $res = new PageResponse();
        $res->meta->title = "Download";
        $res->meta->description = "Download the Authenticator Pro app for free";
        $res->setView("download/index.twig");
        return $res;
    }
}
