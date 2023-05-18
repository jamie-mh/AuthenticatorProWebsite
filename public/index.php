<?php

use AuthPro\Core\Application;
use Dotenv\Dotenv;

define("ROOT", dirname(__DIR__) . "/");
define("APP", ROOT . "application/");

require ROOT . "vendor/autoload.php";

$dotenv = Dotenv::createImmutable(ROOT);
$dotenv->load();

require APP . "Core/_env.php";
require APP . "Core/_routes.php";

if (IS_DEV) {
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
}

(new Application())->run();
