<?php
// Copyright (C) 2023 jmh
// SPDX-License-Identifier: GPL-3.0-only

namespace AuthPro\Controller;

use AuthPro\Core\Controller;
use AuthPro\Core\Response;
use AuthPro\Core\Response\SitemapResponse;
use AuthPro\Entity\SitemapItem;

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
