<?php
// Copyright (C) 2023 jmh
// SPDX-License-Identifier: GPL-3.0-only

namespace Stratum\Controller;

use Stratum\Core\Controller;
use Stratum\Core\Response;
use Stratum\Core\Response\SitemapResponse;
use Stratum\Entity\SitemapItem;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $items = [];

        foreach (ROUTES[""]["routes"] as $route) {
            if ($route[0] !== "GET") {
                continue;
            }

            $items [] = new SitemapItem($route[1], "weekly", 1.0);
        }

        $res = new SitemapResponse();
        $res->setItems($items);

        return $res;
    }
}
