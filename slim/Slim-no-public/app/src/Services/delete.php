<?php
namespace SAE_401\Services;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

    class Delete {
        public function doIt(Request $request, Response $response, array $args) {
            $id = $args['id'];  
    
            $bdd = new \SAE_401\Services\ViaPdo\Bdd();
            $pdo = $bdd->connect;
            $stmt = $pdo->prepare('DELETE FROM files WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $response->withHeader('location','/dashboard');
        }
    }

