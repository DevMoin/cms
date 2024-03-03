<?php

namespace App\Routing;

use Symfony\Component\HttpFoundation\Request;

class Route
{
    public $path;
    public $controller;
    public $action;
    public $name;
    public $file;
    public $fullConfig;
    public $parameters;

    public function __construct($name, $path, $controller, $action, $file, $fullConfig)
    {
        $this->path = $path;
        $this->controller = $controller;
        $this->action = $action;
        $this->name = $name;
        $this->file = $file;
        $this->fullConfig = $fullConfig;
    }

    public function matches(Request $request)
    {
        // Get the path info from the request
        $requestPath = $request->getPathInfo();

        // Prepare a regular expression pattern based on the route path
        $pattern = '~^' . preg_replace('/{([^\/]+)}/', '(?<$1>[^/]+)', $this->path) . '$~';

        // Perform a regular expression match
        if (preg_match($pattern, $requestPath, $matches)) {
            // If a match is found, extract captured parameters
            $this->parameters = array_intersect_key($matches, array_flip(array_filter(array_keys($matches), 'is_string')));
            \App::setParameters($this->parameters);
            return true;
        }

        return false;
    }

    public function execute(Request $request)
    {
        // Logic to execute the controller action associated with this route
        // You need to implement this based on your application's requirements
        // For example, you might instantiate the controller and call the action method
        $controller = new $this->controller();
        if(method_exists($controller, $this->action)){
            // return ->{$this->action}($request);
            call_user_func([$controller, $this->action], $request, $this);
        }else{
            die("Unkown method <b>{$this->action}</b> in <b>{$this->controller}</b>");
        }
    }

    public function getName()
    {
        return $this->name;
    }
}
