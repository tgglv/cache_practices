<?php declare(strict_types=1);

namespace Daemon;

class BookRepository
{
    public function getBookById(int $id): array
    {
        $sql = <<<SQL_GET_BOOK
SELECT 
	b.id,
	b.isbn,
	b.name AS book_name,
	trim(group_concat(' ', a.name)) AS author_list,
	p.name AS publisher_name,
	c.name AS category_name,
	b.description,
	YEAR(b.publishing_date) AS publishing_year,
	b.page_number,
	b.price,
	b.image_small,
	b.image_medium,
	b.quantity > 0 AS in_stock
FROM bookstore.books b
JOIN bookstore.publishers p ON b.id_publisher = p.id
JOIN bookstore.categories c ON b.id_category = c.id
JOIN bookstore.authors_books ab ON b.id = ab.id_book 
JOIN bookstore.authors a ON a.id = ab.id_author
WHERE b.id = {$id}
GROUP BY b.id;
SQL_GET_BOOK;

        $res = mysqli_query(MysqlConnection::getInstance()->getLink(), $sql);
        return false !== $res && 1 === $res->num_rows
            ? $res->fetch_assoc()
            : [];
    }

    public function getLatestBooks(): array
    {
        $sql = <<<SQL_GET_BOOK_LIST
SELECT 
	b.id,
	b.name,
	trim(group_concat(' ', a.name)) AS author_list,
	p.name AS publisher,
	YEAR(b.publishing_date) AS publishing_year,
	b.price,
	b.image_small,
	b.quantity > 0 AS in_stock
FROM bookstore.books b
JOIN bookstore.publishers p ON b.id_publisher = p.id
JOIN bookstore.categories c ON b.id_category = c.id
JOIN bookstore.authors_books ab ON b.id = ab.id_book 
JOIN bookstore.authors a ON a.id = ab.id_author
WHERE b.quantity > 0
GROUP BY b.id
ORDER by RAND()
SQL_GET_BOOK_LIST;

        $res = mysqli_query(MysqlConnection::getInstance()->getLink(), $sql);
        if (false === $res || 0 === $res->num_rows) {
            return [];
        }

        $result = [];
        while ($row = $res->fetch_assoc()) {
            $result[] = $row;
        }
        return $result;
    }
}