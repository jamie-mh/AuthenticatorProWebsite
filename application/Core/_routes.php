<?php

/*
    -- Route format --
    0: request methods (string or array)
    1: path expression
    2: handler class path
    3: handler method name
    4: handler args (optional)
*/

use AuthPro\Controller\HomeController;
use AuthPro\Controller\SitemapController;
use AuthPro\Core\Response\PageResponse;


const ROUTES = [
    "" => [
        "response" => PageResponse::class,
        "routes" => [
            ["GET", "", HomeController::class, "index"],
            ["GET", "/sitemap.xml", SitemapController::class, "index"]
        ]
    ]
];
