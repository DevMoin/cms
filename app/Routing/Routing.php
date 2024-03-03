<?php

namespace App\Routing;

use Error;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\HttpFoundation\Request;

class Routing
{
    private static $routes = [];
    public static $currentRoute;


    public static function register(Route $route)
    {
        if (isset(self::$routes[$route->name])) {
            $otherRoute = self::$routes[$route->name];
            die("Can't register route <b>{$route->name}</b> from <b>{$route->file}</b> because it is already registered from <b>{$otherRoute->file}</b>");
            exit;
        }
        self::$routes[$route->name] = $route;
    }

    public static function handle(Request $request)
    {
        $routeMatches = [];
        foreach (self::$routes as $route) { // Route
            if ($route->matches($request)) {
                $routeMatches[] = $route;
                // return $route->execute($request);
            }
        }

        // Assuming your routing system checks if a route matches the request
        // If no route matches, call the ErrorController's notFound method
        if (!$routeMatches) {
            // Create a new ErrorController instance
            $errorController = new \Controller\ErrorController();
            // Handle the request
            $request = Request::createFromGlobals();
            $errorController->notFound();
        } else {
            $route = $routeMatches[0];
            self::$currentRoute = $route;
            $route->execute($request);
        }
    }

    public static function exists($name)
    {
        foreach (self::$routes as $route) {
            if ($route->getName() === $name) {
                return true;
            }
        }
        return false;
    }

    public static function loadRoutesFromFile($filePath)
    {
        $routes = Yaml::parseFile($filePath);
        foreach ($routes as $route) {
            self::register(new Route($route['name'], $route['path'], $route['controller'], $route['action'], $filePath, $route));
        }
    }


    public static function getCurrentRoute()
    {
        return self::$currentRoute;
    }
}
