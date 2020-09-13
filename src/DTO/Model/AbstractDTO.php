<?php

declare(strict_types = 1);

namespace App\DTO\Model;

abstract class AbstractDTO
{
    protected $container = [];

    public function __set(string $name, $value): void
    {
        $this->container[$name] = $value;
        var_dump($name);
        var_dump($value);
    }

    public function __get(string $name)
    {
        return $this->container[$name] ?? null;
    }
}
