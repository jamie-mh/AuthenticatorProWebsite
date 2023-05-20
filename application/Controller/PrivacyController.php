<?php

namespace AuthPro\Controller;

use AuthPro\Core\Controller;
use AuthPro\Core\Response;
use AuthPro\Core\Response\PageResponse;

class PrivacyController extends Controller
{
    public function index(): Response
    {
        $res = new PageResponse();
        $res->meta->title = "Privacy Policy";
        $res->meta->description = "Authenticator Pro Privacy Policy";
        $res->setView("privacy/index.twig");
        return $res;
    }
}
