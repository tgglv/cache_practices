<?php declare(strict_types=1);

namespace App\Service;

interface StorageConnectionInterface
{
    public function getConnection();
    public function __destruct();
}