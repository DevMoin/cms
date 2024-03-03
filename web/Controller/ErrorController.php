<?php

namespace Controller;

use Symfony\Component\HttpFoundation\Response;

class ErrorController {
    public function notFound() {
        $four04 = \App\Twiger::render('404');
        // Create a Response object with the appropriate content and status code
        $response = new Response($four04, Response::HTTP_NOT_FOUND);
        // // Send the response
        $response->send();
    }
}