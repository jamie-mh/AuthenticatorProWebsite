<?php
// Copyright (C) 2023 jmh
// SPDX-License-Identifier: GPL-3.0-only

namespace Stratum\Controller;

use Stratum\Core\Controller;
use Stratum\Core\Response;
use Stratum\Core\Response\PageResponse;

class PrivacyController extends Controller
{
    public function index(): Response
    {
        $res = new PageResponse();
        $res->meta->title = "Privacy Policy";
        $res->meta->description = "Stratum Privacy Policy";
        $res->setView("privacy/index.twig");
        return $res;
    }
}
