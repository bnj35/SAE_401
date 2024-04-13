<?php
namespace SAE_401\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig as Twig;

class Login{
    public function doIt(Request $request, Response $response, ): Response{
        $view = Twig::fromRequest($request);
        return $view->render($response,'login.html',[
            // 'session'=>$_SESSION,
            // 'stats'=>$stats
        ]) ;
    }
}