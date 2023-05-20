<?php

namespace AuthPro\Core;

use Exception;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Respect\Validation\Factory;

use function FastRoute\simpleDispatcher;


readonly class Application
{
    private Dispatcher $_dispatcher;

    public function __construct()
    {
        $this->_dispatcher = simpleDispatcher(function (RouteCollector $collector) {
            foreach (ROUTES as $prefix => $def) {
                $collector->addGroup($prefix, function (RouteCollector $groupCollector) use ($prefix) {
                    foreach (ROUTES[$prefix]["routes"] as $route) {
                        $groupCollector->addRoute($route[0], $route[1], $route);
                    }
                });
            }
        });

        Factory::setDefaultInstance(
            (new Factory())
                ->withRuleNamespace("AuthPro\\Core\\Validation\\Rules")
                ->withExceptionNamespace("AuthPro\\Core\\Validation\\Exceptions")
        );
    }

    public function run(): void
    {
        session_start();
        $uri = $_SERVER["REQUEST_URI"];

        // Strip query string (?foo=bar) and decode URI
        if (($pos = strpos($uri, "?")) !== false) {
            $uri = substr($uri, 0, $pos);
        }

        $uri = rawurldecode($uri);

        // Remove last slash
        if (strrpos($uri, "/") === ($lengthWithoutSlash = strlen($uri) - 1)) {
            $uri = substr($uri, 0, $lengthWithoutSlash);
        }

        $this->loadRoute($_SERVER["REQUEST_METHOD"], $uri);
    }

    private static function getPrefix(string $uri): string
    {
        $prefix = explode("/", substr($uri, 1))[0];

        if ($prefix === "") {
            return "";
        }

        $prefix = "/" . $prefix;

        if (!isset(ROUTES[$prefix])) {
            return "";
        }

        return $prefix;
    }

    private function loadRoute(string $httpMethod, string $uri): void
    {
        $dispatchInfo = $this->_dispatcher->dispatch($httpMethod, $uri);
        $prefix = self::getPrefix($uri);
        $responseObj = ROUTES[$prefix]["response"];

        switch ($dispatchInfo[0]) {
            case Dispatcher::METHOD_NOT_ALLOWED:
                $response = $responseObj::methodNotAllowed();
                break;

            case Dispatcher::FOUND:
                $response = $this->getResponse($dispatchInfo[1], $responseObj, $dispatchInfo[2]);
                break;

            default:
            case Dispatcher::NOT_FOUND:
                $response = $responseObj::notFound();
                break;
        }

        http_response_code($response->getStatusCode());
        $response->render();
    }

    private function getResponse(array $route, string $responseObj, array $args): Response
    {
        if (isset($route[4])) {
            if (is_array($route[4])) {
                $args = array_merge($route[4], $args);
            } else {
                array_unshift($args, $route[4]);
            }
        }

        try {
            return call_user_func_array([new $route[2](), $route[3]], array_values($args));
        } catch (Exception $e) {
            error_log($e);
            return $responseObj::serverError($e);
        }
    }
}