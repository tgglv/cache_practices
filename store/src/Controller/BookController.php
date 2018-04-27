<?php declare(strict_types=1);

namespace App\Controller;

use App\Repository\BookRepository;
use Psr\{
    Http\Message\ResponseInterface,
    Log\LoggerInterface
};

use Slim\{
    App,
    Http\Request,
    Http\Response,
    Views\Twig
};

class BookController extends BaseController
{
    /** @var BookRepository */
    private $bookRepository;

    public function __construct(App $app, BookRepository $bookRepository, LoggerInterface $logger, Twig $twig)
    {
        parent::__construct($app, $logger, $twig);
        $this->bookRepository = $bookRepository;

        $app->get('/{book_id}', [$this, 'getBookDetails']);
        $app->get('', [$this, 'getBookList']);
    }

    public function getBookDetails(Request $request, Response $response, $args): ResponseInterface
    {
        $bookId = $args['book_id'] ?? -1;
        $details = $this->bookRepository->getBookById((int)$bookId);
        $this->ensureBookImage($bookId, ['image_medium' => 'default_medium.png'], $details);

        return $this->twig->render($response, 'book.html.twig', compact('details'));
    }

    public function getBookList(Request $request, Response $response, $args): ResponseInterface
    {
        $list = $this->bookRepository->getLatestBooks();
        $keys = array_keys($list);
        foreach ($keys as $key) {
            $details = &$list[$key];
            $this->ensureBookImage($details['id'], ['image_small' => 'default_small.png'], $details);
        }
        return $this->twig->render($response, 'list.html.twig', compact('list'));
    }

    private function ensureBookImage($bookId, array $replacement, array &$details)
    {
        foreach ($replacement as $key => $defaultPath) {
            $path = __DIR__ . "/../../public/images/books/{$bookId}/{$details[$key]}";
            if (!file_exists($path)) {
                $path = __DIR__ . "/../../public/images/books/{$defaultPath}";
            }
            $details[$key] = substr($path, strpos($path, '/images/books'));
        }
    }
}