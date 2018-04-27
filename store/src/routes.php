<?php declare(strict_types=1);

use App\Controller\BookController;

$app->group('/books', BookController::class);

$app->get(
    '/[{name}]',
    function (\Slim\Http\Request $request, \Slim\Http\Response $response, array $args) {
        $name = empty($args['name'])
            ? 'World'
            : ucfirst($args['name']);

        return $response->write(
            sprintf('Hello, %s! Current time is %d', $name, time())
        );
    }
);
