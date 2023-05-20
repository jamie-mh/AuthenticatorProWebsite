<?php

namespace AuthPro\Controller;

use AuthPro\Core\Controller;
use AuthPro\Core\Response;
use AuthPro\Core\Response\PageResponse;

class ToolsController extends Controller
{
    public function index(): Response
    {
        $res = new PageResponse();
        $res->meta->title = "Tools";
        $res->meta->description = "Useful tools for Authenticator Pro";
        $res->setView("tools/index.twig");
        return $res;
    }
}
