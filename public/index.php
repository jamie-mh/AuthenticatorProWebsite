<?php
// Copyright (C) 2023 jmh
// SPDX-License-Identifier: GPL-3.0-only

use AuthPro\Core\Application;
use Dotenv\Dotenv;

define("ROOT", dirname(__DIR__) . "/");
define("APP", ROOT . "application/");

require_once ROOT . "vendor/autoload.php";

$dotenv = Dotenv::createImmutable(ROOT);
$dotenv->load();

require_once APP . "Core/_env.php";
require_once APP . "Core/_routes.php";

if (IS_DEV) {
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
}

(new Application())->run();
