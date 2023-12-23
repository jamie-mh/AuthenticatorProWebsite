<?php
// Copyright (C) 2023 jmh
// SPDX-License-Identifier: GPL-3.0-only

namespace AuthPro\Controller;

use AuthPro\Core\Controller;
use AuthPro\Core\Response;
use AuthPro\Core\Response\PageResponse;

class HomeController extends Controller
{
    public function index(): Response
    {
        $res = new PageResponse();
        $res->meta->title = "Open-source two-factor authentication app";
        $res->meta->description = "Authenticator Pro is a free and open-source two-factor authentication app for Android. It features encrypted backups, icons, categories, a high level of customisation and even a Wear OS app.";
        $res->setView("home/index.twig");
        return $res;
    }
}
