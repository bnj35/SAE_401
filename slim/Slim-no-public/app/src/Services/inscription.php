<?php
namespace SAE_401\Services;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;



class inscription {
    public function doIt(Request $request, Response $response, array $args) {
        $data = $request->getParsedBody();
        if (!isset($data['pseudo'], $data['password'])) {

            $response->getBody()->write('Missing pseudo or password');
            return $response->withStatus(400);
        }
        $pseudo = $data['pseudo'];
        $password = $data['password'];
        $bdd = new \SAE_401\Services\ViaPdo\Bdd();
        $pdo = $bdd->connect;
    
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
        $stmt = $pdo->prepare('INSERT INTO User (pseudo, password) VALUES (?, ?)');
        $stmt->execute([$pseudo, $hashedPassword]);
    
        $userId = $pdo->lastInsertId();
    
        $_SESSION['id'] = $userId;
        $_SESSION['is_connected'] = true;
    
        $response->getBody()->write('Inscription réussie');
        return $response->withHeader('Location', '/dashboard');
    }
};