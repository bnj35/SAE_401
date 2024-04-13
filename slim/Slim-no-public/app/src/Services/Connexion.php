<?php
namespace SAE_401\Services;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;



class Connexion {
    public function doIt(Request $request, Response $response, array $args) {
        $data = $request->getParsedBody();
        if (!isset($data['pseudo'], $data['password'])) {
        // handle the error, for example by returning a response with an error message
        $response->getBody()->write('Missing pseudo or password');
        return $response->withStatus(400);
        }
        $pseudo = $data['pseudo'];
        $password = $data['password'];
        $bdd = new \SAE_401\Services\ViaPdo\Bdd();
        $pdo = $bdd->connect;
        $stmt = $pdo->prepare('SELECT * FROM User WHERE pseudo = ?');
        $stmt->execute([$pseudo]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // L'utilisateur est connecté, vous pouvez stocker ses informations dans la session
            $_SESSION['id'] = $user['Id'];
            $response->getBody()->write('Connexion réussie');
            $_SESSION['is_connected']=true;
            $response = $response->withHeader('Location', '/dashboard');
        }
        else {
            // L'utilisateur n'est pas connecté, vous pouvez rediriger vers la page de connexion
            $response->getBody()->write('Connexion échouée, <a href="/login">réessayer</a>');
            // $response = $response->withHeader('Location', '/login');
        }

        return $response;
    }
}