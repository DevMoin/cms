<?php

namespace Controller;

use App\Routing\Route;
use Symfony\Component\HttpFoundation\Request;

class HomeController{
    public function index(Request $request, Route $route){
        echo \App\Twiger::render('index', ['urlParams'=>$route->parameters]);
    }
}