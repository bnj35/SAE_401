<?php

session_start();

$_SESSION['is_connected']=false;

require __DIR__ . '/vendor/autoload.php';

use Slim\Factory\AppFactory;
use Slim\Views\Twig as Twig;
use Slim\Views\TwigMiddleware as TwigMiddleware;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Psr\Http\Server\RequestHandlerInterface as RequestHandler;





$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response) {
    return $response->withHeader('location','home');
    return $response;   
});


//twig
//on crée une instance de moteur
$twig =Twig::create(__DIR__.'/templates',['cache'=>false, 'debug'=>true, 'strict_variables'=>true]);
/*ce qui suit est obligatoire pour que chaque requète http soit décorée par 'view', une variable d'instance injectée sur chaque requête.
le nom de cette variable d'instance peut être passé en paramçtre de twigMiddleware::create sous forme de chaine de caractères si on veut
autre choses que 'view'
*/


$app->add(TwigMiddleware::create($app,$twig));

$app->add(function (Request $request, RequestHandler $handler){
    $response = $handler->handle($request);
    return $response->withHeader('Access-control-allow-origin', '*');
});

$app->get('/formulaire',\SAE_401\Controllers\Formulaire::class.':doIt')
->setName('formulaire');

$app->get('/login',\SAE_401\Controllers\Login::class.':doIt')
->setName('login');


$app->get('/home',\SAE_401\Controllers\Home::class.':doIt')
->setName('home');

$app->get('/register',\SAE_401\Controllers\register::class.':doIt')
->setName('register');

$app->post('/connexion', \SAE_401\Services\Connexion::class.':doIt')
    ->setName('connexion');

    $app->post('/inscription', \SAE_401\Services\inscription::class.':doIt')
    ->setName('inscription');

    $app->post('/ServiceAjouterMessage', \SAE_401\Services\ServiceAjouterMessage::class.':doIt')
    ->setName('ServiceAjouterMessage');

$app->get('/dashboard',\SAE_401\Services\dashboard::class.':doIt')
->setName('dashboard');

$app->get('/deconnexion',\SAE_401\Controllers\deconnexion::class.':doIt')
->setName('deconnexion');


$app->get('/assets/{path:.*}', function ($request, $response, $args) {
    $file = __DIR__ . '/assets/' . $args['path'];
    if (file_exists($file)) {
        $contentType = mime_content_type($file);
        $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
        if ($fileExtension === 'css') {
            $contentType = 'text/css';
        } elseif ($fileExtension === 'js') {
            $contentType = 'application/javascript';
        }
        return $response->withHeader('Content-Type', $contentType)
                        ->withBody(new \Slim\Psr7\Stream(fopen($file, 'r')));
    } else {
        throw new \Slim\Exception\HttpNotFoundException($request);
    }
});




$app->run(); 
?>







