<?php declare(strict_types=1);

namespace Daemon;

class InvalidateRule
{
    /** @var string */
    private $hashName;
    /** @var bool */
    private $isSingleKey;
    /** @var string */
    private $primaryKey;

    public function __construct(string $hashName, bool $isSingleKey, ?string $primaryKey)
    {
        $this->hashName = $hashName;
        $this->isSingleKey = $isSingleKey;
        $this->primaryKey = $primaryKey;
    }

    public function getHashName(): string
    {
        return $this->hashName;
    }

    public function isSingleKey(): bool
    {
        return $this->isSingleKey;
    }

    public function hasPrimaryKey(): bool
    {
        return !empty($this->primaryKey);
    }

    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }
}