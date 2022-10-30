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

    $imageUrls = [
        'https://picsum.photos/300',
        'https://picsum.photos/350',
        'https://picsum.photos/360',
        'https://picsum.photos/370'
    ];

    return $view->render($response, 'home.html', [
        'imageUrls' => $imageUrls
    ]);
});

$app->run();
