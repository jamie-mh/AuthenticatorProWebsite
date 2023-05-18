<?php

namespace AuthPro\Controller;

use AuthPro\Core\Controller;
use AuthPro\Core\Response;
use AuthPro\Core\Response\SitemapResponse;
use AuthPro\Entity\SitemapItem;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $res = new SitemapResponse();
        $items = [
            new SitemapItem("/", "monthly", 1.0),
        ];

        $res->setItems($items);
        return $res;
    }
}