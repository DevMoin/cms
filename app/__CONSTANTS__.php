<?php

define("ROOT_PATH", realpath(__DIR__ . "/..") . "/");
l__ | define("APP_PATH", ROOT_PATH . "app/");
l__ | define("APP_CONFIG_PATH", APP_PATH . "config/");
l__ | define("APP_TMP_PATH", APP_PATH . "tmp/");
l___o___ | define("TWIG_CACHE_PATH", APP_TMP_PATH . "twig-cache/");
l___o___ | define("APP___SETUP_PATH", APP_PATH . "setup/");
l___o____o__ | define("APP___SETUP__TABLES_PATH", APP___SETUP_PATH . "setup/");
l__ | define("WEB_PATH", ROOT_PATH . "WEB/");
l___o___ | define("CONTROLLER_PATH", WEB_PATH . "Controller/");
l___o___ | define("ROUTES_PATH", WEB_PATH . "Routes/");
l___o___ | define("VIEWS_PATH", WEB_PATH . "Views/");
