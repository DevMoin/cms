<?php

use App\Routing\Routing;
use App\Routing\Route;
use App\Twiger;

class App
{
    public static $hooks;
    public static $parameters;


    public static function init()
    {
        if (!file_exists(APP_CONFIG_PATH . "db.php")) {
            self::runSetupPage();
        }

        require_once APP_CONFIG_PATH . "db.php";

        if (!\App\DB::$conn) {
            die("Failed to connect to db ");
        }

        self::loadRoutesFromYaml(['basic.yml']);
        Twiger::init();
    }

    public static function getParameters()
    {
        return self::$parameters;
    }

    public static function setParameters($parameters)
    {
        self::$parameters = $parameters;
    }


    public static function redirectTo($path)
    {
        header("location: $path");
        exit;
    }

    public static function runSetupPage()
    {
        self::redirectTo("install.php");
        exit;
    }



    public static function loadRoutesFromYaml($files)
    {
        foreach ($files as $yamlFile) {
            $routes = Symfony\Component\Yaml\Yaml::parseFile(ROUTES_PATH . $yamlFile);
            // s($routes);
            foreach ($routes as $routeName => $routeInfo) {

                //defaults
                $routeInfo = array_merge(
                    [
                        // 'name' => "Untitle"
                    ],
                    $routeInfo,
                );

                $route = new Route(
                    $routeName,
                    $routeInfo['path'],
                    $routeInfo['controller'],
                    $routeInfo['action'],
                    basename($yamlFile),
                    $routeInfo
                );
                Routing::register($route);
            }
        }
    }

    public static function getCurrentRoute()
    {
        return Routing::getCurrentRoute();
    }
}
