<?php

declare(strict_types = 1);

namespace App\DTO\Model;

use InvalidArgumentException;

abstract class AbstractDTO
{
    public function __set(string $name, $value): void
    {
        throw new InvalidArgumentException('Property is unavailable');
    }

    public function __get(string $name): void
    {
        throw new InvalidArgumentException('Property is unavailable');
    }
}
