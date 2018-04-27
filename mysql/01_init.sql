SET NAMES utf8;

CREATE TABLE bookstore.books (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `isbn` VARCHAR(255) NOT NULL COMMENT 'ISBN-код книги',
  `id_publisher` INT(11) NOT NULL COMMENT 'ID издателя',
  `id_category` INT(11) NOT NULL COMMENT 'ID категории',
  `name` VARCHAR(255) NOT NULL COMMENT 'Название',
  `description` TEXT NOT NULL COMMENT 'Описание',
  `publishing_date` DATE NOT NULL COMMENT 'Дата публикации',
  `page_number` SMALLINT NOT NULL COMMENT 'Количество страниц',
  `price` DECIMAL(16,2) NOT NULL COMMENT 'Цена',
  `image_small` VARCHAR(255) NULL COMMENT 'Малая картинка',
  `image_medium` VARCHAR(255) NULL COMMENT 'Средняя картинка',
  `quantity` SMALLINT NOT NULL COMMENT 'Количество книг на складе',
  PRIMARY KEY(`id`),
  KEY `idx_publisher` (`id_publisher`),
  KEY `idx_category` (`id_category`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE bookstore.authors (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  PRIMARY KEY(`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE bookstore.authors_books (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_book` INT(11) NOT NULL,
  `id_author` INT(11) NOT NULL,
  `order` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Порядок авторства',
  PRIMARY KEY(`id`),
  UNIQUE KEY `idx_author_book_order` (`id_author`, `id_book`),
  UNIQUE KEY `idx_book_order` (`id_book`, `order`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE bookstore.publishers (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  PRIMARY KEY(`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE bookstore.categories (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  PRIMARY KEY(`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

GRANT REPLICATION SLAVE, REPLICATION CLIENT ON *.* TO 'bookstore'@'%';
FLUSH PRIVILEGES;