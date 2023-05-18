<?php

namespace AuthPro\Controller;

use AuthPro\Core\Controller;
use AuthPro\Core\Response;
use AuthPro\Core\Response\PageResponse;

class HomeController extends Controller
{
    public function index(): Response
    {
        $res = new PageResponse();
        $res->meta->title = "test";
        $res->meta->description = "test";
        $res->setView("home/index.twig");
        return $res;
    }
}