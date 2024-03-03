<?php

use App\Routing\Routing;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once "load.env.php"; // Composer autoloader
require_once "vendor/autoload.php"; // Composer autoloader

if (!defined("BASE_PATH")) {
    die("BASE_PATH constant missing in load.env.php file e.g. define('BASE_PATH',  'http://localhost/cms');");
    exit;
}

\App::init();
// Handle incoming request
$request = Symfony\Component\HttpFoundation\Request::createFromGlobals();
Routing::handle($request);
