<?php
namespace SAE_401\Services;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig as Twig;


class ServiceAjouterMessage {
    public function doIt(Request $request, Response $response, array $args) {
        $data = $request->getParsedBody();
        if (!isset($data['title'], $data['description'])) {
            $response->getBody()->write('Missing title, description or file');
            return $response->withStatus(400);
        }
        $title = $data['title'];
        $description = $data['description'];

        $files = $request->getUploadedFiles();
        if (!isset($files['blob'])) {
            $response->getBody()->write('No file uploaded');
            return $response->withStatus(400);
        }

        $file = $files['blob'];
        if ($file->getError() !== UPLOAD_ERR_OK) {
            $response->getBody()->write('Failed to upload file');
            return $response->withStatus(500);
        }

        // Move the uploaded file to a temporary location
        $tmpPath = sys_get_temp_dir() . '/' . $file->getClientFilename();
        $file->moveTo($tmpPath);

        // Read the contents of the file
        $fileContent = file_get_contents($tmpPath);

        // Delete the temporary file
        unlink($tmpPath);

        if (isset($_SESSION['id'])) {
            $id_user = $_SESSION['id'];
        } else {
            // Handle the case where the user is not logged in
            $response->getBody()->write('User is not logged in');
            return $response->withStatus(401);
        }

        $bdd = new \SAE_401\Services\ViaPdo\Bdd();
        $pdo = $bdd->connect;

        // Prepare the SQL statement
        $stmt = $pdo->prepare('INSERT INTO files (title, description, file, id_user) VALUES (?, ?, ?, ?)');

        // Execute the statement with the user data
        $stmt->execute([$title, $description, $fileContent, $id_user]);

        $response = $response->withHeader('Location', '/dashboard');
        return $response->withStatus(302);
    }
}