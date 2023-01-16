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
    $validMimeTypes = ['image/png', 'image/jpeg', 'image/webp'];
    $images = [];

    foreach (glob('images/thumbnails/*') as $imgPath) {
        if (! in_array(mime_content_type($imgPath), $validMimeTypes)) continue;

        $images[basename($imgPath)]['thumbnail'] = [
            'slug' => preg_replace('/[^A-Za-z0-9-]+/', '-', basename($imgPath)) . '-thumbnail',
            'filename' => basename($imgPath),
            'path' => $imgPath,
        ];
    }

    // foreach (glob('images/large/*') as $imgPath) {
    //     if (! in_array(mime_content_type($imgPath), $validMimeTypes)) continue;

    //     $images[basename($imgPath)]['large'] = [
    //         'slug' => preg_replace('/[^A-Za-z0-9-]+/', '-', basename($imgPath)) . '-large',
    //         'filename' => basename($imgPath),
    //         'path' => $imgPath,
    //     ];
    // }

    return $view->render($response, 'home.html', [
        'images' => $images
    ]);
});

$app->run();
