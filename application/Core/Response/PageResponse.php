<?php

namespace AuthPro\Core\Response;

use AuthPro\Core\Response;
use AuthPro\Entity\DocumentMeta;
use Exception;
use Twig\Environment;
use Twig\Error\Error;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

class PageResponse implements Response
{
    private readonly Environment $_twig;

    public DocumentMeta $meta;
    public array $viewData;

    private string $_viewPath;
    private int $_statusCode;

    public function __construct()
    {
        $this->_statusCode = Response::STATUS_OK;
        $this->meta = new DocumentMeta();
        $this->viewData = [];

        $loader = new FilesystemLoader(APP . "View");
        $this->_twig = new Environment(
            $loader,
            IS_DEV
                ? [
                "auto_reload" => true,
                "debug" => true
            ]
                : []
        );

        $this->_twig->addGlobal("meta", $this->meta);
        $this->_twig->addGlobal("session", $_SESSION);

        if (IS_DEV) {
            $this->_twig->addExtension(new DebugExtension());
        }
    }

    public function render(): void
    {
        if (!isset($this->_viewPath)) {
            return;
        }

        try {
            echo $this->_twig->render("_shared/header.twig");
            echo $this->_twig->render($this->_viewPath, $this->viewData);
            echo $this->_twig->render("_shared/footer.twig");
        } catch (Error $e) {
            self::serverError($e)->render();
        }
    }

    public function setView(string $path): void
    {
        $this->_viewPath = $path;
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
        return $this->_statusCode;
    }

    public function setStatusCode($statusCode): void
    {
        $this->_statusCode = $statusCode;
    }
}