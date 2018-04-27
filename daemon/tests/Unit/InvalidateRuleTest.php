<?php declare(strict_types=1);

namespace Tests\Unit;

use Daemon\InvalidateRule;

class InvalidateRuleTest extends \PHPUnit_Framework_TestCase
{
    const HASH_NAME = 'hash:name';

    public function testHashName(): void
    {
        $rule = new InvalidateRule(self::HASH_NAME, false, null);
        $this->assertEquals(self::HASH_NAME, $rule->getHashName());
    }

    public function testIsSingleKey(): void
    {
        foreach ([false, true] as $isSingleKey) {
            $rule = new InvalidateRule(self::HASH_NAME, $isSingleKey, null);
            $this->assertEquals($isSingleKey, $rule->isSingleKey());
        }
    }

    public function testPrimaryKey(): void
    {
        foreach ([null => false, 'id' => true] as $primaryKey => $hasPrimaryKey) {
            $rule = new InvalidateRule(self::HASH_NAME, false, $primaryKey);
            $this->assertEquals($primaryKey, $rule->getPrimaryKey());
            $this->assertEquals($hasPrimaryKey, $rule->hasPrimaryKey());
        }
    }
}