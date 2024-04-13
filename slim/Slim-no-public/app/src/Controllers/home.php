<?php
namespace SAE_401\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig as Twig;


class Home {
    public function doIt(Request $request, Response $response, array $args) {
    
        $bdd = new \SAE_401\Services\ViaPdo\Bdd();
        $pdo = $bdd->connect;
        $stmt = $pdo->prepare('SELECT * FROM files ORDER BY id DESC');
        $stmt->execute();
        $files = $stmt->fetchAll();
    
        // Convertissez chaque fichier en base64
        foreach ($files as &$file) {
            $file['file'] = base64_encode($file['file']);
        }
    
        $view = Twig::fromRequest($request);
        return $view->render($response, 'home.html', ['files' => $files]);
    }
}