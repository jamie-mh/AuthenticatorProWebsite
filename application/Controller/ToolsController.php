<?php
// Copyright (C) 2023 jmh
// SPDX-License-Identifier: GPL-3.0-only

namespace Stratum\Controller;

use Stratum\Core\Controller;
use Stratum\Core\Response;
use Stratum\Core\Response\PageResponse;

class ToolsController extends Controller
{
    public function index(): Response
    {
        $res = new PageResponse();
        $res->meta->title = "Tools";
        $res->meta->description = "Useful tools for Stratum";
        $res->setView("tools/index.twig");
        return $res;
    }
}
