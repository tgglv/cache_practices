<?php declare(strict_types=1);

use App\{
    Controller\BookController,
    Repository\BookRepository,
    Repository\CachedRepository
};

use Psr\Container\ContainerInterface;

$container = $app->getContainer();

// view renderer
$container['twig'] = function (ContainerInterface $c) {
    $settings = $c->get('settings');
    $view = new \Slim\Views\Twig($settings['twig']['templates'], [
        'cache' => $settings['twig']['cache']
    ]);

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $c->get('request')->getUri()->getBasePath()), '/');
    $view->addExtension(new \Slim\Views\TwigExtension($c['router'], $basePath));

    return $view;
};

// monolog
$container['logger'] = function (ContainerInterface $c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

$container[BookRepository::class] = function (ContainerInterface $c): BookRepository {
    return 'mysql' === getenv('SETTINGS_STORAGE_TYPE')
        ? new BookRepository
        : new CachedRepository;
};

$container[BookController::class] = function (ContainerInterface $c) use ($app): BookController {
    return new BookController($app, $c->get(BookRepository::class), $c->get('logger'), $c->get('twig'));
};