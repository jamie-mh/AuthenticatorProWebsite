<?php
// Copyright (C) 2023 jmh
// SPDX-License-Identifier: GPL-3.0-only

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

define("RECAPTCHA_SITE_KEY", $_ENV["RECAPTCHA_SITE_KEY"]);
define("RECAPTCHA_SECRET", $_ENV["RECAPTCHA_SECRET"]);

define('EMAIL_HOST', $_ENV['EMAIL_HOST']);
define('EMAIL_PORT', $_ENV['EMAIL_PORT']);
define('EMAIL_USERNAME', $_ENV['EMAIL_USERNAME']);
define('EMAIL_PASSWORD', $_ENV['EMAIL_PASSWORD']);
define('EMAIL_RECIPIENT', $_ENV['EMAIL_RECIPIENT']);

define("REDIS_HOST", $_ENV["REDIS_HOST"]);
define("REDIS_PORT", $_ENV["REDIS_PORT"]);
