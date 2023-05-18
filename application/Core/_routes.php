<?php

/*
    -- Route format --
    0: request methods (string or array)
    1: path expression
    2: handler class path
    3: handler method name
    4: handler args (optional)
*/

use AuthPro\Controller\FeedbackController;
use AuthPro\Controller\HomeController;
use AuthPro\Controller\SitemapController;
use AuthPro\Controller\WikiController;
use AuthPro\Core\Response\PageResponse;


const ROUTES = [
    "" => [
        "response" => PageResponse::class,
        "routes" => [
            ["GET", "", HomeController::class, "index"],
            ["GET", "/wiki", WikiController::class, "index"],
            ["GET", "/wiki/faq", WikiController::class, "faq"],
            ["GET", "/wiki/import-from-google-authenticator", WikiController::class, "googleAuthenticator"],
            ["GET", "/wiki/import-from-authy", WikiController::class, "authy"],
            ["GET", "/wiki/import-from-blizzard-authenticator", WikiController::class, "blizzardAuthenticator"],
            ["GET", "/wiki/import-from-steam", WikiController::class, "steam"],
            ["GET", "/feedback", FeedbackController::class, "index"],
            ["POST", "/feedback", FeedbackController::class, "submit"],
            ["GET", "/sitemap.xml", SitemapController::class, "index"]
        ]
    ]
];
