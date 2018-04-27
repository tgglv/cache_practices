<?php declare(strict_types=1);

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Slim\{
    App,
    Views\Twig
};

abstract class BaseController
{
    /** @var App */
    protected $app;
    /** @var LoggerInterface */
    protected $logger;
    /** @var Twig */
    protected $twig;
    /**
     * BaseController constructor.
     * @param App $app
     * @param LoggerInterface $logger
     */
    public function __construct(App $app, LoggerInterface $logger, Twig $twig)
    {
        $this->app = $app;
        $this->logger = $logger;
        $this->twig = $twig;
    }

    public function __invoke()
    {

    }
}