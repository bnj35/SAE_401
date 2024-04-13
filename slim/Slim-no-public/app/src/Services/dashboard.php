<?php 
namespace SAE_401\Services;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig as Twig;

class dashboard {
    public function doIt(Request $request, Response $response, array $args) {
    
        $userId = $_SESSION['id'];
    
        $bdd = new \SAE_401\Services\ViaPdo\Bdd();
        $pdo = $bdd->connect;
        $stmt = $pdo->prepare('SELECT * FROM files WHERE id_user = :userId ORDER BY id DESC');
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        $files = $stmt->fetchAll();
    
        // Convertissez chaque fichier en base64
        foreach ($files as &$file) {
            $file['file'] = base64_encode($file['file']);
        }
    
        $view = Twig::fromRequest($request);
        return $view->render($response, 'dashboard.html', ['files' => $files]);
    }
}