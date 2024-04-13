<?php
namespace SAE_401\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class deconnexion {
    public function doIt(Request $request, Response $response, array $args) {
        session_destroy();
        $response = $response->withHeader('Location', '/home');
        return $response->withStatus(302);
    }
}
