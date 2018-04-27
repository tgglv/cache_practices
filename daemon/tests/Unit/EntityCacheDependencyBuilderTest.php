<?php declare(strict_types=1);

namespace Tests\Unit;

use Daemon\EntityCacheDependencyBuilder;
use Daemon\InvalidateRule;

class EntityCacheDependencyBuilderTest extends \PHPUnit_Framework_TestCase
{
    /** @var EntityCacheDependencyBuilder */
    private $builder;

    public function setUp()
    {
        $this->builder = new EntityCacheDependencyBuilder;
    }

    public function test1()
    {
        $bookPkRule = new InvalidateRule('cache:hash:book', false, 'id');
        $bookWholeRule = new InvalidateRule('cache:hash:book', false, null);
        $bookCollectionRule = new InvalidateRule('cache:hash:book_collection', true, null);

        $checkData = [
            'bookstore' => [
                'books' => [$bookPkRule, $bookCollectionRule],
                'publishers' => [$bookWholeRule, $bookCollectionRule],
                'categories' => [$bookWholeRule, $bookCollectionRule],
                'authors_books' => [$bookWholeRule, $bookCollectionRule,],
                'authors' => [$bookWholeRule, $bookCollectionRule]
            ]
        ];
        $data = $this->builder->build();
        $this->assertEquals($checkData, $data);
    }
}