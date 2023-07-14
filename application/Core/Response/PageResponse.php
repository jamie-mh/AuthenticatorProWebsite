<?php
// Copyright (C) 2023 jmh
// SPDX-License-Identifier: GPL-3.0-only

namespace AuthPro\Core\Response;

use AuthPro\Core\Response;
use AuthPro\Entity\DocumentMeta;
use Exception;
use Twig\Environment;
use Twig\Error\Error;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class PageResponse implements Response
{
    private readonly Environment $twig;
    private readonly array $assetMap;

    public DocumentMeta $meta;
    public array $viewData;

    private string $viewPath;
    private int $statusCode;

    public function __construct()
    {
        $this->statusCode = Response::STATUS_OK;
        $this->meta = new DocumentMeta();
        $this->viewData = [];

        $loader = new FilesystemLoader(APP . "View");
        $this->twig = new Environment(
            $loader,
            IS_DEV
                ? [
                "auto_reload" => true,
                "debug" => true
            ]
                : []
        );

        $this->twig->addGlobal("meta", $this->meta);
        $this->twig->addGlobal("session", $_SESSION);
        $this->addAssetHashFunction();

        if (IS_DEV) {
            $this->twig->addExtension(new DebugExtension());
        } else {
            $assetMapContents = file_get_contents(APP . "assets.json");
            $this->assetMap = json_decode($assetMapContents, true, 2, JSON_THROW_ON_ERROR);
        }
    }

    private function addAssetHashFunction(): void
    {
        $this->twig->addFunction(
            new TwigFunction("asset_hash", function (string $name) {
                return IS_DEV ? $name : $this->assetMap[$name];
            })
        );
    }

    public function render(): void
    {
        if (!isset($this->viewPath)) {
            return;
        }

        try {
            echo $this->twig->render("_shared/header.twig");
            echo $this->twig->render($this->viewPath, $this->viewData);
            echo $this->twig->render("_shared/footer.twig");
        } catch (Error $e) {
            self::serverError($e)->render();
        }
    }

    public function setView(string $path): void
    {
        $this->viewPath = $path;
    }

    private static function getErrorResponse(int $statusCode): PageResponse
    {
        $res = new PageResponse();
        $res->setStatusCode($statusCode);
        $res->setView("error.twig");

        $message = self::STATUS_MESSAGES[$res->getStatusCode()];
        $res->meta->title = $message;
        $res->viewData["message"] = $message;
        $res->viewData["code"] = $res->getStatusCode();

        return $res;
    }

    public static function notFound(): Response
    {
        return self::getErrorResponse(self::STATUS_NOT_FOUND);
    }

    public static function serverError(Exception $exception): Response
    {
        $res = self::getErrorResponse(self::STATUS_SERVER_ERROR);

        if (IS_DEV) {
            $res->viewData["exception"] = $exception;
        }

        return $res;
    }

    public static function methodNotAllowed(): Response
    {
        return self::notFound();
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode($statusCode): void
    {
        $this->statusCode = $statusCode;
    }
}
