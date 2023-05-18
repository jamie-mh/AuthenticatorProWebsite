<?php

define("ENVIRONMENT", $_ENV["APP_ENV"]);

if ($_ENV["APP_ENV"] === "dev") {
    define("IS_DEV", true);
    define("PROTOCOL", "http");
    define("DOMAIN_NAME", "localhost:8080");
} else {
    define("IS_DEV", false);
    define("PROTOCOL", "https");
    define("DOMAIN_NAME", "authenticatorpro.jmh.me");
}

define("REDIS_HOST", $_ENV["REDIS_HOST"]);
define("REDIS_PORT", $_ENV["REDIS_PORT"]);
