<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

require __DIR__ . '/../../vendor/autoload.php';

$app = AppFactory::create();

// Create Twig & add to middleware
$twig = Twig::create(__DIR__ . '/../templates', ['cache' => false]);
$app->add(TwigMiddleware::create($app, $twig));

$app->get('/', function (Request $request, Response $response, $args) {
    $view = Twig::fromRequest($request);
    $validMimeTypes = ['image/png', 'image/jpeg'];

    $images = array_filter(glob('images/*'), fn($imgPath) => in_array(mime_content_type($imgPath), $validMimeTypes));

    return $view->render($response, 'home.html', [
        'images' => $images
    ]);
});

$app->run();
